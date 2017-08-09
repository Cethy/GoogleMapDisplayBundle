<?php

namespace Cethyworks\GoogleMapDisplayBundle\Tests\Functional;

use Cethyworks\ContentInjectorBundle\EventSubscriber\ContentInjectorSubscriber;
use Cethyworks\GoogleMapDisplayBundle\Command\Handler\GoogleMapDisplayCommandHandler;
use Cethyworks\GoogleMapDisplayBundle\Tests\Functional\Mock\MockKernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class GoogleMapDisplayFunctionalTest extends WebTestCase
{
    public function testDisplayWithInjection()
    {
        // debug=false to be able to use the dispatcher
        // using debug=true, the container dispatcher throws a "LogicException: Event "__section__" is not started." at event dispatch
        $kernel = static::bootKernel(['debug' => false]);
        $container = $kernel->getContainer();

        /** @var EventDispatcherInterface $dispatcher */
        $dispatcher = $container->get('event_dispatcher');

        /** @var GoogleMapDisplayCommandHandler $handler */
        $handler = $container->get(GoogleMapDisplayCommandHandler::class);

        $handler->addMap('mapId1', 'foo, France');
        $handler->addMap('mapId2', 'bar, Spain');

        // trigger kernel.response
        $response = new Response('<body>foo</body>bar');
        $event = new FilterResponseEvent(self::$kernel, new Request(), HttpKernelInterface::MASTER_REQUEST, $response);

        $dispatcher->dispatch('kernel.response', $event);

        $expectedResponseContent = <<<EOF
<body>foo<script>
  function initMap() {
    /**
     * Display map if the address is correct, otherwise remove the html
     */
    var displayMap = function(geocoder, mapId, address) {
      geocoder.geocode({'address': address}, function (results, status) {
        if (status === 'OK') {
          var map = new google.maps.Map(document.getElementById(mapId), {
            center: results[0].geometry.location,
            zoom: 15
          });

          new google.maps.Marker({
            map: map,
            position: results[0].geometry.location
          });

        } else {
          document.getElementById(mapId).remove();
        }
      });
    };

    /**
     * eg:
     * addresses = [
     *      {'mapId': 'mapId1', address: 'foo, France'},
     *      {'mapId': 'mapId2', address: 'bar, Spain'}
     *  ];
     */
    var addresses = [{"mapId":"mapId1","address":"foo, France"},{"mapId":"mapId2","address":"bar, Spain"}];
    var geocoder = new google.maps.Geocoder();

    for(var i=0;i<addresses.length;i++) {
      displayMap(geocoder, addresses[i].mapId, addresses[i].address);
    }
  }
</script>
<script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initMap&key=bar_api_key"
        async defer></script>
</body>bar
EOF;

        $this->assertEquals($expectedResponseContent, $response->getContent());
    }

    /**
     * @return KernelInterface A KernelInterface instance
     */
    protected static function createKernel(array $options = array())
    {
        if (null === static::$class) {
            static::$class = MockKernel::class;
        }
        return new static::$class(
            isset($options['environment']) ? $options['environment'] : 'test',
            isset($options['debug']) ? $options['debug'] : true
        );
    }
}

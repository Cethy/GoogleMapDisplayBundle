<?php

namespace Cethyworks\GoogleMapDisplayBundle\Tests\Functional;

use Cethyworks\ContentInjectorBundle\Registerer\ListenerRegisterer;
use Cethyworks\GoogleMapDisplayBundle\Listener\SimpleGoogleMapDisplayListener;
use Cethyworks\GoogleMapDisplayBundle\Tests\Dummy\DummyKernel;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class SimpleGoogleMapDisplayFunctionalTest extends WebTestCase
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function setUp()
    {
        self::bootKernel();

        $this->container = self::$kernel->getContainer();
    }

    public function testJsCodeInjected()
    {
        $expectedCodeInjected = //file_get_contents(__DIR__ . '../../Resources/assets/twig/simple_google_map_js.html.twig');
            <<<EOF
<script>
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
<script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initMap"
        async defer></script>

EOF;
        /** @var SimpleGoogleMapDisplayListener $gMapListener */
        $gMapListener = $this->container->get('cethyworks.google_map_display.listener');

        $gMapListener->addMap('mapId1', 'foo, France');
        $gMapListener->addMap('mapId2', 'bar, Spain');

        /** @var EventDispatcherInterface $dispatcher */
        // do not use the container dispatcher because it throws a "LogicException: Event "__section__" is not started."
        // at event dispatch, exception which I do not understand :)
        $dispatcher = new EventDispatcher();//$this->container->get('event_dispatcher');

        /** @var ListenerRegisterer $registerer */
        $registerer = new ListenerRegisterer($dispatcher);//$this->container->get('cethyworks_content_injector.listener.registerer');
        $registerer->addListener([$gMapListener, 'onKernelResponse']);


        // trigger kernel.response
        $response = new Response('<body>foo</body>bar');
        $event = $this->createFilterResponseEvent($response);


        $dispatcher->dispatch('kernel.response', $event);

        $this->assertContains($expectedCodeInjected, $response->getContent());
    }

    /**
     * @return FilterResponseEvent
     */
    protected function createFilterResponseEvent(Response $response)
    {
        return new FilterResponseEvent(self::$kernel,
            new Request(),
            HttpKernelInterface::MASTER_REQUEST,
            $response
        );
    }

    /**
     * @return KernelInterface A KernelInterface instance
     */
    protected static function createKernel(array $options = array())
    {
        if (null === static::$class) {
            static::$class = DummyKernel::class;
        }

        return new static::$class(
            isset($options['environment']) ? $options['environment'] : 'test',
            isset($options['debug']) ? $options['debug'] : true
        );
    }
}

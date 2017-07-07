<?php

namespace Cethyworks\GoogleMapDisplayBundle\Tests\Listener;

use Cethyworks\ContentInjector\ContentTransformer\ContentTransformerInterface;
use Cethyworks\ContentInjector\Injector\InjectorInterface;
use Cethyworks\GoogleMapDisplayBundle\Listener\SimpleGoogleMapDisplayListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class SimpleGoogleMapDisplayListenerTest extends TestCase
{

    public function testAddMap()
    {
        $mapsData = [
            ['mapId' => 'map_id_1', 'address' => 'address_1'],
            ['mapId' => 'map_id_2', 'address' => 'address_2'],
        ];

        /** @var \PHPUnit_Framework_MockObject_MockObject|ContentTransformerInterface $contentTransformer */
        $contentTransformer = $this->getMock(ContentTransformerInterface::class);

        $contentTransformer->expects($this->once())
            ->method('transform')
            ->with(['addresses' => $mapsData])
        ;

        /** @var \PHPUnit_Framework_MockObject_MockObject|InjectorInterface $injector */
        $injector = $this->getMock(InjectorInterface::class);

        $injector->expects($this->once())
            ->method('inject')
            ->willReturn($injector)
        ;

        $listener = new SimpleGoogleMapDisplayListener($contentTransformer, $injector);

        foreach ($mapsData as $mapData) {
            $listener->addMap($mapData['mapId'], $mapData['address']);
        }

        $listener->onKernelResponse($this->createFilterResponseEvent());
    }

    /**
     * @return FilterResponseEvent
     */
    protected function createFilterResponseEvent()
    {
        $kernel   = $this->getMockBuilder(HttpKernelInterface::class)
            ->getMock()
        ;
        $response = new Response('foo');
        return new FilterResponseEvent($kernel,
            new Request(),
            HttpKernelInterface::MASTER_REQUEST,
            $response
        );
    }
}

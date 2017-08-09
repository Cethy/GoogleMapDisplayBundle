<?php

namespace Cethyworks\GoogleMapDisplayBundle\Tests\Command\Handler;

use Cethyworks\ContentInjectorBundle\EventSubscriber\ContentInjectorSubscriber;
use Cethyworks\GoogleMapDisplayBundle\Command\Handler\GoogleMapDisplayCommandHandler;
use PHPUnit\Framework\TestCase;

class GoogleMapDisplayCommandHandlerTest extends TestCase
{
    public function testHandler()
    {
        $injectorSubscriber = $this->getMockBuilder(ContentInjectorSubscriber::class)->disableOriginalConstructor()->getMock();
        $injectorSubscriber->expects($this->once())->method('registerCommand');
        
        $twig = $this->getMockBuilder(\Twig_Environment::class)->disableOriginalConstructor()->getMock();

        $handler = new GoogleMapDisplayCommandHandler($injectorSubscriber, $twig, 'google_api_key');

        $handler->addMap('id', 'address');
        $handler->addMap('id2', 'address2');
    }
}

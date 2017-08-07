<?php

namespace Cethyworks\GoogleMapDisplayBundle\Tests\Command\Wrapper;

use Cethyworks\GoogleMapDisplayBundle\Command\Wrapper\GoogleMapDisplayCommandWrapper;
use PHPUnit\Framework\TestCase;

class GoogleMapDisplayCommandWrapperTest extends TestCase
{
    public function testWrapper()
    {
        $twig = $this->getMockBuilder(\Twig_Environment::class)->disableOriginalConstructor()->getMock();
        $twig->expects($this->once())->method('render')->with(
            '@CethyworksGoogleMapDisplayBundle/Resources/assets/twig/simple_google_map_js.html.twig',
            ['google_api_key' => 'google_api_key',
                'addresses' => [ ['mapId' => 'id', 'address' => 'address'], ['mapId' => 'id2', 'address' => 'address2'] ]]);

        $wrapper = new GoogleMapDisplayCommandWrapper($twig, 'google_api_key');

        $wrapper->addMap('id', 'address');
        $wrapper->addMap('id2', 'address2');

        $command = $wrapper->getCommand();
        $command();
    }
}

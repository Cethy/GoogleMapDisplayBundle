<?php

namespace Cethyworks\GoogleMapDisplayBundle\Tests\Command;

use Cethyworks\GoogleMapDisplayBundle\Command\GoogleMapDisplayCommand;
use PHPUnit\Framework\TestCase;

class GoogleMapDisplayCommandTest extends TestCase
{
    public function testAddMap()
    {
        $twig = $this->getMockBuilder(\Twig_Environment::class)->disableOriginalConstructor()->getMock();
        $twig->expects($this->once())->method('render')->with(
            '@CethyworksGoogleMapDisplayBundle/Resources/assets/twig/simple_google_map_js.html.twig',
            ['addresses' => [ ['mapId' => 'id', 'address' => 'address'], ['mapId' => 'id2', 'address' => 'address2'] ]]);

        $command = new GoogleMapDisplayCommand($twig);

        $command->addMap('id', 'address');
        $command->addMap('id2', 'address2');

        $command();
    }

    public function testSetGoogleApiKey()
    {
        $twig = $this->getMockBuilder(\Twig_Environment::class)->disableOriginalConstructor()->getMock();
        $twig->expects($this->once())->method('render')->with(
            '@CethyworksGoogleMapDisplayBundle/Resources/assets/twig/simple_google_map_js.html.twig',
            ['google_api_key' => 'foobar' ]);

        $command = new GoogleMapDisplayCommand($twig);

        $command->setGoogleApiKey('foobar');

        $command();
    }
}

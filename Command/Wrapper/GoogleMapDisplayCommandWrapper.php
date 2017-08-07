<?php

namespace Cethyworks\GoogleMapDisplayBundle\Command\Wrapper;

use Cethyworks\GoogleMapDisplayBundle\Command\GoogleMapDisplayCommand;

/** Wrapper instead of factory to handle only one GoogleMapDisplayCommand during the request */
class GoogleMapDisplayCommandWrapper
{
    /**
     * @var GoogleMapDisplayCommand
     */
    protected $command;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * GoogleMapDisplayCommandWrapper constructor.
     *
     * @param \Twig_Environment $twig
     * @param string $apiKey
     */
    function __construct(\Twig_Environment $twig, $apiKey)
    {
        $this->twig = $twig;
        $this->command = $this->buildCommand($apiKey);
    }

    /**
     * Register a new map to display
     *
     * @param string $mapId
     * @param string $address
     */
    public function addMap($mapId, $address)
    {
        $this->command->addMap($mapId, $address);
    }
    
    /**
     * @return Callable
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param string $apiKey
     * @return GoogleMapDisplayCommand
     */
    protected function buildCommand($apiKey)
    {
        return (new GoogleMapDisplayCommand($this->twig))
            ->setGoogleApiKey($apiKey);
    }
}

<?php

namespace Cethyworks\GoogleMapDisplayBundle\Command\Handler;

use Cethyworks\ContentInjectorBundle\EventSubscriber\ContentInjectorSubscriber;
use Cethyworks\GoogleMapDisplayBundle\Command\GoogleMapDisplayCommand;

class GoogleMapDisplayCommandHandler
{
    /**
     * @var ContentInjectorSubscriber
     */
    protected $injectorSubscriber;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var GoogleMapDisplayCommand
     */
    protected $command;

    /**
     * @var bool
     */
    protected $registered = false;

    /**
     * GoogleMapDisplayCommandHandler constructor.
     *
     * @param ContentInjectorSubscriber $injectorSubscriber
     * @param \Twig_Environment $twig
     * @param string $apiKey
     */
    function __construct(ContentInjectorSubscriber $injectorSubscriber, \Twig_Environment $twig, $apiKey)
    {
        $this->injectorSubscriber = $injectorSubscriber;
        $this->twig               = $twig;

        $this->command            = $this->buildCommand($apiKey);
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

        if(! $this->registered) {
            $this->injectorSubscriber->registerCommand($this->command);
            $this->registered = true;
        }
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

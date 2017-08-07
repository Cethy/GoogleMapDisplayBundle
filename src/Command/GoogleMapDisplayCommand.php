<?php

namespace Cethyworks\GoogleMapDisplayBundle\Command;

use Cethyworks\ContentInjectorBundle\Command\TwigCommand;

class GoogleMapDisplayCommand extends TwigCommand
{
    protected $template = '@CethyworksGoogleMapDisplayBundle/Resources/assets/twig/simple_google_map_js.html.twig';

    /**
     * Add a new map to display
     *
     * @param string $mapId
     * @param string $address
     *
     * @return $this
     */
    public function addMap($mapId, $address)
    {
        $this->data['addresses'][] = [
            'mapId'   => $mapId,
            'address' => $address
        ];

        return $this;
    }

    /**
     * @param string $apiKey
     *
     * @return $this
     */
    public function setGoogleApiKey($apiKey)
    {
        $this->data['google_api_key'] = $apiKey;

        return $this;
    }
}

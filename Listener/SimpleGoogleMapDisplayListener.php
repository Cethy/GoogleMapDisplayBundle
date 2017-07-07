<?php

namespace Cethyworks\GoogleMapDisplayBundle\Listener;

use Cethyworks\ContentInjectorBundle\Listener\SimpleContentInjectorListener;

class SimpleGoogleMapDisplayListener extends SimpleContentInjectorListener
{
    /**
     * Register a new map to display
     *
     * @param string $mapId
     * @param string $address
     */
    public function addMap($mapId, $address)
    {
        $this->data['addresses'][] = [
            'mapId'   => $mapId,
            'address' => $address
        ];
    }
}

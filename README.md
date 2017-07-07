Cethyworks\GoogleMapDisplayBundle
===
Provides a way to display google maps from address(es) (w/ the javascript API), the most minimalist, unobtrusive way possible.


## How to use
1\. Update (optionally) your `config.yml` with :

    cethyworks_google_map_display:
        google:
            api_key: 'your_api_key'

2\. Add map info to the listener :

    // retrieve the listener
    /** @var \Cethyworks\GoogleMapDisplayBundle\Listener\SimpleGoogleMapDisplayListener $gMapListener */
    $gMapListener = $this->container->get('cethyworks.google_map_display.listener');
    // add address & html id to display the map
    $gMapListener->addMap('map_id', 'address 1');
    // optionnally add other maps 
    // $gMapListener->addMap('map2', 'address 2');

3\. Register the listener 

    // register the listener on the kernel.response event
     /** @var \Cethyworks\ContentInjectorBundle\Registerer\ListenerRegisterer $registerer */
     $registerer = $this->container->get('cethyworks_content_injector.listener.registerer');
     $registerer->addListener([$gMapListener, 'onKernelResponse']);

4\. That's it.


## How it works
The `Cethyworks\GoogleMapDisplayBundle\Listener\SimpleGoogleMapDisplayListener` listener will inject 
the javascript code needed (with mapIds, addresses & the google api_key) into the `Response` automatically.


## Additional information
[Google Map JS API Documentation](https://developers.google.com/maps/documentation/javascript/examples/map-simple)

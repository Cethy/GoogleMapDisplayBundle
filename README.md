Cethyworks\GoogleMapDisplayBundle
===
Provides a way to display google maps from address(es) (w/ the javascript API), the most minimalist, unobtrusive way possible.

[![CircleCI](https://circleci.com/gh/Cethy/GoogleMapDisplayBundle/tree/master.svg?style=shield)](https://circleci.com/gh/Cethy/GoogleMapDisplayBundle/tree/master)


## Install

1\. Composer require

    $ composer require cethyworks/google-map-display-bundle 

2\. Register bundles

    // AppKernel.php
    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = [
                // ...
                new Cethyworks\ContentInjectorBundle\CethyworksContentInjectorBundle(),
                new Cethyworks\GooglePlaceAutocompleteBundle\CethyworksGooglePlaceAutocompleteBundle(),
            ];
            // ...


## How to use
1\. Update (optionally) your `config.yml` with :

    cethyworks_google_map_display:
        google:
            api_key: 'your_api_key'

2\. Call the wrapper to add maps :

    // retrieve the command wrapper
    /** @var GoogleMapDisplayCommandWrapper $wrapper */
    $commandWrapper = $container->get(GoogleMapDisplayCommandWrapper::class);
    // add address & html id to display the map
    $commandWrapper->addMap('map_id', 'address 1');
    // optionnally add other maps 
    // $commandWrapper->addMap('map2', 'address 2');
    // ...

3\. Register the command into the `ContentInjectorSubscriber` : 

    /** @var ContentInjectorSubscriber $injectorSubscriber */
    $injectorSubscriber = $container->get(ContentInjectorSubscriber::class);
    $injectorSubscriber->registerCommand($commandWrapper->getCommand());

4\. Done !


## How it works
The `ContentInjectorSubscriber` will inject the template containing the javascript code (with mapIds, addresses & the google api_key) into the `Response` automatically.


## Additional information
[Cethyworks\ContentInjectorBundle](https://github.com/Cethy/ContentInjectorBundle)

[Google Map JS API Documentation](https://developers.google.com/maps/documentation/javascript/examples/map-simple)

<?php
/**
 * Factory Default
 *
*/
namespace Scene\DI;

use \Scene\DI;

/**
 * Scene\DI\FactoryDefault
 *
 * This is a variant of the standard Scene\DI. By default it automatically
 * registers all the services provided by the framework. Thanks to this, the developer does not need
 * to register each service individually providing a full stack framework
 *
 */
class FactoryDefault extends DI
{
    /**
     * \Scene\DI\FactoryDefault constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->_services = [
            /* Base */ 
            "router" =>             new Service("router", "Scene\\Mvc\\Router", true),
            "dispatcher" =>         new Service("dispatcher", "Scene\\Mvc\\Dispatcher", true),
            "url" =>                new Service("url", "Scene\\Mvc\\Url", true),
            
            /* Models */
            //"modelsManager" =>      new Service("modelsManager", "Scene\\Mvc\\Model\\Manager", true),
            //"modelsMetadata" =>     new Service("modelsMetadata", "Scene\\Mvc\\Model\\MetaData\\Memory", true),
            
            /* Request/Response */
            "response" =>           new Service("response", "Scene\\Http\\Response", true),
            "cookies" =>            new Service("cookies", "Scene\\Http\\Response\\Cookies", true),
            "request" =>            new Service("request", "Scene\\Http\\Request", true),
            
            /* Filter/Escaper */
            "filter" =>             new Service("filter", "Scene\\Filter", true),
            //"escaper" =>            new Service("escaper", "Scene\\Escaper", true),
            
            /* Security */
            "security" =>           new Service("security", "Scene\\Security", true),
            "crypt" =>              new Service("crypt", "Scene\\Crypt", true),
            
            /* Annotations */
            //"annotations" =>        new Service("annotations", "Scene\\Annotations\\Adapter\\Memory", true),
            
            /* Flash */
            //"flash" =>              new Service("flash", "Scene\\Flash\\Direct", true),
            //"flashSession" =>       new Service("flashSession", "Scene\\Flash\\Session", true),
            
            /* Tag/Helpers */
            //"tag" =>                new Service("tag", "Scene\\Tag", true),
            
            /* Session */
            "session" =>            new Service("session", "Scene\\Session\\Adapter\\Files", true),
            "sessionBag" =>         new Service("sessionBag", "Scene\\Session\\Bag"),
            
            /* Managers */
            "eventsManager" =>      new Service("eventsManager", "Scene\\Events\\Manager", true),
            //"transactionManager" => new Service("transactionManager", "Scene\\Mvc\\Model\\Transaction\\Manager", true),
            
            //"assets" =>             new Service("assets", "Scene\\Assets\\Manager", true)
        ];

    }
}

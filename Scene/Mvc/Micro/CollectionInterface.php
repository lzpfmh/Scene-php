<?php
/**
 * Collection Interface
 *
*/

namespace Scene\Mvc\Micro;

/**
 * Scene\Mvc\Micro\CollectionInterface
 *
 * Interface for Scene\Mvc\Micro\Collection
 */
interface CollectionInterface
{
    
    /**
     * Sets a prefix for all routes added to the collection
     *
     * @param string $prefix
     * @return \Scene\Mvc\Micro\CollectionInterface
     */
    public function setPrefix($prefix);

    /**
     * Returns the collection prefix if any
     *
     * @return string
     */
    public function getPrefix();

    /**
     * Returns the registered handlers
     *
     * @return array
     */
    public function getHandlers();

    /**
     * Sets the main handler
     *
     * @param mixed $handler
     * @param boolean|null $lazy
     * @return \Scene\Mvc\Micro\CollectionInterface
     */
    public function setHandler($handler, $lazy = false);

    /**
     * Sets if the main handler must be lazy loaded
     *
     * @param boolean $lazy
     * @return \Scene\Mvc\Micro\CollectionInterface
     */
    public function setLazy($lazy);

    /**
     * Returns if the main handler must be lazy loaded
     *
     * @return boolean
     */
    public function isLazy();

    /**
     * Returns the main handler
     *
     * @return mixed
     */
    public function getHandler();

    /**
     * Maps a route to a handler
     *
     * @param string $routePattern
     * @param callable $handler
     * @param string $name
     * @return \Scene\Mvc\Router\RouteInterface
     */
    public function map($routePattern, $handler, $name = null);

    /**
     * Maps a route to a handler that only matches if the HTTP method is GET
     *
     * @param string $routePattern
     * @param callable $handler
     * @param string $name
     * @return \Scene\Mvc\Router\RouteInterface
     */
    public function get($routePattern, $handler, $name = null);

    /**
     * Maps a route to a handler that only matches if the HTTP method is POST
     *
     * @param string $routePattern
     * @param callable $handler
     * @param string $name
     * @return \Scene\Mvc\Router\RouteInterface
     */
    public function post($routePattern, $handler, $name = null);

    /**
     * Maps a route to a handler that only matches if the HTTP method is PUT
     *
     * @param string $routePattern
     * @param callable $handler
     * @param string $name
     * @return \Scene\Mvc\Router\RouteInterface
     */
    public function put($routePattern, $handler, $name = null);

    /**
     * Maps a route to a handler that only matches if the HTTP method is PATCH
     *
     * @param string $routePattern
     * @param callable $handler
     * @param string $name
     * @return \Scene\Mvc\Router\RouteInterface
     */
    public function patch($routePattern, $handler, $name = null);

    /**
     * Maps a route to a handler that only matches if the HTTP method is HEAD
     *
     * @param string $routePattern
     * @param callable $handler
     * @param string $name
     * @return \Scene\Mvc\Router\RouteInterface
     */
    public function head($routePattern, $handler, $name = null);

    /**
     * Maps a route to a handler that only matches if the HTTP method is DELETE
     *
     * @param string $routePattern
     * @param callable $handler
     * @param string $name
     * @return \Scene\Mvc\Router\RouteInterface
     */
    public function delete($routePattern, $handler, $name = null);

    /**
     * Maps a route to a handler that only matches if the HTTP method is OPTIONS
     *
     * @param string $routePattern
     * @param callable $handler
     * @param string $name
     * @return \Scene\Mvc\Router\RouteInterface
     */
    public function options($routePattern, $handler, $name = null);
}

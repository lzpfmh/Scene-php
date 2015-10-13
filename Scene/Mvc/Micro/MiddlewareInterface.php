<?php
/**
 * Middleware Interface
 *
*/
namespace Scene\Mvc\Micro;

/**
 * Scene\Mvc\Micro\MiddlewareInterface initializer
 *
 */
interface MiddlewareInterface
{
    /**
     * Calls the middleware
     *
     * @param \Scene\Mvc\Micro $application
     */
    public function call($application);
}

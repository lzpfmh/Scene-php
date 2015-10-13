<?php
/**
 * 依赖注入接口
 *
*/
namespace Scene;

use Scene\DiInterface;
use Scene\Di\ServiceInterface;

/**
 * Scene\DiInterface
 *
 * Interface for Scene\Di
 */
interface DiInterface extends \ArrayAccess
{
    /**
     * 在服务容器中注册服务
     *
     * @param string! $name
     * @param mixed $definition
     * @param boolean $shared
     * @return \Scene\DI\ServiceInterface
     */
    public function set($name, $definition, $shared = null);

    /**
     * Registers an "always shared" service in the services container
     *
     * @param string! $name
     * @param mixed $definition
     * @return \Scene\DI\ServiceInterface
     */
    public function setShared($name, $definition);

    /**
     * Removes a service in the services container
     *
     * @param string! $name
     */
    public function remove($name);

    /**
     * Attempts to register a service in the services container
     * Only is successful if a service hasn't been registered previously
     * with the same name
     *
     * @param string! $name
     * @param mixed $definition
     * @param boolean $shared
     * @return \Scene\DI\ServiceInterface
     */
    public function attempt($name, $definition, $shared = null);

    /**
     * Resolves the service based on its configuration
     *
     * @param string! $name
     * @param array $parameters
     * @return mixed
     */
    public function get($name, $parameters = null);

    /**
     * Returns a shared service based on their configuration
     *
     * @param string! $name
     * @param array $parameters
     * @return mixed
     */
    public function getShared($name, $parameters = null);

    /**
     * Sets a service using a raw \Scene\DI\Service definition
     *
     * @param string! $name
     * @param \Scene\DI\ServiceInterface $rawDefinition
     * @return \Scene\DI\ServiceInterface
     */
    public function setRaw($name, $rawDefinition);

    /**
     * Returns a service definition without resolving
     *
     * @param string! $name
     * @return mixed
     */
    public function getRaw($name);

    /**
     * Returns the corresponding \Scene\Di\Service instance for a service
     *
     * @param string! $name
     * @return \Scene\DI\ServiceInterface
     */
    public function getService($name);

    /**
     * Check whether the DI contains a service by a name
     *
     * @param string! $name
     * @return boolean
     */
    public function has($name);

    /**
     * Check whether the last service obtained via getShared produced a fresh instance or an existing one
     *
     * @return boolean
     */
    public function wasFreshInstance();

    /**
     * Return the services registered in the DI
     *
     * @return \Scene\Di\ServiceInterface[]
     */
    public function getServices();

    /**
     * Set a default dependency injection container to be obtained into static methods
     *
     * @param \Scene\DiInterface $dependencyInjector
     */
    public static function setDefault($dependencyInjector);

    /**
     * Return the last DI created
     *
     * @return \Scene\DiInterface
     */
    public static function getDefault();

    /**
     * Resets the internal default DI
     */
    public static function reset();
}

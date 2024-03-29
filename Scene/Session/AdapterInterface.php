<?php
/**
 * Adapter Interface
 *
*/

namespace Scene\Session;

/**
 * Scene\Session\AdapterInterface
 *
 * Interface for Scene\Session adapters
 */
interface AdapterInterface
{

    /**
     * Starts session, optionally using an adapter
     */
    public function start();

    /**
     * Sets session options
     *
     * @param array $options
     */
    public function setOptions($options);

    /**
     * Get internal options
     *
     * @return array
     */
    public function getOptions();

    /**
     * Gets a session variable from an application context
     *
     * @param string $index
     * @param mixed $defaultValue
     * @return array
     */
    public function get($index, $defaultValue = null);

    /**
     * Sets a session variable in an application context
     *
     * @param string $index
     * @param string $value
     */
    public function set($index, $value);

    /**
     * Check whether a session variable is set in an application context
     *
     * @param string $index
     * @return boolean
     */
    public function has($index);

    /**
     * Removes a session variable from an application context
     *
     * @param string $index
     */
    public function remove($index);

    /**
     * Returns active session id
     *
     * @return string
     */
    public function getId();

    /**
     * Check whether the session has been started
     *
     * @return boolean
     */
    public function isStarted();

    /**
     * Destroys the active session
     *
     * @return boolean
     */
    public function destroy();

    /**
     * Regenerate session's id
     *
     * @param boolean
     * @return Scene\Session\AdapterInterface
     */
    public function regenerateId($deleteOldSession = true);

    /**
     * Set session name
     *
     * @param string $name
     */
    public function setName($name);

    /**
     * Get session name
     *
     * @param string
     */
    public function getName();
}

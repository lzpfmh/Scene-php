<?php
/**
 * Manager Interface
 *
*/
namespace Scene\Events;

/**
 * Scene\Events\Manager
 *
 * Scene Events Manager, offers an easy way to intercept and manipulate, if needed,
 * the normal flow of operation. With the EventsManager the developer can create hooks or
 * plugins that will offer monitoring of data, manipulation, conditional execution and much more.
 */
interface ManagerInterface
{
    /**
     * Attach a listener to the events manager
     *
     * @param string $eventType
     * @param object $handler
     */
    public function attach($eventType, $handler);

    /**
     * Detach the listener from the events manager
     *
     * @param string eventType
     * @param object handler
     */
    public function detach($eventType, $handler);


    /**
     * Removes all events from the EventsManager
     *
     * @param string|null $type
     */
    public function dettachAll($type = null);

    /**
     * Fires a event in the events manager causing that the acive listeners will be notified about it
     *
     * @param string $eventType
     * @param object $source
     * @param mixed|null $data
     * @param boolean|null $cancelable
     * @return mixed
     */
    public function fire($eventType, $source, $data = null);

    /**
     * Returns all the attached listeners of a certain type
     *
     * @param string $type
     * @return array
     */
    public function getListeners($type);
}

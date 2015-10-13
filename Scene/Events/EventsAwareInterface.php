<?php
/**
 * Events Aware Interface
 *
*/
namespace Scene\Events;

/**
 * Scene\Events\EventsAwareInterface initializer
 *
 * This interface must for those classes that accept an EventsManager and dispatch events
 */
interface EventsAwareInterface
{
    /**
     * Sets the events manager
     *
     * @param \Scene\Events\ManagerInterface $eventsManager
     */
    public function setEventsManager($eventsManager);

    /**
     * Returns the internal event manager
     *
     * @return \Scene\Events\ManagerInterface
     */
    public function getEventsManager();
}

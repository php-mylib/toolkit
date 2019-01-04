<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-03-27
 * Time: 16:17
 */

namespace Toolkit\Traits\Event;

use Toolkit\PhpUtil\PhpHelper;

/**
 * Class FixedEventStaticTrait
 * @package Toolkit\Traits\Event
 */
trait FixedEventStaticTrait
{
    /**
     * set the supported events, if you need.
     *  if it is empty, will allow register any event.
     * @var array
     */
    protected static $supportedEvents = [];

    /**
     * registered Events
     * @var array
     * [
     *  'event' => bool, // is once event
     * ]
     */
    private static $events = [];

    /**
     * events and handlers
     * @var array
     * [
     *  'event' => callable, // event handler
     * ]
     */
    private static $eventHandlers = [];

    /**
     * register a event handler
     * @param          $event
     * @param callable $handler
     * @param bool     $once
     */
    public static function on($event, callable $handler, $once = false)
    {
        if (self::isSupportedEvent($event)) {
            self::$eventHandlers[$event][] = $handler;
            self::$events[$event] = (bool)$once;
        }
    }

    /**
     * register a once event handler
     * @param          $event
     * @param callable $handler
     */
    public static function once($event, callable $handler)
    {
        self::on($event, $handler, true);
    }

    /**
     * trigger event
     * @param       $event
     * @param array $args
     * @return bool
     */
    public static function fire($event, array $args = []): bool
    {
        if (!isset(self::$events[$event])) {
            return false;
        }

        // call event handlers of the event.
        foreach ((array)self::$eventHandlers[$event] as $cb) {
            // return FALSE to stop go on handle.
            if (false === PhpHelper::call($cb, ...$args)) {
                break;
            }
        }

        // is a once event, remove it
        if (self::$events[$event]) {
            return self::off($event);
        }

        return true;
    }

    /**
     * remove event and it's handlers
     * @param $event
     * @return bool
     */
    public static function off($event): bool
    {
        if (self::hasEvent($event)) {
            unset(self::$events[$event], self::$eventHandlers[$event]);

            return true;
        }

        return false;
    }

    /**
     * @param $event
     * @return bool
     */
    public static function hasEvent($event): bool
    {
        return isset(self::$events[$event]);
    }

    /**
     * @param $event
     * @return bool
     */
    public static function isOnce($event): bool
    {
        if (self::hasEvent($event)) {
            return self::$events[$event];
        }

        return false;
    }

    /**
     * check $name is a supported event name
     * @param $event
     * @return bool
     */
    public static function isSupportedEvent($event): bool
    {
        if (!$event || !preg_match('/[a-zA-z][\w-]+/', $event)) {
            return false;
        }

        if ($ets = self::$supportedEvents) {
            return \in_array($event, $ets, true);
        }

        return true;
    }

    /**
     * @return array
     */
    public static function getSupportEvents(): array
    {
        return self::$supportedEvents;
    }

    /**
     * @param array $supportedEvents
     */
    public static function setSupportEvents(array $supportedEvents)
    {
        self::$supportedEvents = $supportedEvents;
    }

    /**
     * @return array
     */
    public static function getEvents(): array
    {
        return self::$events;
    }

    /**
     * @return int
     */
    public static function countEvents(): int
    {
        return \count(self::$events);
    }
}

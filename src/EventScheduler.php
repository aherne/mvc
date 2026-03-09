<?php

namespace Lucinda\MVC;

final class EventScheduler
{
    private array $events = [];

    public function add(EventType $eventType, string $className): void
    {
        if (!class_exists($className) || !is_subclass_of($className, EventListener::class)) {
            throw new ConfigurationException("Class must exist and be an EventListener: ".$className);
        }
        if (isset($this->events[$eventType->value][$className])) {
            throw new ConfigurationException("Event already registered: ".$className);
        }
        $this->events[$eventType->value][$className] = $className;
    }

    public function get(EventType $eventType): array
    {
        return $this->events[$eventType->value]??[];
    }
}
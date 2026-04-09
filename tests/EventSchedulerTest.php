<?php

namespace Test\Lucinda\MVC;

use Lucinda\MVC\ConfigurationException;
use Lucinda\MVC\EventScheduler;
use Lucinda\MVC\EventType;
use Lucinda\UnitTest\Validator\Arrays;
use Lucinda\UnitTest\Validator\Strings;
use Test\Lucinda\MVC\Support\DemoFacetedListener;
use Test\Lucinda\MVC\Support\DemoTransformerListener;
use Test\Lucinda\MVC\Support\DemoUnFacetedListener;

class EventSchedulerTest
{
    public function add()
    {
        $results = [];
        $scheduler = new EventScheduler();
        $scheduler->add(EventType::START, DemoUnFacetedListener::class);

        $results[] = (new Arrays($scheduler->get(EventType::START)))->assertContainsValue(
            DemoUnFacetedListener::class
        );

        try {
            $scheduler->add(EventType::START, DemoUnFacetedListener::class);
            $results[] = (new Strings(""))->assertNotEmpty("duplicate listener should fail");
        } catch (ConfigurationException $e) {
            $results[] = (new Strings($e->getMessage()))->assertContains("Event already registered");
        }

        try {
            $scheduler->add(EventType::RESPONSE, DemoUnFacetedListener::class);
            $results[] = (new Strings(""))->assertNotEmpty("response listener should implement transformer");
        } catch (ConfigurationException $e) {
            $results[] = (new Strings($e->getMessage()))->assertContains("Response event listeners must implement");
        }

        try {
            $scheduler->add(EventType::RESPONSE, DemoFacetedListener::class);
            $results[] = (new Strings(""))->assertNotEmpty("response listener should not be faceted");
        } catch (ConfigurationException $e) {
            $results[] = (new Strings($e->getMessage()))->assertContains("must not be faceted");
        }

        try {
            $scheduler->add(EventType::RESPONSE, DemoTransformerListener::class);
            $results[] = (new Arrays($scheduler->get(EventType::RESPONSE)))->assertContainsValue(
                DemoTransformerListener::class
            );
        } catch (ConfigurationException $e) {
            $results[] = (new Strings($e->getMessage()))->assertNotEmpty("valid response listener should be accepted");
        }

        return $results;
    }

    public function get()
    {
        $scheduler = new EventScheduler();
        $scheduler->add(EventType::APPLICATION, DemoUnFacetedListener::class);
        return [
            (new Arrays($scheduler->get(EventType::APPLICATION)))->assertSize(1),
            (new Arrays($scheduler->get(EventType::APPLICATION)))->assertContainsValue(DemoUnFacetedListener::class)
        ];
    }
}

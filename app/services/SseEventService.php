<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class SseEventService
{
    /**
     * Publish an SSE event by storing it in the cache.
     *
     * Each event gets a unique key based on the current timestamp and
     * a random component so multiple events published in the same
     * second are never overwritten.
     */
    public static function publish(string $eventName, array $payload = []): void
    {
        $event = [
            'event'     => $eventName,
            'data'      => $payload,
            'timestamp' => microtime(true),
        ];

        // Push the event onto a cached list that the SSE controller reads.
        $events = Cache::get('sse_events', []);
        $events[] = $event;

        // Keep only events from the last 30 seconds to avoid unbounded growth.
        $cutoff = microtime(true) - 30;
        $events = array_values(array_filter($events, fn ($e) => $e['timestamp'] > $cutoff));

        Cache::put('sse_events', $events, now()->addMinutes(1));
    }

    /**
     * Retrieve all events newer than the given timestamp.
     */
    public static function getEventsSince(float $since): array
    {
        $events = Cache::get('sse_events', []);

        return array_values(array_filter($events, fn ($e) => $e['timestamp'] > $since));
    }
}

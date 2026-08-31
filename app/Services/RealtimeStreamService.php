<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class RealtimeStreamService
{
    const CACHE_KEY = 'sim_bimtek_realtime_events';
    const MAX_EVENTS = 50;

    /**
     * Push a new real-time event to the event ring buffer.
     */
    public static function pushEvent(string $eventType, array $payload): void
    {
        $event = [
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'event' => $eventType,
            'data' => $payload,
            'time' => microtime(true),
            'created_at' => now()->toIso8601String(),
        ];

        $events = Cache::get(self::CACHE_KEY, []);
        $events[] = $event;

        // Keep only recent events
        if (count($events) > self::MAX_EVENTS) {
            $events = array_slice($events, -self::MAX_EVENTS);
        }

        Cache::put(self::CACHE_KEY, $events, now()->addHours(6));
    }

    /**
     * Get events that occurred after the specified timestamp.
     * If no timestamp provided, only returns events from the last 1 second to prevent replaying stale events.
     */
    public static function getEventsAfter(?float $lastTime = null): array
    {
        $events = Cache::get(self::CACHE_KEY, []);
        $cutoff = $lastTime ?? (microtime(true) - 1.0);

        return array_values(array_filter($events, function ($e) use ($cutoff) {
            return ($e['time'] ?? 0) > $cutoff;
        }));
    }
}

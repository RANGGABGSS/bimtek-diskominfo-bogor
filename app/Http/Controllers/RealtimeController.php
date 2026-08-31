<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Services\RealtimeStreamService;

class RealtimeController extends Controller
{
    /**
     * Non-blocking real-time event check (Ultra-fast, zero thread starvation).
     */
    public function poll(Request $request)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $since = $request->query('since') ? (float) $request->query('since') : null;
        $events = RealtimeStreamService::getEventsAfter($since);

        return response()->json([
            'status' => 'connected',
            'events' => $events,
            'current_time' => microtime(true),
        ]);
    }

    /**
     * Lightweight non-blocking SSE endpoint.
     */
    public function stream(Request $request): StreamedResponse
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Fitur real-time hanya untuk Administrator.');
        }

        $since = $request->query('since') ? (float) $request->query('since') : null;
        $events = RealtimeStreamService::getEventsAfter($since);
        $currentTime = microtime(true);

        $response = new StreamedResponse(function () use ($events, $currentTime) {
            echo "event: connected\n";
            echo "data: " . json_encode(['status' => 'connected', 'time' => $currentTime]) . "\n\n";

            if (!empty($events)) {
                foreach ($events as $ev) {
                    echo "event: " . ($ev['event'] ?? 'message') . "\n";
                    echo "data: " . json_encode($ev['data'] ?? []) . "\n\n";
                }
            }
            flush();
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->headers->set('Connection', 'close'); // Release thread immediately

        return $response;
    }
}

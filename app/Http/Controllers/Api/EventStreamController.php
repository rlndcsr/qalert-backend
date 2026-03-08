<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SseEventService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EventStreamController extends Controller
{
    /**
     * Stream server-sent events to the client.
     */
    public function stream(): StreamedResponse
    {
        $response = new StreamedResponse(function () {
            // Track the last time we checked for events.
            $lastEventTime = microtime(true);

            // Track seconds since the last heartbeat was sent.
            $lastHeartbeat = time();

            while (true) {
                // Abort if the client disconnected.
                if (connection_aborted()) {
                    break;
                }

                // Check for new events since our last poll.
                $events = SseEventService::getEventsSince($lastEventTime);

                foreach ($events as $event) {
                    echo "event: {$event['event']}\n";
                    echo 'data: ' . json_encode($event['data']) . "\n\n";

                    // Advance the pointer so we don't re-send.
                    if ($event['timestamp'] > $lastEventTime) {
                        $lastEventTime = $event['timestamp'];
                    }
                }

                // Send a heartbeat comment every 15 seconds to keep
                // the connection alive through ngrok / proxies.
                if (time() - $lastHeartbeat >= 15) {
                    echo ": heartbeat\n\n";
                    $lastHeartbeat = time();
                }

                // Flush output buffers so the client receives data immediately.
                if (ob_get_level()) {
                    ob_flush();
                }
                flush();

                // Sleep 2 seconds between polls to keep CPU usage low.
                sleep(2);
            }
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');
        $response->headers->set('X-Accel-Buffering', 'no');

        return $response;
    }
}

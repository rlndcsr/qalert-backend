<?php

namespace App\Http\Controllers\Api;

use App\Models\QueueEntry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\QueueEntriesRequest;

class QueueEntriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return QueueEntry::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QueueEntriesRequest $request)
    {
        $validated = $request->validated();

        $validated['date'] = now()->toDateString();

        $validated['queue_status'] = 'waiting';

        $latestQueueNumber = QueueEntry::whereDate('date', $validated['date'])
            ->max('queue_number');

        $nextQueueNumber = $latestQueueNumber ? $latestQueueNumber + 1 : 1;

        $validated['queue_number'] = $nextQueueNumber;

        $queueEntry = QueueEntry::create($validated);
        
        return response()->json([
            'message' => 'Queue entry created successfully',
            'queue_entry' => $queueEntry
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $queueEntry = QueueEntry::findOrFail($id);
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function updateQueueStatus(QueueEntriesRequest $request, string $id)
    {
         $validated = $request->validated();

        $queueEntry = QueueEntry::findOrFail($id);

        $queueEntry->update($validated);

        return response()->json([
            'message' => 'Queue entry updated successfully',
            'queue_entry' => $queueEntry
        ]);
    }

      /**
     * Update the specified resource in storage.
     */
    public function updateQueueReason(QueueEntriesRequest $request, string $id)
    {
         $validated = $request->validated();

        $queueEntry = QueueEntry::findOrFail($id);

        $queueEntry->update($validated);

        return response()->json([
            'message' => 'Queue entry updated successfully',
            'queue_entry' => $queueEntry
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $queueEntry = QueueEntry::findOrFail($id);

        $queueEntry->delete();

        return response()->json([
            'message' => 'Queue entry deleted successfully',
            'queue_entry' => $queueEntry
        ]);
    }
}

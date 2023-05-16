<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Resources\EventResource;
use App\Http\Requests\StoreUpdateEventRequest;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //return response()->json(Event::paginate(15));

        if ($request->attribute && $request->search){
            return response()->json(DB::table('events')
                                    ->select('events.id', 'events.name', 'events.date_start_enrollments', 'events.date_end_enrollments', 'events.date_start_event', 'events.date_end_event','events.year', 'events.course_url', 'events.image_url', 'event_categories.name AS category_name', 'event_categories.description AS category_description', 'events.base_penalty', 'events.point_calc_reason')
                                    ->join('event_categories', 'events.category_id', '=', 'event_categories.id')
                                    ->whereRaw("UPPER({$request->attribute}) LIKE CONCAT('%', UPPER('{$request->search}'), '%')")
                                    ->orderBy($request->column, $request->order)
                                    ->paginate(15));
        }
        return response()->json(DB::table('events')
                                    ->select('events.id', 'events.name', 'events.date_start_enrollments', 'events.date_end_enrollments', 'events.date_start_event', 'events.date_end_event','events.year', 'events.course_url', 'events.image_url', 'event_categories.name AS category_name', 'event_categories.description AS category_description', 'events.base_penalty', 'events.point_calc_reason')
                                    ->join('event_categories', 'events.category_id', '=', 'event_categories.id')
                                    ->orderBy($request->column, $request->order)
                                    ->paginate(15));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateEventRequest $request)
    {
        $newEvent = Event::create($request->validated());
        return new EventResource($newEvent);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return new EventResource($event);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateEventRequest $request, Event $event)
    {
        $event->update($request->validated());
        return new EventResource($event);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return new EventResource($event);
    }
}

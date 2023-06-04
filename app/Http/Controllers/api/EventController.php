<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Resources\EventResource;
use App\Http\Requests\StoreUpdateEventRequest;
use App\Models\EventCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{

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

    public function store(StoreUpdateEventRequest $request)
    {
        //dd($request->validated());

        $event = $request->validated();
        //dd($request->hasFile('image_file'));
        if ($request->hasFile('image_file')) {
            $path = Storage::putFile('public/fotos/eventos', $request->file('image_file'));
            $event['image_url'] = basename($path);
        }
        if ($request->hasFile('course_file')) {
            $path = Storage::putFile('public/circuitos', $request->file('course_file'));
            $event['course_url'] = basename($path);
        }

        $newEvent = Event::create($event);

        return new EventResource($newEvent);
    }

    public function show(Event $event)
    {
        return new EventResource($event);
    }

    public function update(StoreUpdateEventRequest $request, Event $event)
    {

        $validated_data = $request->validated();
        //dd($request->validated());
        if ($request->hasFile('image_file')) {
            $path = Storage::putFile('public/fotos/eventos', $request->file('image_file'));
            $event->image_url = basename($path);
        }

        if ($request->hasFile('course_file')) {
            $path = Storage::putFile('public/circuitos', $request->file('course_file'));
            $event->course_url = basename($path);
        }

        $event->name = $validated_data['name'];
        $event->date_start_enrollments = $validated_data['date_start_enrollments'];
        $event->date_end_enrollments = $validated_data['date_end_enrollments'];
        $event->date_start_event = $validated_data['date_start_event'];
        $event->date_end_event = $validated_data['date_end_event'];
        $event->year = $validated_data['year'];

        $event->category_id = $validated_data['category_id'];
        $event->base_penalty = $validated_data['base_penalty'];
        $event->point_calc_reason = $validated_data['point_calc_reason'];

        $event->save();

        return new EventResource($event);
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return new EventResource($event);
    }

    public function getEventsWithCategory(int $categoryId)
    {
        return response()->json(DB::table('events')->where('category_id', $categoryId)->get());
    }
}

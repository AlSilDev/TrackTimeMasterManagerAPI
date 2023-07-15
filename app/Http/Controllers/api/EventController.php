<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Resources\EventResource;
use App\Http\Requests\StoreUpdateEventRequest;
use App\Models\EventCategory;
use App\Models\Stage;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use stdClass;

class EventController extends Controller
{

    public function index(Request $request)
    {
        //return response()->json(Event::paginate(15));

        if ($request->attribute && $request->search){
            return response()->json(DB::table('events')
                                    ->select('events.id', 'events.name', 'events.date_start_enrollments', 'events.date_end_enrollments', 'events.date_start_event', 'events.date_end_event','events.year', 'events.course_url', 'events.image_url', 'event_categories.name AS category_name', 'event_categories.description AS category_description', 'events.base_penalty', 'events.point_calc_reason')
                                    ->join('event_categories', 'events.category_id', '=', 'event_categories.id')
                                    ->whereRaw("UPPER(events.{$request->attribute}) LIKE CONCAT('%', UPPER('{$request->search}'), '%')")
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
            $path = Storage::putFile('eventos', $request->file('image_file'));
            $event['image_url'] = basename($path);
        }
        if ($request->hasFile('course_file')) {
            $path = Storage::putFile('circuitos', $request->file('course_file'));
            $event['course_url'] = basename($path);
        }

        $dateAux = new DateTime($event['date_start_enrollments'], new DateTimeZone('UTC'));
        $event['date_start_enrollments'] = $dateAux->setTimezone(new DateTimeZone('Europe/Lisbon'));

        $dateAux = new DateTime($event['date_end_enrollments'], new DateTimeZone('UTC'));
        $event['date_end_enrollments'] = $dateAux->setTimezone(new DateTimeZone('Europe/Lisbon'));

        $dateAux = new DateTime($event['date_start_event'], new DateTimeZone('UTC'));
        $event['date_start_event'] = $dateAux->setTimezone(new DateTimeZone('Europe/Lisbon'));

        $dateAux = new DateTime($event['date_end_event'], new DateTimeZone('UTC'));
        $event['date_end_event'] = $dateAux->setTimezone(new DateTimeZone('Europe/Lisbon'));

        //dd($event['date_start_event']);
        //$event['year'] = DateTimeImmutable::createFromFormat(DateTimeInterface::ISO8601, $event['date_start_event']);
        $event['year'] = $event['date_start_event']->format('Y');
        //dd($event['year']);
        //$event['year'] = $event['year']->format('Y');
        //dd($event['year']);

        $newEvent = Event::create($event);

        return new EventResource($newEvent);
    }

    public function show(Event $event)
    {
        return new EventResource($event);
    }

    public function getClassifications(Event $event)
    {
        $categories_in_event = DB::table('participants AS p')
                                ->select('vcc.name')
                                ->join('enrollments AS e', 'e.id', '=', 'p.enrollment_id')
                                ->join('vehicle_history AS v', 'v.id', '=', 'p.vehicle_id')
                                ->join('vehicle_classes AS vc', 'vc.id', '=', 'v.class_id')
                                ->join('vehicle_categories AS vcc', 'vcc.id', '=', 'vc.category_id')
                                ->where('e.event_id', '=', $event->id)
                                ->groupBy('vcc.name')
                                ->get()->toArray();
        //dd($categories_in_event);

        $order = DB::table('classifications_stage AS cs')
        ->selectRaw('cs.participant_id')
        ->whereIn('cs.stage_id', array_column($event->stages->toArray(), 'id'))
        ->groupBy('cs.participant_id')
        ->orderByRaw('SUM(cs.stage_points)')
        ->get()->toArray();

        $object = new stdClass();

        for($i = 0; $i < count($categories_in_event); $i++)
        {
            $object->classifications[$i] = DB::table('classifications_stage AS cs')
                                            ->selectRaw('cs.participant_id, SUM(cs.stage_points) AS points, e.run_order, fd.country AS first_driver_country, fd.name AS first_driver_name, sd.name AS second_driver_name, sd.country AS second_driver_country, v.model AS vehicle_model, vc.name AS vehicle_class, vcc.name AS vehicle_category')
                                            ->join('participants AS p', 'p.id', '=', 'cs.participant_id')
                                            ->join('enrollments AS e', 'e.id', '=', 'p.enrollment_id')
                                            ->join('driver_history AS fd', 'fd.id', '=', 'p.first_driver_id')
                                            ->join('driver_history AS sd', 'sd.id', '=', 'p.second_driver_id')
                                            ->join('vehicle_history AS v', 'v.id', '=', 'p.vehicle_id')
                                            ->join('vehicle_classes AS vc', 'vc.id', '=', 'v.class_id')
                                            ->join('vehicle_categories AS vcc', 'vcc.id', '=', 'vc.category_id')
                                            ->whereIn('cs.stage_id', array_column($event->stages->toArray(), 'id'))
                                            ->where('vcc.name', '=', $categories_in_event[$i]->name)
                                            ->groupBy('cs.participant_id')
                                            ->orderBy('points')
                                            ->get()->toArray();

            for($j = 0; $j < count($object->classifications[$i]); $j++)
            {
                //dd(array_column($order, 'participant_id'));
                //dd($object->classifications[$i][$j]->participant_id);
                //dd(array_search(1, array_column($order, 'participant_id')));
                $object->classifications[$i][$j]->general_pos = array_search($object->classifications[$i][$j]->participant_id, array_column($order, 'participant_id')) + 1;
                //dd($classifications[$i]->participant_id);
                $stage_classifications = DB::table('classifications_stage AS cs')
                                        ->select('cs.id', 'cs.stage_points', 's.name')
                                        ->join('stages AS s', 's.id', '=', 'cs.stage_id')
                                        ->whereIn('cs.stage_id', array_column($event->stages->toArray(), 'id'))
                                        ->where('cs.participant_id', '=', $object->classifications[$i][$j]->participant_id)
                                        ->orderBy('s.date_start')
                                        ->get()->toArray();
                $object->classifications[$i][$j]->stages = $stage_classifications;

            }
        }

        //dd($classifications);
        //dd(count($classifications));

        $object->num_stages = Stage::where('event_id', '=', $event->id)->count();
        //dd($object);

        //dd($classifications);

        return $object;
    }

    public function update(StoreUpdateEventRequest $request, Event $event)
    {

        $validated_data = $request->validated();
        //dd($request->validated());
        if ($request->hasFile('image_file')) {
            $path = Storage::putFile('eventos', $request->file('image_file'));
            $event->image_url = basename($path);
        }

        if ($request->hasFile('course_file')) {
            $path = Storage::putFile('circuitos', $request->file('course_file'));
            $event->course_url = basename($path);
        }

        $event->name = $validated_data['name'];
        //dd($validated_data);
        $dateAux = new DateTime($validated_data['date_start_enrollments'], new DateTimeZone('UTC'));
        $event->date_start_enrollments = $dateAux->setTimezone(new DateTimeZone('Europe/Lisbon'));

        $dateAux = new DateTime($validated_data['date_end_enrollments'], new DateTimeZone('UTC'));
        $event->date_end_enrollments = $dateAux->setTimezone(new DateTimeZone('Europe/Lisbon'));

        $dateAux = new DateTime($validated_data['date_start_event'], new DateTimeZone('UTC'));
        $event->date_start_event = $dateAux->setTimezone(new DateTimeZone('Europe/Lisbon'));

        $dateAux = new DateTime($validated_data['date_end_event'], new DateTimeZone('UTC'));
        $event->date_end_event = $dateAux->setTimezone(new DateTimeZone('Europe/Lisbon'));

        //dd($event->date_start_event);
        $event->year = DateTimeImmutable::createFromFormat(DateTimeInterface::ISO8601, $validated_data['date_start_event']);
        //dd($event->year);
        $event->year = $event->year->format('Y');
        //dd($event->year);

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

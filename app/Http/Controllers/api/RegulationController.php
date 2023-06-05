<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegulationFileRequest;
use App\Http\Resources\RegulationResource;
use App\Models\Event;
use App\Models\Regulation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RegulationController extends Controller
{
    public function show(Event $event)
    {
        //return Regulation::where('event_id', '=', $event->id)->get();

        return response()->json(DB::table('regulations AS r')
                                ->select('r.id', 'e.name', 'r.name', 'r.file_url')
                                ->join('events AS e', 'e.id', 'r.event_id')
                                ->where('event_id', '=', $event->id)
                                ->get());
    }

    public function store(StoreRegulationFileRequest $request, Event $event)
    {
        $regulation = $request->validated();
        $regulation['event_id'] = $event->id;

        if ($request->hasFile('regulation_file')) {
            $path = Storage::putFile('public/regulamentos', $request->file('regulation_file'));
            $regulation['file_url'] = basename($path);
        }

        $newRegulation = Regulation::create($regulation);

        return $newRegulation;
    }

    public function destroy(Regulation $regulation)
    {
        $regulation->delete();
        return new RegulationResource($regulation);
    }
}

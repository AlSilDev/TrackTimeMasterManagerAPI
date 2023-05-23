<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegulationFileRequest;
use App\Http\Resources\RegulationResource;
use App\Models\Event;
use App\Models\Regulation;
use Illuminate\Support\Facades\Storage;

class RegulationController extends Controller
{
     /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return Regulation::where('event_id', '=', $event->id)->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRegulationFileRequest $request, Event $event)
    {
        //dd($request->validated());

        $regulation = $request->validated();
        $regulation['event_id'] = $event->id;

        if ($request->hasFile('regulation_file')) {
            $path = Storage::putFile('public/regulamentos', $request->file('regulation_file'));
            $regulation['file_url'] = basename($path);
        }

        $newRegulation = Regulation::create($regulation);

        return $newRegulation;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Regulation $regulation)
    {
        $regulation->delete();
        return new RegulationResource($regulation);
    }
}

<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePressFileRequest;
use App\Http\Resources\PressResource;
use App\Models\Event;
use App\Models\Press;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PressController extends Controller
{
    public function show(Event $event)
    {
        //return Press::where('event_id', '=', $event->id)->get();

        return response()->json(DB::table('press AS p')
                                ->select('p.id', 'p.name', 'p.name', 'p.file_url', 'p.created_at', 'p.updated_at')
                                ->join('events AS e', 'e.id', 'p.event_id')
                                ->where('event_id', '=', $event->id)
                                ->get());
    }

    public function store(StorePressFileRequest $request, Event $event)
    {
        $press = $request->validated();
        if ($request->hasFile('press_file')) {
            $path = Storage::putFile('imprensa', $request->file('press_file'));
            $press['file_url'] = basename($path);
        }
        $press['event_id'] = $event->id;

        $newPress = Press::create($press);

        return $newPress;
    }

    public function destroy(Press $press)
    {
        $press->delete();
        return new PressResource($press);
    }
}

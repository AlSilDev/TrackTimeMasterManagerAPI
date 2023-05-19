<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePressFileRequest;
use App\Http\Resources\PressResource;
use App\Models\Event;
use App\Models\Press;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PressController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return Press::where('event_id', '=', $event->id)->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePressFileRequest $request, Event $event)
    {
        //dd($request->validated());

        $press = $request->validated();
        //dd($request->hasFile('image_file'));
        if ($request->hasFile('press_file')) {
            $path = Storage::putFile('public/imprensa', $request->file('press_file'));
            $press['file_url'] = basename($path);
        }
        $press['event_id'] = $event->id;

        $newPress = Press::create($press);

        return $newPress;
    }
}

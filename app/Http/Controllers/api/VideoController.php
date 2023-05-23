<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVideoLinkRequest;
use App\Http\Resources\VideoResource;
use App\Models\Event;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return Video::where('event_id', '=', $event->id)->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVideoLinkRequest $request, Event $event)
    {
        //dd($request->validated());

        $video = $request->validated();
        $video['event_id'] = $event->id;

        $newVideo = Video::create($video);

        return $newVideo;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $video)
    {
        $video->delete();
        return new VideoResource($video);
    }
}

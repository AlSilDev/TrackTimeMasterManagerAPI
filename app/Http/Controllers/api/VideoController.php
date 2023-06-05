<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVideoLinkRequest;
use App\Http\Resources\VideoResource;
use App\Models\Event;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VideoController extends Controller
{
    public function show(Event $event)
    {
        //return Video::where('event_id', '=', $event->id)->get();

        return response()->json(DB::table('videos AS v')
                                ->select('v.id', 'e.name', 'v.video_url')
                                ->join('events AS e', 'e.id', 'v.event_id')
                                ->where('event_id', '=', $event->id)
                                ->get());
    }

    public function store(StoreVideoLinkRequest $request, Event $event)
    {
        $video = $request->validated();
        $video['event_id'] = $event->id;

        $newVideo = Video::create($video);

        return $newVideo;
    }

    public function destroy(Video $video)
    {
        $video->delete();
        return new VideoResource($video);
    }
}

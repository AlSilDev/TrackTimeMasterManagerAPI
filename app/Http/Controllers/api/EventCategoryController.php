<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateEventCategoryRequest;
use App\Http\Requests\StoreUpdateEventRequest;
use App\Http\Resources\EventCategoryResource;
use App\Models\EventCategory;
use Illuminate\Http\Request;

class EventCategoryController extends Controller
{
    public function index(Request $request){
        return response()->json(EventCategory::all());
    }

    public function store(StoreUpdateEventCategoryRequest $request)
    {
        $newEventCategory = EventCategory::create($request->validated());
        return new EventCategoryResource($newEventCategory);
    }

    public function show(EventCategory $eventCategory)
    {
        return new EventCategoryResource($eventCategory);
    }

    public function update(StoreUpdateEventCategoryRequest $request, EventCategory $eventCategory)
    {
        $eventCategory->update($request->validated());
        return new EventCategoryResource(($eventCategory));
    }

    public function destroy(EventCategory $eventCategory)
    {
        $eventCategory->delete();
        return new EventCategoryResource($eventCategory);
    }

    public function indexOnlyTrashed(Request $request)
    {
        return response()->json(EventCategory::onlyTrashed()->get());
    }

    public function indexWithTrashed(Request $request)
    {
        return response()->json(EventCategory::withTrashed()->get());
    }

}

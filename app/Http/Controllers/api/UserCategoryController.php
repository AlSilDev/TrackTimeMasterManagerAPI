<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateUserCategoryRequest;
use App\Http\Resources\UserCategoryResource;
use App\Models\UserCategory;
use Illuminate\Http\Request;

class UserCategoryController extends Controller
{
    public function index(Request $request){
        return response()->json(UserCategory::all());
    }

    public function store(StoreUpdateUserCategoryRequest $request)
    {
        $newUserCategory = UserCategory::create($request->validated());
        return new UserCategoryResource($newUserCategory);
    }

    public function show(UserCategory $userCategory)
    {
        return new UserCategoryResource($userCategory);
    }

    public function update(StoreUpdateUserCategoryRequest $request, UserCategory $userCategory)
    {
        $userCategory->update($request->validated());
        return new UserCategoryResource($userCategory);
    }

    public function destroy(UserCategory $userCategory)
    {
        $userCategory->delete();
        return new UserCategoryResource($userCategory);
    }

    public function indexOnlyTrashed(Request $request)
    {
        return response()->json(UserCategory::onlyTrashed()->get());
    }

    public function indexWithTrashed(Request $request)
    {
        return response()->json(UserCategory::withTrashed()->get());
    }

    public function restore(int $userCategoryId)
    {
        return response()->json(UserCategory::withTrashed()->find($userCategoryId)->restore());
    }

    public function showNameById(int $userCategoryId)
    {
        return response()->json(UserCategory::where('id', $userCategoryId)->first()['name']);
    }
}

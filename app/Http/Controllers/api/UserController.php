<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUpdateUserRequest;
use App\Http\Requests\UpdateUserPasswordRequest;


class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->attribute && $request->search){
            return response()->json(User::whereRaw("UPPER({$request->attribute}) LIKE CONCAT('%', UPPER('{$request->search}'), '%')")->orderBy($request->column, $request->order)->paginate(15));
        }
        return response()->json(User::orderBy($request->column, $request->order)->paginate(15));
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function show_me(Request $request)
    {
        return new UserResource($request->user());
    }

    public function update(StoreUpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());
        return new UserResource($user);
    }

    public function update_password(UpdateUserPasswordRequest $request, User $user)
    {
        $user->password = bcrypt($request->validated()['password']);
        $user->save();
        return new UserResource($user);
    }

    public function store(StoreUpdateUserRequest $request)
    {
        $newUser = User::create($request->validated());
        return new UserResource($newUser);
    }
}

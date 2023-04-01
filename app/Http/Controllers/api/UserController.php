<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUpdateUserRequest;
use App\Http\Requests\UpdateUserPasswordRequest;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::all());
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
        $validated_data = $request->validated();

        $user->name = $validated_data['name'];
        $user->type = $validated_data['type'];
        $user->email = $validated_data['email'];
        if ($request->hasFile('photo_file')) {
            $path = Storage::putFile('public/fotos', $request->file('photo_file'));
            $user->photo_url = basename($path);
        }

        $user->save();
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

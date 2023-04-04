<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUpdateUserRequest;
use App\Http\Requests\UpdateUserPasswordRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;


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

    public function store(RegisterRequest $request)
    {
        $validated_data = $request->validated();

        //return $validated_data;

        $newUser = new User;
        $newUser->name = $validated_data['name'];
        $newUser->type = $validated_data['type'];
        $newUser->email = $validated_data['email'];
        //$newUser->password = Hash::make($validated_data['password']);
        $newUser->password = bcrypt($validated_data['password']);

        if ($request->hasFile('photo_file')) {
            $path = Storage::putFile('public/fotos', $request->file('photo_file'));
            $newUser->photo_url = basename($path);
        }
        
        $newUser->save();
        return new UserResource($newUser);
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Models\User;

class ProfileController extends Controller
{
    public function show()
    {
        $user = User::first();

        if (!$user) {
            return response()->json(['message' => 'لا يوجد ملف شخصي'], 404);
        }

        return new ProfileResource($user);
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = User::first();

        if (!$user) {
            return response()->json(['message' => 'لا يوجد ملف شخصي'], 404);
        }

        $user->update($request->validated());

        return new ProfileResource($user);
    }
}

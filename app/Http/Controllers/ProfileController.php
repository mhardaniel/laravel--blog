<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProfileResource;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(string $username)
    {
        $profile = User::whereUsername($username)
            ->firstOrFail();

        return new ProfileResource($profile);
    }

    public function follow(Request $request, string $username)
    {
        $profile = User::whereUsername($username)
            ->firstOrFail();

        $profile->followers()
            ->syncWithoutDetaching($request->user());

        return new ProfileResource($profile);
    }

    public function unfollow(Request $request, string $username)
    {
        $profile = User::whereUsername($username)
            ->firstOrFail();

        $profile->followers()->detach($request->user());

        return new ProfileResource($profile);
    }
}

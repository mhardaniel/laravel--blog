<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class UserResource extends BaseUserResource
{
    public static $wrap = 'user';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return array_merge(parent::toArray($request), [
            'email' => $this->email,
            'token' => $this->token,
        ]);
    }
}

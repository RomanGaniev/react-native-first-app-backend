<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\User\SearchUsersResource;
use Illuminate\Http\Request;

use App\Models\User;

class UserController extends Controller
{
    public function show(Request $request)
    {
        $userId = auth()->id();
        $users = User::where('id', '!=', $userId)->get();

        return SearchUsersResource::collection($users);
    }
}

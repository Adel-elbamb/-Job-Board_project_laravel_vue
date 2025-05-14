<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Hash;

class ProfileManagementController extends ApiController
{
    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();

        $user->update([
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return $this->successResponse($user, 'Profile updated successfully');

    }
}
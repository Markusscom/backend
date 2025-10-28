<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AdminController extends Controller
{
    public function login(Request $request) {
        try {
            $validated = $request->validate([
                "email" => "required|email",
                "password" => "required"
            ]);
        } catch (ValidationException $e) {
            return json_encode([
                "errors" => $e->errors()
            ]);
        }

        if ($validated["email"] === env("ADMIN_EMAIL") && $validated["password"] === env("ADMIN_PASSWORD")) {
            $user = User::where("email", $validated["email"])->first();

            if (!$user) {
                return json_encode([
                    "errors" => "Admin user not found."
                ]);
            }

            if (!$user->tokens()->exists()) {
                $newtoken = $user->createToken("ADMIN_TOKEN");
                $token = $newtoken->plainTextToken;
            } else {
                $user->tokens()->delete();
                $newtoken = $user->createToken("ADMIN_TOKEN");
                $token = $newtoken->plainTextToken;
            }

            return json_encode([
                "token" => $token
            ]);
        }

        return json_encode([
            "errors" => "Invalid Credentials!"
        ]);
    }
}

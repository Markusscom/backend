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

    // CREATE a new user
    public function create(Request $request) {
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|email|unique:users,email",
            "password" => "required|min:6"
        ]);

        $user = User::create([
            "name" => $validated["name"],
            "email" => $validated["email"],
            "password" => bcrypt($validated["password"])
        ]);

        return json_encode([
            "message" => "User created successfully.",
            "user" => $user
        ]);
    }

    // READ all users
    public function index() {
        $users = User::all();
        return json_encode($users);
    }

    // READ one user by ID
    public function show($id) {
        $user = User::find($id);

        if (!$user) {
            return json_encode([
                "errors" => "User not found."
            ]);
        }

        return json_encode($user);
    }

    // UPDATE user
    public function update(Request $request, $id) {
        $user = User::find($id);

        if (!$user) {
            return json_encode([
                "errors" => "User not found."
            ]);
        }

        $validated = $request->validate([
            "name" => "nullable|string|max:255",
            "email" => "nullable|email|unique:users,email," . $id,
            "password" => "nullable|min:6"
        ]);

        if (isset($validated["name"])) $user->name = $validated["name"];
        if (isset($validated["email"])) $user->email = $validated["email"];
        if (isset($validated["password"])) $user->password = bcrypt($validated["password"]);
        $user->save();

        return json_encode([
            "message" => "User updated successfully.",
            "user" => $user
        ]);
    }

    // DELETE user
    public function delete($id) {
        $user = User::find($id);

        if (!$user) {
            return json_encode([
                "errors" => "User not found."
            ]);
        }

        $user->delete();

        return json_encode([
            "message" => "User deleted successfully."
        ]);
    }
}

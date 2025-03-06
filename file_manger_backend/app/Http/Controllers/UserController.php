<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResoucre;
use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function updateInfo(Request $request)
    {
        $req = Validator::make($request->all(), [
            "name"=> "required|string|max:255|min:3",
            "email"=> "required|string|email|max:255",
        ]);

        if ($req->fails()) {
            return response()->json([
                'errors' => $req->errors()
            ], 422);
        }

        $user = Auth::user();

        if($user->email != $request->email) {
            // check new email is unique
            $check = User::where("email", $request->email)->first();

            if($check) {
                return response()->json(["message" => "This email has already been taken."], 404);
            }
        }

        $file = File::where("name", $user->email)
        ->where('is_folder', true)
        ->where('created_by', $user->id)
        ->first();

        if($file) {
            $file->name = $request->email;
            $file->save();
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return response()->json([
            'user' => new UserResoucre($user)
        ]);
    }

    public function updatePassword(Request $request)
    {
        $req = Validator::make($request->all(), [
            'current_password' => 'required|string|min:8',
            'password'=> ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($req->fails()) {
            return response()->json([
                'errors' => $req->errors()
            ], 422);
        }

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->noContent();
    }

    public function deleteUser(Request $request)
    {
        $req = Validator::make($request->all(), [
            'password'=> ['required', 'string', 'min:8'],
        ]);

        if ($req->fails()) {
            return response()->json([
                'errors' => $req->errors()
            ], 400);
        }

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
                ], 404);
        }

        $file = File::where('name', $user->email)
        ->where('is_folder', true)
        ->where('created_by', $user->id)
        ->first();

        if($file) {
            $file->delete();

            $files = File::onlyTrashed()
            ->where('created_by', $user->id)
            ->get();

            foreach($files as $file) {
                if(!$file->is_folder){
                    Storage::disk('public')->delete($file->path);
                }
                $file->forceDelete();
            }
        }

        $user->delete();

        return response()->noContent(200);
    }       
}

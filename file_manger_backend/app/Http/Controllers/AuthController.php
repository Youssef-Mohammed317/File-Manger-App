<?php

namespace App\Http\Controllers;


use App\Http\Resources\UserResoucre;
use App\Models\File;
use App\Models\User;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $req = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'email'=> ['required', 'string', 'email', 'email','max:255', 'unique:'.User::class],
            'password'=> ['required', 'string', 'min:8', 'confirmed'],
            ]);
        if($req->fails()){
            return response()->json([
                'errors' => $req->errors()
            ], 400);
        }

        $verifyCode = rand(100000, 999999);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->string('password')),
            'verify_code' => $verifyCode
        ]);

        $file = new File();
        $file->name = $user->email;
        $file->is_folder = true;
        $file->created_by = $user->id;
        $file->updated_by = $user->id;
        $file->makeRoot();
        $file->save();

        
        Mail::raw('Your verification code is '.$verifyCode, function ($message) use ($user) {
            $message->subject('Verification Email');
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $message->to($user->email, $user->name);
        });

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => new UserResoucre($user),
            'token'=> $token
        ], 201);
    }
    public function login(Request $request)
    {
        $req = Validator::make($request->all(), [
            'email'=> ['required', 'string', 'email', 'max:255', 'exists:users,email'],
            'password'=> ['required', 'string', 'min:8'],
            ]);

        if($req->fails()){
            return response()->json([
                'errors' => $req->errors()
            ], 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 404);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => new UserResoucre($user),
            'token'=> $token
        ], 200);
    }

    public function resendVerifyEmail(Request $request)
    {
        $req = Validator::make($request->all(), [
            'email'=> ['required', 'string', 'email', 'max:255', 'exists:users,email'],
            ]);

        if($req->fails()) {
            return response()->json([
                'errors'=> $req->errors(),
            ], 400);
        }
        
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
            'message'=> 'Invalid credentials'
            ], 404);
        }
        
        $verifyCode = rand(100000, 999999);

        $user->update([
            'verify_code' => $verifyCode,
        ]);

        $user->save();


        Mail::raw('Your new verification code is '.$verifyCode, function ($message) use ($user) {
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $message->to($user->email, $user->name)->subject('Verification Email');
        });

        return response()->noContent();
    }

    public function verifyEmail(Request $request)
    {
        $req = Validator::make($request->all(), [
            'email'=> ['required', 'string', 'email','max:255', 'exists:users,email'],
            'verify_code'=> ['required', 'numeric', 'min:100000', 'max:999999'],
            ]);

        if($req->fails()) {
            return response()->json([
                'errors'=> $req->errors(),
            ], 400);
        }
        $user = User::where('email', $request->email)
        ->where('verify_code', $request->verify_code)    
        ->first();

        if (!$user) {
            return response()->json([
                'message'=> 'Invalid credentials'
            ], 404);
        }

        $user->update([
            'verify_code' => null
        ]);

        $user->save();

        return response()->noContent();
    }

    public function forgotPassword(Request $request)
    {
        $req = Validator::make($request->all(), [
            'email'=> ['required', 'string', 'email','max:255', 'exists:users,email'],
            ]);

        if($req->fails()) {
            return response()->json([
                'errors'=> $req->errors(),
            ], 400);
        }

        $user = User::where('email', $request->email)->first();

        $verifyCode = rand(100000, 999999);

        $user->update([
            'verify_code' => $verifyCode,
        ]);

        $user->save();

        Mail::raw('Your verification code is '.$verifyCode, function ($message) use ($user) {
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $message->to($user->email, $user->name)->subject('Verification Email');
        });

        return response()->noContent();
    }
    public function resetPassword(Request $request)
    {
        $req = Validator::make($request->all(), [
            'email'=> ['required', 'string', 'email','max:255', 'exists:users,email'],
            'verify_code'=> ['required', 'numeric', 'min:100000', 'max:999999'],
            'password'=> ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if($req->fails()) {
            return response()->json([
                'errors'=> $req->errors(),
            ], 400);
        }

        $user = User::where('email', $request->email)
        ->where('verify_code', $request->verify_code)
        ->first();

        $user->update([
            'password' => Hash::make($request->password),
            'verify_code' => null
        ]);
        $user->save();

        Mail::raw('Your password has been reset Successfully', function ($message) use ($user) {
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->to($user->email, $user->name)
            ->subject('Password Reset');
        });

        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileResource;
use App\Models\File;
use App\Models\FileShare;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FileShareController extends Controller
{
    public function shareFiles(Request $request)
    {
        $req = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email','max:255', 'exists:users,email'],
        ]);

        if($req->fails()) {
            return response()->json([
                'errors'=> $req->errors(),
            ], 400);
        }

        if($request->email == Auth::user()->email) {
            return response()->json([
                'message' => 'You can not share files with yourself'
            ], 400);
        }

        $user = User::where('email', $request->email)->first();

        if($user) {
            $fileShared = [];
            foreach($request->ids as $id){
                $file = File::where('id', $id)
                // ->where('created_by', Auth::id())
                ->first();

                if($file) {
                    $checkFileShareBefore = FileShare::where('user_id', $user->id)
                    // ->where('created_by', Auth::id())
                    ->where('file_id', $id)->first();

                    if(!$checkFileShareBefore){
                        $fileShare = new FileShare();
                        $fileShare->user_id = $user->id;
                        $fileShare->file_id = $file->id;
                        $fileShare->created_by = $file->created_by;
                        $fileShare->save();
                        $fileShared[] = $fileShare;
                    }
                }
            }
            return response()->json([
                'file_shared' => $fileShared
            ], 200);
        } 
        return response()->noContent();
    }

    public function getSharedFilesbyMe(Request $request)
    {
        $req = Validator::make($request->all(), [
            'parent_id' => 'nullable|integer|exists:files,id',
        ]);

        if ($req->fails()) {
            return response()->json([
                'error'=> $req->errors()->first(),
            ],400);
        }
        
        $files = [];
        if(!$request->parent_id) {   
            $filesIds = FileShare::where('created_by', Auth::id())->pluck('file_id');
            
            $files = File::whereIn('id', $filesIds)
            ->orderBy('is_folder', 'desc')
            ->get();
        } else {
            $files = File::where('parent_id', $request->parent_id)
            ->where('created_by', Auth::id())
            ->orderBy('is_folder', 'desc')
            ->get();
        }
        return response()->json([
            'files' => FileResource::collection($files),
        ], 200);
    }

    public function getSharedFilesWithMe(Request $request)  
    {
        $req = Validator::make($request->all(), [
            'parent_id' => 'nullable|integer|exists:files,id',
            'created_by' => 'nullable|integer|exists:users,id',
        ]);

        if ($req->fails()) {
            return response()->json([
                'error'=> $req->errors()->first(),
            ],400);
        }

        $files = [];
        if(!$request->parent_id || !$request->created_by) {
            $filesIds = FileShare::where('user_id', Auth::id() )->pluck('file_id');
            $files = File::whereIn('id', $filesIds)
            ->orderBy('is_folder', 'desc')
            ->get();

        } else {
            $files = File::where('parent_id', $request->parent_id)
            ->where('created_by', $request->created_by)
            ->orderBy('is_folder', 'desc')
            ->get();            
        }
        return response()->json([
            'files'=> FileResource::collection($files),
        ]);
    }

    public function deleteShareByMe(Request $request){
        foreach($request->ids as $id){
            FileShare::where('file_id', $id)->delete();
        }
    }
    public function deleteShareWithMe(Request $request){
        foreach($request->ids as $id){
            $file = FileShare::where('file_id', $id)
            ->where('user_id', Auth::id())->delete();
        }
    }
}

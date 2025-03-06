<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileResource;
use App\Models\File;
use App\Models\FileStarred;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FileStarredController extends Controller
{

    public function addToMyFavorites(Request $request)
    {
            foreach($request->ids as $id){
                $file = File::where('id', $id)->first();

                if($file) {
                    $checkFileAddToFavoritesBefore = FileStarred::where('file_id', $id)
                    ->where('user_id', Auth::id())
                    ->where('created_by', $file->created_by)
                    ->first();

                    if(!$checkFileAddToFavoritesBefore){
                        $fileStarred = new FileStarred();
                        $fileStarred->user_id = Auth::id();
                        $fileStarred->file_id = $file->id;
                        $fileStarred->created_by = $file->created_by;
                        $fileStarred->save();
                    }
                }
            }
            return response()->json([
                'message' => 'File Added To Favorites Successfully',
            ], 200);
    }
    public function getMyFavoriteFiles(Request $request)
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
            $filesIds = FileStarred::where('user_id', Auth::id() )->pluck('file_id');
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

    public function deleteMyFavorites(Request $request)
    {
        foreach($request->ids as $id){
            FileStarred::where('file_id', $id)->delete();
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use ZipArchive;

class FileController extends Controller
{
    public function createFolder(Request $request)
    {
        $req = Validator::make($request->all(), [
            'name'=> ['required', 'string', 'max:255'],
            'parent_id'=> ['required', 'integer', 'exists:files,id'],
        ]);

        if ($req->fails()) {
            return response()->json([
                'errors' => $req->errors()
            ], 400);
        }

        $folder = $this->checkName($request->name, $request->parent_id, true);

        if ($folder) {
            return response()->json([
                'message' => 'This Folder Name Already Exists'
            ], 400);
        }
        
        $folder = $this->newFolder($request->name, $request->parent_id);

        $parent = File::where('id', $request->parent_id)->first();

        $parent->appendNode( $folder );

        return response()->noContent();
    }

    public function getFiles(Request $request)
    {
        $req = Validator::make($request->all(), [
            'parent_id'=> ['required', 'integer', 'exists:files,id'],
        ]);

        if ($req->fails()) {
            return response()->json([
                'errors' => $req->errors()
            ], 400);
        }
        $files = File::where('parent_id', $request->parent_id)
        ->where('deleted_at', null)
        ->orderBy('is_folder', 'desc')
        ->orderBy('created_at', 'asc')
        ->get();

        return response()->json([
            'files' => FileResource::collection($files),
        ], 200);
    }

    public function uploadFiles(Request $request)
    {
        $req = Validator::make($request->all(), [
            'parent_id'=> ['required', 'integer', 'exists:files,id'],
            'relative_paths'=> ['nullable'],
        ]);

        if ($req->fails()) {
            return response()->json([
                'errors'=> $req->errors()
            ], 400);
        }

        $files = $request->file('files');

        $parent = File::where('id', $request->parent_id)->first();

        if(!$request->relative_paths[0]){

            foreach ($files as $file) {
                
                $check = $this->checkName($file->getClientOriginalName(), $request->parent_id, false);
              
                if ($check) {
                    return response()->json([
                        'message' => 'This File ' . $file->getClientOriginalName() . ' Name Already Exists',
                    ], 404);
                }   
            }
            
            foreach ($files as $file) {
                $file = $this->newFile($file, $request->parent_id);
                $parent->appendNode( $file );
            }

            return response()->noContent();
        } else {

            
            $filePaths = $request->relative_paths;
            
            $check = $this->checkName(explode('/', $filePaths[0])[0], $request->parent_id, true);
            
            if ($check) {
                return response()->json([
                'message' => 'This Folder ' . explode('/', $filePaths[0])[0] . ' Name Already Exists',
            ], 404);
        }
        
        // create folder
        foreach ($filePaths as $key => $path) {
            $root = $parent;
            $exp = explode('/', $path);
            foreach ($exp as $key2 => $name) {
                if($key2 == count($exp) - 1) {
                    // this is the file
                    $file = $files[$key];
                    $file = $this->newFile($file, $root->id);
                    $root->appendNode( $file );
                    continue;
                }
                // this is folder
                $check = File::where('name', $name)
                ->where('parent_id', $root->id)
                ->where('is_folder', true)
                ->where('deleted_at', null)
                ->where('created_by', Auth::id())
                ->first();
                if ($check) {
                    $root = $check;
                    continue;
                }
                $folder = $this->newFolder(
                    $name,
                    $root->id,
                );
                $root->appendNode( $folder );
                $root = $folder;
            }
            }
        }
    }

    private function newFolder($name, $parent_id)
    {
        $folder = File::create([
            'name'=> $name,
            'parent_id'=> $parent_id,
            'is_folder'=> true,
            'created_by'=> Auth::id(),
            'updated_by'=> Auth::id()
        ]);

        return $folder;
    }

    private function newFile($file, $parent_id)
    {
        $storagePath = Storage::disk('public')->putFile('files/' . Auth::id(), $file);
        $file = File::create([
            'name'=> $file->getClientOriginalName(),
            'path'=> $storagePath,
            'parent_id'=> $parent_id,
            'is_folder'=> false,
            'created_by'=> Auth::id(),
            'updated_by'=> Auth::id(),
            'size'=> $file->getSize(),
            'mime' => $file->getClientMimeType(),
        ]);
        return $file;
    }

    private function checkName($name, $parent_id, $is_folder = true)
    {
        $folder = File::where('name', $name)
        ->where('parent_id', $parent_id)
        ->where('is_folder', $is_folder)
        ->where('created_by', Auth::id())
        ->where('deleted_at', null)
        ->first();

        if ($folder) {
            return true;
        }
        return false;
    }

    public function getAncestors(Request $request){
        $req = Validator::make($request->all(), [
            'parent_id' => 'nullable|integer|exists:files,id',
        ]);

        if ($req->fails()) {
            return response()->json([
                'error'=> $req->errors()->first(),
            ],400);
        }

        $folder = File::where('parent_id', $request->parent_id)
        // ->where('created_by', Auth::id())
        ->where('deleted_at', null)
        // ->where('is_folder', true)
        ->first();

        if (!$folder) {
            $rootFolder = File::where('id', $request->parent_id)
            ->where('parent_id', null)
            ->where('deleted_at', null)
            ->where('created_by', Auth::id())
            ->where('is_folder', true)
            ->where('name', Auth::user()->email)
            ->first();

            if ($rootFolder) {
                return response()->json([
                    'ancestors' => FileResource::collection([$rootFolder]),
                ],200);
            }

            $folder = File::where('id', $request->parent_id)
            ->where('deleted_at', null)
            ->where('is_folder', true)
            ->first();
            if (!$folder) {
                return response()->json([
                    'error'=> 'Folder Not Found',
                ]);
            } else {
                $ancestors = $folder->ancestors()->get();

                return response()->json([
                    'ancestors' => FileResource::collection([...$ancestors, $folder]),
                ],200);
            }
        }

        $ancestors = $folder->ancestors()->get();

        return response()->json([
            'ancestors' => FileResource::collection([...$ancestors, ]),
        ],200);

    }

    public function downloadFiles(Request $request)
    {
        
        $zipPath = storage_path('app/public/archive.zip');
        
        $zip = new ZipArchive();
        
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) != true) {
            return response()->json(['error' => 'Failed to create ZIP archive'], 500);
        }

        $files = File::whereIn('id', $request->ids)->get();

        $this->addFilesToZip($files, $zip);

        $zip->close();
        return response()->download($zipPath, 'archive.zip', [
            'Content-Type' => 'application/zip',
            'Content-Disposition' => 'attachment; filename="archive.zip"',
        ])->deleteFileAfterSend(true); // Delete the temporary ZIP file after sending
    }
    private function addFilesToZip($files, $zip, $ancestors = '') {
        foreach ($files as $file) {
            if($file->is_folder) {
                $this->addFilesToZip($file->children, $zip, $ancestors . $file->name . '/');
            } else {
                $zip->addFile(Storage::disk('public')->path($file->path), $ancestors . $file->name);
            }
        }
    }

    public function destroy(Request $request)
    {
        foreach($request->ids as $id){
            $file = File::where('id', $id)
            ->where('created_by', Auth::id())
            ->where('deleted_at', null)
            ->first();

            if (!$file) {
                return response()->json([
                    'message' => 'File Not Found'
                ], 400);
            }
        }

        foreach($request->ids as $id){
            $file = File::where('id', $id)
            ->where('created_by', Auth::id())
            ->where('deleted_at', null)
            ->first();

            $file->update([
                'is_trashed' => true
            ]);

            $file->save();

            $file->delete();
        }

        return response()->noContent();
    }

    public function getDeletedFiles(Request $request)
    {
        $files = File::onlyTrashed()
        ->where('created_by', Auth::id())
        ->where('is_trashed', true)
        ->orderBy('is_folder', 'desc')
        ->orderBy('created_at', 'asc')
        ->get();

        return response()->json([
            'files' => FileResource::collection($files),
        ], 200);
    }

    public function deleteFiles(Request $request)
    {
        foreach($request->ids as $id){
            $file = File::onlyTrashed()
            ->where('id', $id)
            ->where('created_by', Auth::id())
            ->first();
            if(!$file->is_folder){
                Storage::disk('public')->delete($file->path);
            }
            $file->forceDelete();
        }        
    }
    public function restoreFiles(Request $request)
    {
        foreach($request->ids as $id){
            $file = File::onlyTrashed()
            ->where('id', $id)
            ->where('created_by', Auth::id())
            ->where('is_trashed', true)
            ->first();
            $file->is_trashed = false;
            $file->save();
            $file->restore();
        }        
    }

    public function search(){
        $files = File::where('created_by', Auth::id())
        ->where('deleted_at', null)
        ->where('name', '!=', Auth::user()->email)
        ->orderBy('is_folder', 'desc')
        ->orderBy('created_at', 'asc')
        ->get();
        return response()->json([
            'files' => FileResource::collection($files),
        ], 200);
    }
}

<?php

namespace App\Http\Resources;

use App\Models\FileStarred;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=> $this->id,
            'name' => $this->name,
            'is_folder' => $this->is_folder,
            'created_at' =>  $this->created_at->diffForHumans(),
            'updated_at'=> $this->updated_at->diffForHumans(),
            'deleted_at'=> $this->deleted_at ? $this->deleted_at->diffForHumans() : $this->deleted_at,
            'owner' => $this->created_by == Auth::id() ? 'me' : User::where('id', $this->created_by)->first()->name,
            'parent_id' => $this->parent_id,
            'mime' => $this->mime,
            'size' => $this->is_folder ? 0 : $this->calcFileSize($this->size),
            'directory_path' => $this->directory_path,
            'created_by' => $this->created_by,
            'url' => Storage::disk('public')->url($this->path),
            'is_starred' => FileStarred::where('file_id', $this->id)->first() ? 1 : 0,
        ];
    }

    private function calcFileSize($size) {
        if ($size < 1024) return $size . " B";
        else if ($size < 1048576) return (round($size / 1024, 2)) . " KB";
        else if ($size < 1073741824) return (round($size / 1048576, 2)) . " MB";
        else if ($size < 1099511627776) return (round($size / 1073741824, 2)) . " GB";
        else return (round($size / 1099511627776, 2)) . " TB";
    }
}

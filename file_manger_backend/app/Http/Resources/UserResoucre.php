<?php

namespace App\Http\Resources;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResoucre extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $root = File::where('name', $this->email)
        ->where('created_by', $this->id)
        ->where('is_folder', true)
        ->where('parent_id', null)
        ->where('deleted_at', null)
        ->first();
        return [
            "id"=> $this->id,
            "name" => $this->name,
            "email"=> $this->email,
            "verified" => $this->verify_code? false: true,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'=> $this->updated_at->format('Y-m-d H:i:s'),
            'root_id' => $root ? $root->id : null,
        ];
    }
}

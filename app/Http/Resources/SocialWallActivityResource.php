<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SocialWallActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request){
        if($request->activity_type == 1){
            return [
                'liked_by' => $this->userDetail->first_name,
            ];    
        }elseif($request->activity_type == 2){
            return [
            'comment_by' => $this->userDetail->first_name,
            'comment_text' => $this->comment,
            ];
        }
    }
}

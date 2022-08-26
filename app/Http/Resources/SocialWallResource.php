<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Model\SocialWallActivity;

class SocialWallResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    private function postActivityDetail($post_id, $activity_type){

        return $count_likes = SocialWallActivity::where(['post_id'=>$post_id, 'activity_type'=>$activity_type])->count();
    }

    public function toArray($request)
    {
        return [
            'post_id' => $this->id,
            'description' => $this->description,
            'image' => asset('storage/'.$this->image),         
            'likes_count' => $this->postActivityDetail($this->id, 1)??0,         

            'comment_count' => $this->postActivityDetail($this->id, 2)??0,         
        ];
    }
}

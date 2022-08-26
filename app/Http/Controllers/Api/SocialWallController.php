<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\SocialWall;
use App\Model\SocialWallActivity;
use App\Http\Resources\SocialWallResource;
use App\Http\Resources\SocialWallActivityResource;
use Carbon\Carbon;
use App\User;

class SocialWallController extends Controller
{
   
   public function makeNewPost(Request $request){
      $this->validate($request, [
            'image'=>'required',
            'description'=>'required|max:225',
            'post_status'=>'required',
      ]);

      $post = new SocialWall();
      $post->description = $request->description;
      $post->user_id = $request->user()->id;
      $post->post_status = $request->post_status;
      $post->image = $request->file('image')->store('social_wall');
      $post->end_at = Carbon::now()->addDays(30);
      $post->save();

      return response()->json(['error'=>false, 'message'=>'Your Post has been submitted Successfully']);
   }

   public function allPosts(Request $request){

      $all_posts = SocialWall::select('id', 'image', 'description')->where('post_status', 1)->where('end_at', '>', Carbon::now())->get();

      $all_posts = SocialWallResource::collection($all_posts);

      return response()->json(['error'=>false, 'message'=>'All Posts', 'data'=>$all_posts]);
   }


   public function newActivity(Request $request){

      $this->validate($request, [
         'post_id'=>'required|exists:social_walls,id',
         'activity_type'=>'required|numeric',
      ]);

      $activity_by = $request->user()->id;


      if($request->activity_type == 2){ #1 for Like, 2 for Comment
         $this->validate($request, [
            'comment'=>'required|max:200',
         ]);         
         $activity = new SocialWallActivity();
         $activity->comment = $request->comment;
         $activity->activity_type = $request->activity_type;
      }else{
         $activity = SocialWallActivity::firstOrNew(['activity_by'=>$activity_by, 'activity_type'=>$request->activity_type, 'post_id'=>$request->post_id]);   
      }

      $activity->activity_by = $activity_by;
      $activity->post_id = $request->post_id;
      $activity->save();

      return response()->json(['error'=>false, 'message'=>'Activity Saved Successfully']);
   }

   public function fetchPostActivities(Request $request){

      $this->validate($request, [
         'post_id'=>'required|exists:social_walls,id',
         'activity_type'=>'required|numeric',
      ]);
      
      $fetch_activities = SocialWallActivity::select('post_id', 'comment', 'activity_by')->with(['userDetail'=>function($query){
         $query->select('id', 'first_name');
      }])->where(['post_id'=>$request->post_id, 'activity_type'=>$request->activity_type])->get();

      $data = SocialWallActivityResource::collection($fetch_activities);

      return response()->json(['error'=>false, 'message'=>'Activity on Posts', 'data'=>$data]);
   }

   public function myPosts(Request $request){

      $all_posts = SocialWall::select('id', 'image', 'description')->where(['post_status'=>1, 'user_id'=>$request->user()->id])->where('end_at', '>', Carbon::now())->get();

      $all_posts = SocialWallResource::collection($all_posts);

      return response()->json(['error'=>false, 'message'=>'My posts', 'data'=>$all_posts]);
   }
}

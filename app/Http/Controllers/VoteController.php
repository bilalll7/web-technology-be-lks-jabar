<?php

namespace App\Http\Controllers;

use App\Models\Choice;
use App\Models\Poll;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    public function create(Request $request){
        $user = Auth::user();
        if($user->role == 'user'){
            return $this->store($request);
        }else{
            return response()->json([
                'message' => 'Unaothorized'
            ], 401);
        }
    }
    public function store(Request $request){
        $user = $request->user();
        $vote = Vote::where('user_id', $user->id)->where('poll_id', $request->poll_id)->first();

        $poll = Choice::where('poll_id' , $request->poll_id)->where('id', $request->choice_id)->first();
        
        if($poll == null){
            return response()->json([
                'message' => 'The Given Data Was Invalid'
            ],422);
        }
        $cari_polling = Poll::find($request->poll_id);
        if(strtotime($cari_polling['deadline']) < time()) {
            return response()->json([
                'message' => 'voting deadline'
            ],422);
        }
        if ($vote) {
            return response()->json([
                'message' => 'already vote'
            ],422);
        } else {
            $store = Vote::create([
                'choice_id' => $request->choice_id,
                'user_id' => $user->id,
                'poll_id' => $request->poll_id,
                'division_id' => $user->division_id,
            ]);
            if($store){
                return response()->json([
                    'message' => 'votes succesfully',
                    'data' => $store
                ],200);
            }
        }
    }
    public function getVoted(Request $request){
        $user = $request->user();
        $vote = Vote::where('user_id', $user->id)->where('poll_id', $request->poll_id)->first();
        if ($vote) {
            return response()->json([
                'message' => 'already vote'
            ],422);
        } else {
            return response()->json([
                'message' => "No Data Vote"
            ],200);
        }
    }
}

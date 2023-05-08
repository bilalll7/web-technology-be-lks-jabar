<?php

namespace App\Http\Controllers;

use App\Models\Choice;
use Illuminate\Http\Request;
use App\Models\Poll;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Vote;
use App\Models\Division;

class PollController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function create(Request $request){
        $user = Auth::user();
        if($user->role == 'admin'){
            return $this->store($request);
        }else{
            return response()->json([
                'message' => 'Unaothorized'
            ], 401);
        }
    }
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(),[
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'deadline' => 'required|datetime-local',
            'choices' => 'array|min:2'
        ]);
        $choices = $request->choices;

        if ($validator->fails()){
            return response()->json($validator->errors(),422);
        }else {
            $user = $request->user();
            $create = Poll::create([
                'title' => $request->title,
                'description' => $request->description,
                'deadline' => $request->deadline,
                'created_by' => $user->id,
            ]);
            
            if($create){
                foreach($choices as $choice){
                $poll_id = Poll::all()->last()->id;
                Choice::create([
                    'choice' => $choice,
                    'poll_id' => $poll_id
                ]);
            }
                return response()->json($request->all());
            }else{
                return response()->json([
                    'message' => 'The Given Data Was Invalid'
                ],422);
            
            }
        }
    }
    
    public function index()
    {
        $poll = Poll::withCount('choice')->get();

        foreach($poll as $row){
            $choice = Choice::where('poll_id', $row->id)->withCount('votes')->get();
            $index = Choice::where('poll_id', $row->id)->count();

            $pointCount = Vote::where('poll_id', $row->id)->count();
            for($i = 0; $i < $index; $i++){
                $choice[$i]->point = round($choice[$i]->votes_count == 0 ? 0 : $choice[$i]->votes_count / $pointCount * 100);
                $point[] = $choice[$i]->point;
                $choiceData = $choice[$i];
                if(max($point) == $choiceData->point){
                    $idChoice = $choiceData->id;
                    $nameChoice = $choiceData->choice;
                }
            }
            $vote = Auth::user()->role == 'admin' || Vote::where('user_id', Auth::user()->id)->where('poll_id',$row->id)->first();
            if($vote){
                $data[] = [
                   'id' => $row->id,
                   'title' => $row->title,
                   'description' => $row->description,
                   'deadline' => $row->deadline,
                   'created_by' => $row->user->id,
                   'created_at' => $row->created_at,
                   'creator' => $row->user->username, 
                   'result' => [
                       'id' => $idChoice,
                       'choice' => $nameChoice,
                       'point' => max($point),
                   ],
                   'choices' => $choice,
                ];
            }else{
                $data[] = [
                    'id' => $row->id,
                    'title' => $row->title,
                    'description' => $row->description,
                    'deadline' => $row->deadline,
                    'created_by' => $row->user->id,
                    'created_at' => $row->created_at,
                    'creator' => $row->user->username, 
                    'choices' => $choice,
                 ];
            }
        }
        return $data;
    }

    public function show(string $poll_id)
    {
        $poll = Poll::withCount('choice')->find($poll_id);

            $choice = Choice::where('poll_id', $poll_id)->withCount('votes')->get();
            $index = Choice::where('poll_id', $poll_id)->count();

            $pointCount = Vote::where('poll_id', $poll_id)->count();
            for($i = 0; $i < $index; $i++){
                $choice[$i]->point = round($choice[$i]->votes_count == 0 ? 0 : $choice[$i]->votes_count / $pointCount * 100);
                $point[] = $choice[$i]->point;
                $choiceData = $choice[$i];
                if(max($point) == $choiceData->point){
                    $idChoice = $choiceData->id;
                    $nameChoice = $choiceData->choice;
                }
            }
            $vote = Auth::user()->role == 'admin' || Vote::where('user_id', Auth::user()->id)->where('poll_id',$poll_id)->first();
            if($vote){
                $data[] =  [
                   'id' => $poll_id,
                   'title' => $poll->title,
                   'description' => $poll->description,
                   'deadline' => $poll->deadline,
                   'created_by' => $poll->user->id,
                   'created_at' => $poll->created_at,
                   'creator' => $poll->user->username, 
                   'result' => [
                       'id' => $idChoice,
                       'choice' => $nameChoice,
                       'point' => max($point),
                   ],
                   'choices' => $choice,
                ];
            }else{
                $data[] = [
                    'id' => $poll_id,
                    'title' => $poll->title,
                    'description' => $poll->description,
                    'deadline' => $poll->deadline,
                    'created_by' => $poll->user->id,
                    'created_at' => $poll->created_at,
                    'creator' => $poll->user->username, 
                    'choices' => $choice,
                 ];
            }
        return $data;
    }

    public function destroy($id)
    {
        $poll = Poll::find($id);
        $poll->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'poll deleted successfully',
            'poll' => $poll,
        ]);
    }
}

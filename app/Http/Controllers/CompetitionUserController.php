<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Facades\PermissionsFacade;
use App\Http\Resources\CompetitionUserResource;
use App\Models\Competition;
use App\Models\CompetitionUser;
use Illuminate\Http\Request;

class CompetitionUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $users = CompetitionUser::query();

        $data = $users->orderBy('competition_users.created_at', 'desc')->paginate(25);

        return CompetitionUserResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::competitionCanUpdate($competition, $request->user())){
            return response()->json(['error' => 'Access denied to competition.'], 403);
        }

        $input = $request->all();

        $input['competition_id'] = $competition->id;

        $user = new CompetitionUser();

        $valid = $user->validate($input);

        if (!$valid) {
            return response()->json($user->getValidationErrors(), 422);
        }

        $user = CompetitionUser::create($input);

        return new CompetitionUserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CompetitionUser  $competitionUser
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id, $subid)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $user = CompetitionUser::where('competition_id', $id)
        ->where('user_id', $subid)
        ->first();

        if(!$user){
            return response()->json(['error' => 'The user does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        return new CompetitionUserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompetitionUser  $competitionUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $subid)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::competitionCanUpdate($competition, $request->user())){
            return response()->json(['error' => 'Access denied to competition.'], 403);
        }

        $user = CompetitionUser::where('competition_id', $id)->where('user_id', $subid)->first();

        if(!$user){
            return response()->json(['error' => 'The competition/user relationship does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $input = $request->all();

        $data = $input + $competition->toArray();

        $data['competition_id'] = $competition->id;

        $data['user_id'] = $user->user_id;

        $valid = $user->validate($data);

        if (!$valid) {
            return response()->json($user->getValidationErrors(), 422);
        }

        $user->update($data);

        return new CompetitionUserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompetitionUser  $competitionUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id, $subid)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::competitionCanUpdate($competition, $request->user())){
            return response()->json(['error' => 'Access denied to competition.'], 403);
        }

        $user = CompetitionUser::where('competition_id', $id)->where('user_id', $subid)->first();

        if(!$user){
            return response()->json(['error' => 'The competition/user relationship does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $user->delete();

        return response()->json(null, 204);
    }
}

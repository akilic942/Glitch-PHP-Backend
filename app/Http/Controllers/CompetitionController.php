<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Facades\PermissionsFacade;
use App\Facades\RolesFacade;
use App\Http\Resources\CompetitionResource;
use App\Models\Competition;
use Illuminate\Http\Request;

class CompetitionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $competitions = Competition::query();

        $data = $competitions->orderBy('competitions.created_at', 'desc')->paginate(25);

        return CompetitionResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $competition = new Competition();

        $valid = $competition->validate($input);

        if (!$valid) {
            return response()->json($competition->getValidationErrors(), 422);
        }

        $competition = Competition::create($input);

        if($competition) {
            RolesFacade::eventMakeSuperAdmin($competition, $request->user());
        }

        return new CompetitionResource($competition);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Competition  $competition
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $competition = Competition::where('id', $id)->first();

        return new CompetitionResource($competition);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Competition  $competition
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The stream does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::competitionCanUpdate($competition, $request->user())){
            return response()->json(['error' => 'Access denied to live stream.' , 'message' => 'Access denied to live stream.'], 403);
        }

        $input = $request->all();

        $data = $input + $competition->toArray();

        $valid = $competition->validate($data);

        if (!$valid) {
            return response()->json($competition->getValidationErrors(), 422);
        }

        $competition->update($data);

        return new CompetitionResource($competition);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Competition  $competition
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $competition = Competition::where('id', $id)->first();

        if(!PermissionsFacade::competitionCanUpdate($competition, $request->user())){
            return response()->json(['error' => 'Access denied to live stream.'], 403);
        }
        
        $competition->delete();

        return response()->json(null, 204);
    }

    
}

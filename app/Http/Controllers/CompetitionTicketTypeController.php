<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Facades\PermissionsFacade;
use App\Http\Resources\CompetitionTicketTypeResource;
use App\Models\Competition;
use App\Models\CompetitionTicketType;
use Illuminate\Http\Request;

class CompetitionTicketTypeController extends Controller
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

        $typees = CompetitionTicketType::where('competition_id', '=', $competition->id);

        $data = $typees->orderBy('competition_ticket_types.created_at', 'desc')->paginate(25);

        return CompetitionTicketTypeResource::collection($data);
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

        $type = new CompetitionTicketType();

        $valid = $type->validate($input);

        if (!$valid) {
            return response()->json($type->getValidationErrors(), 422);
        }

        $type = CompetitionTicketType::create($input);

        return new CompetitionTicketTypeResource($type);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CompetitionTicketType  $CompetitionTicketType
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id, $subid)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $type = CompetitionTicketType::where('competition_id', $id)->where('id', $subid)->first();

        if(!$type){
            return response()->json(['error' => 'The ticket type does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        return new CompetitionTicketTypeResource($type);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompetitionTicketType  $CompetitionTicketType
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

        $type = CompetitionTicketType::where('competition_id', $id)->where('id', $subid)->first();

        if(!$type){
            return response()->json(['error' => 'The ticket type does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $input = $request->all();

        $data = $input + $competition->toArray();

        $data['competition_id'] = $competition->id;

        $data['id'] = $type->id;

        $valid = $type->validate($data);

        if (!$valid) {
            return response()->json($type->getValidationErrors(), 422);
        }

        $type->update($data);

        return new CompetitionTicketTypeResource($type);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompetitionTicketType  $CompetitionTicketType
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

        $type = CompetitionTicketType::where('competition_id', $id)->where('id', $subid)->first();

        if(!$type){
            return response()->json(['error' => 'The ticket type does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $type->delete();

        return response()->json(null, 204);
    }
}

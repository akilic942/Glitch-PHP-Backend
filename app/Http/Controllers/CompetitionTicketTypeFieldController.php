<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Facades\PermissionsFacade;
use App\Http\Resources\CompetitionTicketTypeFieldResource;
use App\Models\Competition;
use App\Models\CompetitionTicketType;
use App\Models\CompetitionTicketTypeField;
use Illuminate\Http\Request;

class CompetitionTicketTypeFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id, $type_id)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::competitionCanUpdate($competition, $request->user())){
            return response()->json(['error' => 'Access denied to competition.'], 403);
        }

        $type = CompetitionTicketType::where('competition_id', '=', $competition->id)
        ->where('id', '=', $type_id)
        ->first();

        if(!$type){
            return response()->json(['error' => 'The ticket type does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $fields = CompetitionTicketTypeField::where('ticket_type_id', $type->id);

        $data = $fields->orderBy('competition_ticket_type_fields.field_order', 'desc')->paginate(25);

        return CompetitionTicketTypeFieldResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id, $type_id)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::competitionCanUpdate($competition, $request->user())){
            return response()->json(['error' => 'Access denied to competition.'], 403);
        }

        $type = CompetitionTicketType::where('competition_id', '=', $competition->id)
        ->where('id', '=', $type_id)
        ->first();

        if(!$type){
            return response()->json(['error' => 'The ticket type does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $input = $request->all();

        $input['competition_id'] = $competition->id;

        $input['ticket_type_id'] = $type->id;

        $field = new CompetitionTicketTypeField();

        $valid = $field->validate($input);

        if (!$valid) {
            return response()->json($field->getValidationErrors(), 422);
        }

        $field = CompetitionTicketTypeField::create($input);

        return new CompetitionTicketTypeFieldResource($field);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CompetitionTicketType  $CompetitionTicketType
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id, $type_id, $field_id)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $type = CompetitionTicketType::where('competition_id', '=', $competition->id)
        ->where('id', '=', $type_id)
        ->first();

        if(!$type){
            return response()->json(['error' => 'The ticket type does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $field = CompetitionTicketTypeField::where('ticket_type_id', '=', $type->id)
        ->where('id', '=', $field_id)
        ->first();

        if(!$field){
            return response()->json(['error' => 'The field does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        return new CompetitionTicketTypeFieldResource($field);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompetitionTicketType  $CompetitionTicketType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $type_id, $field_id)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::competitionCanUpdate($competition, $request->user())){
            return response()->json(['error' => 'Access denied to competition.'], 403);
        }

        $type = CompetitionTicketType::where('competition_id', '=', $competition->id)
        ->where('id', '=', $type_id)
        ->first();

        if(!$type){
            return response()->json(['error' => 'The ticket type does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $field = CompetitionTicketTypeField::where('ticket_type_id', '=', $type->id)
        ->where('id', '=', $field_id)
        ->first();

        if(!$field){
            return response()->json(['error' => 'The field does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $input = $request->all();

        $data = $input + $competition->toArray();

        $data['ticket_type_id'] = $type->id;

        $data['id'] = $field->id;

        $valid = $field->validate($data);

        if (!$valid) {
            return response()->json($field->getValidationErrors(), 422);
        }

        $field->update($data);

        return new CompetitionTicketTypeFieldResource($field);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompetitionTicketType  $CompetitionTicketType
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id, $type_id, $field_id)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::competitionCanUpdate($competition, $request->user())){
            return response()->json(['error' => 'Access denied to competition.'], 403);
        }

        $type = CompetitionTicketType::where('competition_id', '=', $competition->id)
        ->where('id', '=', $type_id)
        ->first();

        if(!$type){
            return response()->json(['error' => 'The ticket type does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $field = CompetitionTicketTypeField::where('ticket_type_id', '=', $type->id)
        ->where('id', '=', $field_id)
        ->first();

        if(!$field){
            return response()->json(['error' => 'The field does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $field->delete();

        return response()->json(null, 204);
    }
}

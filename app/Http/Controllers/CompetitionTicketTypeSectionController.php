<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Facades\PermissionsFacade;
use App\Http\Resources\CompetitionTicketTypeSectionResource;
use App\Models\Competition;
use App\Models\CompetitionTicketType;
use App\Models\CompetitionTicketTypeSection;
use Illuminate\Http\Request;

class CompetitionTicketTypeSectionController extends Controller
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

        $type = CompetitionTicketType::where('competition_id', '=', $competition->id)
        ->where('id', '=', $type_id)
        ->first();

        if(!$type){
            return response()->json(['error' => 'The ticket type does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $sections = CompetitionTicketTypeSection::where('ticket_type_id', $type->id);

        $data = $sections->orderBy('competition_ticket_type_sections.section_order', 'desc')->paginate(25);

        return CompetitionTicketTypeSectionResource::collection($data);
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

        $section = new CompetitionTicketTypeSection();

        $valid = $section->validate($input);

        if (!$valid) {
            return response()->json($section->getValidationErrors(), 422);
        }

        $section = CompetitionTicketTypeSection::create($input);

        return new CompetitionTicketTypeSectionResource($section);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CompetitionTicketType  $CompetitionTicketType
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id, $type_id, $section_id)
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

        $section = CompetitionTicketTypeSection::where('ticket_type_id', '=', $type->id)
        ->where('id', '=', $section_id)
        ->first();

        if(!$section){
            return response()->json(['error' => 'The section does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        return new CompetitionTicketTypeSectionResource($section);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompetitionTicketType  $CompetitionTicketType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $type_id, $section_id)
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

        $section = CompetitionTicketTypeSection::where('ticket_type_id', '=', $type->id)
        ->where('id', '=', $section_id)
        ->first();

        if(!$section){
            return response()->json(['error' => 'The section does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $input = $request->all();

        $data = $input + $competition->toArray();

        $data['ticket_type_id'] = $type->id;

        $data['id'] = $section->id;

        $valid = $section->validate($data);

        if (!$valid) {
            return response()->json($section->getValidationErrors(), 422);
        }

        $section->update($data);

        return new CompetitionTicketTypeSectionResource($section);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompetitionTicketType  $CompetitionTicketType
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id, $type_id, $section_id)
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

        $section = CompetitionTicketTypeSection::where('ticket_type_id', '=', $type->id)
        ->where('id', '=', $section_id)
        ->first();

        if(!$section){
            return response()->json(['error' => 'The section does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $section->delete();

        return response()->json(null, 204);
    }
}

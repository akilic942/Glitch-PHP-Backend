<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Facades\PermissionsFacade;
use App\Http\Resources\CompetitionAddressResource;
use App\Models\Competition;
use App\Models\CompetitionAddress;
use Illuminate\Http\Request;

class CompetitionAddressController extends Controller
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

        $addresses = CompetitionAddress::query();

        $data = $addresses->orderBy('competition_addresses.created_at', 'desc')->paginate(25);

        return CompetitionAddressResource::collection($data);
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

        $address = new CompetitionAddress();

        $valid = $address->validate($input);

        if (!$valid) {
            return response()->json($address->getValidationErrors(), 422);
        }

        $address = CompetitionAddress::create($input);

        return new CompetitionAddressResource($address);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CompetitionAddress  $competitionAddress
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id, $subid)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $address = CompetitionAddress::where('competition_id', $id)->where('id', $subid)->first();

        if(!$address){
            return response()->json(['error' => 'The address does not exit.'], HttpStatusCodes::HTTP_FOUND);
        }

        return new CompetitionAddressResource($address);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompetitionAddress  $competitionAddress
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

        $address = CompetitionAddress::where('competition_id', $id)->where('id', $subid)->first();

        if(!$address){
            return response()->json(['error' => 'The address does not exit.'], HttpStatusCodes::HTTP_FOUND);
        }

        $input = $request->all();

        $data = $input + $competition->toArray();

        $data['competition_id'] = $competition->id;

        $data['id'] = $address->id;

        $valid = $address->validate($data);

        if (!$valid) {
            return response()->json($address->getValidationErrors(), 422);
        }

        $address->update($data);

        return new CompetitionAddressResource($address);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompetitionAddress  $competitionAddress
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

        $address = CompetitionAddress::where('competition_id', $id)->where('id', $subid)->first();

        if(!$address){
            return response()->json(['error' => 'The address does not exit.'], HttpStatusCodes::HTTP_FOUND);
        }

        $address->delete();

        return response()->json(null, 204);
    }
}

<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Facades\PermissionsFacade;
use App\Http\Resources\CompetitionVenueResource;
use App\Models\Competition;
use App\Models\CompetitionVenue;
use Illuminate\Http\Request;

class CompetitionVenueController extends Controller
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

        $addresses = CompetitionVenue::where('competition_id', '=', $competition->id);

        $data = $addresses->orderBy('competition_venues.created_at', 'desc')->paginate(25);

        return CompetitionVenueResource::collection($data);
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

        $address = new CompetitionVenue();

        $valid = $address->validate($input);

        if (!$valid) {
            return response()->json($address->getValidationErrors(), 422);
        }

        $address = CompetitionVenue::create($input);

        return new CompetitionVenueResource($address);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CompetitionVenue  $CompetitionVenue
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id, $subid)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $address = CompetitionVenue::where('competition_id', $id)->where('id', $subid)->first();

        if(!$address){
            return response()->json(['error' => 'The address does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        return new CompetitionVenueResource($address);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompetitionVenue  $CompetitionVenue
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

        $address = CompetitionVenue::where('competition_id', $id)->where('id', $subid)->first();

        if(!$address){
            return response()->json(['error' => 'The address does not exist.'], HttpStatusCodes::HTTP_FOUND);
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

        return new CompetitionVenueResource($address);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompetitionVenue  $CompetitionVenue
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

        $address = CompetitionVenue::where('competition_id', $id)->where('id', $subid)->first();

        if(!$address){
            return response()->json(['error' => 'The address does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $address->delete();

        return response()->json(null, 204);
    }

    public function uploadMainImage(Request $request, $id, $subid)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        // check if currently authenticated user is the owner of the book
        if(!PermissionsFacade::competitionCanUpdate($competition, $request->user())){
            return response()->json(['error' => 'Access denied to competition.'], 403);
        }

        $venue = CompetitionVenue::where('id', $subid)->first();

        if(!$venue){
            return response()->json(['error' => 'The venue does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $base_location = 'images';

        // Handle File Upload
        if($request->hasFile('image')) {              
            //Using store(), the filename will be hashed. You can use storeAs() to specify a name.
            //To specify the file visibility setting, you can update the config/filesystems.php s3 disk visibility key,
            //or you can specify the visibility of the file in the second parameter of the store() method like:
            //$imagePath = $request->file('document')->store($base_location, ['disk' => 's3', 'visibility' => 'public']);
            
            $imagePath = $request->file('image')->store($base_location, 's3');
          
        } else {
            return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
        }
        
        //We save new path
        $venue->forceFill([
            'venue_image' => $imagePath
        ]);

        $venue->save();
       
        return CompetitionVenueResource::make($venue);
    }
}

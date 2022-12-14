<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Facades\PermissionsFacade;
use App\Facades\RolesFacade;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = Team::query();

        $data = $teams->orderBy('teams.created_at', 'desc')->paginate(25);

        return TeamResource::collection($data);
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

        $team = new Team();

        $valid = $team->validate($input);

        if (!$valid) {
            return response()->json($team->getValidationErrors(), 422);
        }

        $team = Team::create($input);

        if($team) {
            RolesFacade::teamMakeSuperAdmin($team, $request->user());
        }

        return new TeamResource($team);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team, $id)
    {
        $team = Team::where('id', $id)->first();

        return new TeamResource($team);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $team = Team::where('id', $id)->first();

        if(!$team){
            return response()->json(['error' => 'The stream does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::teamCanUpdate($team, $request->user())){
            return response()->json(['error' => 'Access denied to live stream.' , 'message' => 'Access denied to live stream.'], 403);
        }

        $input = $request->all();

        $data = $input + $team->toArray();

        $valid = $team->validate($data);

        if (!$valid) {
            return response()->json($team->getValidationErrors(), 422);
        }

        $team->update($data);

        return new TeamResource($team);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $team = Team::where('id', $id)->first();

        if(!PermissionsFacade::teamCanUpdate($team, $request->user())){
            return response()->json(['error' => 'Access denied to live stream.'], 403);
        }
        
        $team->delete();

        return response()->json(null, 204);
    }

    public function uploadMainImage(Request $request, $id)
    {
        $team = Team::where('id', $id)->first();

        if(!$team){
            return response()->json(['error' => 'The team does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        // check if currently authenticated user is the owner of the book
        if(!PermissionsFacade::teamCanUpdate($team, $request->user())){
            return response()->json(['error' => 'Access denied to team.'], 403);
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
        $team->forceFill([
            'main_image' => $imagePath
        ]);

        $team->save();
       
        return TeamResource::make($team);
    }

    public function uploadBannerImage(Request $request, $id)
    {
        $team = Team::where('id', $id)->first();

        if(!$team){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        // check if currently authenticated user is the owner of the book
        if(!PermissionsFacade::teamCanUpdate($team, $request->user())){
            return response()->json(['error' => 'Access denied to the competitionee.'], 403);
        }

        $base_location = 'images';

        // Handle File Upload
        if($request->hasFile('image')) {              
            //Using store(), the filename will be hashed. You can use storeAs() to specify a name.
            //To specify the file visibility setting, you can update the config/filesystems.php s3 disk visibility key,
            //or you can specify the visibility of the file in the second parameter of the store() method like:
            //$imagePath = $request->file('document')->store($base_location, ['disk' => 's3', 'visibility' => 'public']);
            
            $imagePath = $request->file('image')->store($base_location, ['disk' => 's3', 'visibility' => 'public']);
          
        } else {
            return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
        }
    
        //We save new path
        $team->forceFill([
            'banner_image' => $imagePath
        ]);

        $team->save();
       
        return TeamResource::make($team);
    }
}

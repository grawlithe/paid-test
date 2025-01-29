<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUserTeams(Request $request, $team_id){

        $team = Team::find($team_id);
        $teamusers = $team->users->select('id', 'name')->toArray();

        //dd($teamusers);

        return response()->json($teamusers);
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    #GET A LACATION
    function RetrieveLocation(Request $request, $id)
    {
       $location = Location::where("visible",1)->with(["Locataire"])->find($id);
       return response()->json($location);
    }

}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\AgencyAccountSold;
use App\Models\City;
use App\Models\Country;
use App\Models\Departement;
use App\Models\Facture;
use App\Models\HomeStopState;
use App\Models\House;
use App\Models\HouseType;
use App\Models\Proprietor;
use App\Models\Quarter;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HouseController extends Controller
{
    #GET AN HOUSE
    function RetrieveHouse(Request $request, $id)
    {
       $house = House::with("Rooms")->where("visible",1)->find($id);
       return response()->json($house);
    }

}

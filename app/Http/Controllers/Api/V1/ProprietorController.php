<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Proprietor;
use App\Models\Room;
use Illuminate\Http\Request;

class ProprietorController extends Controller
{
    #GET A PROPRIETOR
    function RetrieveProprietor(Request $request, $id)
    {
       $proprietor = Proprietor::with(["Houses"])->find($id);
       return response()->json($proprietor);
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    #GET A ROOM
    function RetrieveRoom(Request $request, $id)
    {
        $room = Room::find($id);
        $locataires = [];
        foreach ($room->Locations as $location) {
            $locataires[] = $location->Locataire;
        }
        $room["locataires"] = $locataires;
        return response()->json($room);
    }
}

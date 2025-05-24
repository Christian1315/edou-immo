<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Locataire;
use Illuminate\Http\Request;

class LocatorController extends Controller
{
    #GET A LOCATOR
    function RetrieveLocator(Request $request, $id)
    {
        $locator = Locataire::with(['avaliseur'])->where("visible", 1)->find($id);
        $houses = [];
        $rooms = [];
        foreach ($locator->Locations as $location) {
            $houses[] = $location->House;
            $rooms[] = $location->Room;
        };

        $locator["houses"] = $houses;
        $locator["rooms"] = $rooms;
        return response()->json($locator);
    }
}

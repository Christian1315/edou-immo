<?php

namespace App\Http\Controllers;

use App\Models\StopHouseElectricityState;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Support\Facades\Log;

class StopHouseElectricityStateController extends Controller
{
    private const TIMEOUT_SECONDS = 3600;

    #VERIFIONS SI LE USER EST AUTHENTIFIE
    public function __construct()
    {
        $this->middleware(['auth'])->except(['ShowStateImprimeHtml']);
    }

    /**
     * Affiche l'état d'électricité en format PDF
     *
     * @param Request $request
     * @param int $stateId
     * @return Response|View
     */
    
    public function ShowStateImprimeHtml(Request $request, int $stateId)
    {
        try {
            set_time_limit(self::TIMEOUT_SECONDS);

            $state = StopHouseElectricityState::findOrFail($stateId);

            // Calcul des sommes des factures
            $factures = $state->StatesFactures->filter(fn($facture) => !$facture->state_facture);
            
            $facturesArray = $factures->pluck('amount')->toArray();
            $facturesSum = array_sum($facturesArray);
            
            $paidFacturesSum = $factures->where('paid', true)->sum('amount');
            $unpaidFacturesSum = $factures->where('paid', false)->sum('amount');

            $pdf = Pdf::loadView('electricity-state', [
                'state' => $state,
                'factures'=>$factures,
                'factures_sum' => $facturesSum,
                'paid_factures_sum' => $paidFacturesSum,
                'umpaid_factures_sum' => $unpaidFacturesSum
            ]);

            $pdf->setPaper('a4', 'landscape');

            return $pdf->stream();

        } catch (ModelNotFoundException $e) {
            Log::error('État d\'électricité non trouvé', [
                'state_id' => $stateId,
                'error' => $e->getMessage()
            ]);
            
            alert()->error("Erreur", "Cet état d'électricité n'existe pas");
            return back()->withInput();

        } catch (Exception $e) {
            Log::error('Erreur lors de la génération du PDF', [
                'state_id' => $stateId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            alert()->error("Erreur", "Une erreur est survenue lors de la génération du PDF");
            return back()->withInput();
        }
    }
}

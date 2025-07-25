<?php

namespace App\Http\Controllers;

use App\Models\CardType;
use App\Models\City;
use App\Models\Country;
use App\Models\Proprietor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Exception;
use Illuminate\Validation\ValidationException;

class ProprietorController extends Controller
{
    private const FILE_STORAGE_PATH = 'contrats';
    private const ERROR_MESSAGES = [
        'not_found' => "Ce propriétaire n'existe pas",
        'not_authorized' => "Ce propriétaire ne vous appartient pas",
        'city_not_found' => "Cette ville n'existe pas",
        'country_not_found' => "Ce pays n'existe pas",
        'card_type_not_found' => "Ce type de carte n'existe pas",
        'creation_failed' => "Une erreur est survenue, veuillez réessayer à nouveau!",
        'update_failed' => "Une erreur est survenue lors de la mise à jour!",
        'file_upload_failed' => "Une erreur est survenue lors de l'upload du fichier!",
    ];

    #VERIFIONS SI LE USER EST AUTHENTIFIE
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    // ################## LES VALIDATIONS #########################

    ##======== PROPRIETOR VALIDATION =======##
    static function proprietor_rules(): array
    {
        return [
            'firstname' => "required",
            'lastname' => "required",
            'phone' => "required",

            'sexe' => ['required'],
            // 'piece_number' => ['required'],
            // 'piece_file' => ['required', "file"],
            // 'mandate_contrat' => ['required', "file"],
            'adresse' => ['required'],

            'city' => "required|integer|exists:cities,id",
            'country' => "required|integer|exists:countries,id",
            'card_type' => "required|integer|exists:card_types,id",
            'agency' => "required|integer|exists:agencies,id",
        ];
    }

    static function proprietor_messages(): array
    {
        return [
            'firstname.required' => 'Veuillez précisez le prénom!',
            'lastname.required' => 'Veuillez précisez le nom!',
            'phone.required' => 'Veuillez précisez le phone!',
            'sexe.required' => 'Veuillez précisez le sexe!',

            // 'piece_number.required' => 'Veuillez précisez le numéro de la pièce!',

            // 'piece_file.required' => "La pièce d'identité est réquise",
            // 'piece_file.file' => 'Ce champ est un fichier',

            // 'mandate_contrat.required' => 'Veuillez précisez le contrat de location!',
            // 'mandate_contrat.file' => 'Ce champ doit être un fichier!',

            'adresse.required' => 'Veuillez précisez l\'adresse!',
            'city.required' => 'Veuillez précisez la ville!',
            'country.required' => 'Veuillez précisez le pays!',
            'card_type.required' => 'Veuillez précisez le type de carte!',

            'city.integer' => 'Ce champ doit être de type entier!',
            'country.integer' => 'Ce champ doit être de type entier!',
            'card_type.integer' => 'Ce champ doit être de type entier!',

            'agency.required' => "Veillez préciser l'agence",
            'agency.integer' => "L'agence doit être de type entier!",

            'phone.numeric' => 'Ce champ doit être de type numeric!',
            // 'phone.unique' => "Ce numéro de tephone existe déjà",
        ];
    }

    ###############===================================###############

    public function _AddProprietor(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $formData = $request->all();

            Validator::make($formData, self::proprietor_rules(), self::proprietor_messages())->validate();

            $formData['owner'] = $request->user()->id;

            if ($request->hasFile('mandate_contrat')) {
                $formData['mandate_contrat'] = $this->handleFileUpload($request->file('mandate_contrat'));
            }

            if ($request->hasFile('piece_file')) {
                $formData['piece_file'] = $this->handleFileUpload($request->file('piece_file'));
            }

            $proprietor = Proprietor::create($formData);

            if (!$proprietor) {
                throw new Exception(self::ERROR_MESSAGES['creation_failed']);
            }

            DB::commit();
            alert()->success("Succès", "Propriétaire ajouté avec succès!");
            return redirect()->back();
        } catch (ValidationException $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (Exception $e) {
            DB::rollBack();
            alert()->error("Échec", $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function updateProprietor(Request $request, int $id): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $proprietor = Proprietor::where('visible', 1)->findOrFail($id);
            $user = $request->user();

            if (!$this->canModifyProprietor($user, $proprietor)) {
                throw new Exception(self::ERROR_MESSAGES['not_authorized']);
            }

            $formData = $request->all();

            $this->validateRelatedEntities($request);

            if ($request->hasFile('mandate_contrat')) {
                $formData['mandate_contrat'] = $this->handleFileUpload($request->file('mandate_contrat'));
            }

            $updated = $proprietor->update($formData);

            if (!$updated) {
                throw new Exception(self::ERROR_MESSAGES['update_failed']);
            }

            DB::commit();
            alert()->success("Succès", "Propriétaire modifié avec succès");
            return redirect()->back();
        } catch (ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors());
        } catch (Exception $e) {
            DB::rollBack();
            alert()->error("Échec", $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    private function handleFileUpload($file): string
    {
        try {
            $fileName = $file->getClientOriginalName();
            $file->move(self::FILE_STORAGE_PATH, $fileName);
            return asset(self::FILE_STORAGE_PATH . '/' . $fileName);
        } catch (Exception $e) {
            throw new Exception(self::ERROR_MESSAGES['file_upload_failed']);
        }
    }

    private function canModifyProprietor($user, $proprietor): bool
    {
        return $user->is_master || $user->is_admin || $proprietor->owner === $user->id;
    }

    private function validateRelatedEntities(Request $request): void
    {
        $validations = [
            'city' => City::class,
            'country' => Country::class,
            'card_type' => CardType::class,
        ];

        foreach ($validations as $field => $model) {
            if ($request->get($field) && !$model::find($request->get($field))) {
                throw new Exception(self::ERROR_MESSAGES[$field . '_not_found']);
            }
        }
    }
}

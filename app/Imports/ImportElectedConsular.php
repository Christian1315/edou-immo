<?php

namespace App\Imports;

use App\Models\ElectedConsular;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use function Laravel\Prompts\alert;

class ImportElectedConsular implements ToModel, WithHeadingRow, WithChunkReading
{
    /**
     * @param Collection $collection
     */
    public function model(array $consular)
    {
        $excel_date = $consular["birth_date"]; //Your row line date that is : 3/17/2022
        $uniq_date = ($excel_date - 25569) * 86400;
        $date = gmdate('Y-m-d', $uniq_date);

        return ElectedConsular::create([
            "owner" => request()->user()->id,
            "ifu" => $consular["ifu"],
            "npi" => $consular["npi"],
            "firstname" => $consular["firstname"],
            "lastname" => $consular["lastname"],
            "sexe" => $consular["sexe"],
            // "photo" => $consular->photo,
            "phone" => $consular["phone"],
            "email" => $consular["email"],
            "birth_date" => $date,
            "place_of_birth" => $consular["place_of_birth"],
            "country_of_birth" => $consular["country_of_birth"],
            "nationnality" => $consular["nationnality"],
        ]);
    }

    public function chunkSize(): int
    {
        return 10;
    }
}

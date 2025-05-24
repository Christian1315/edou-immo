<?php

namespace App\Imports;

use App\Models\Company;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportCompany implements ToModel, WithHeadingRow, WithChunkReading
{
    /**
     * @param Collection $collection
     */
    public function model(array $consular)
    {
        $excel_date = $consular["creation_date"]; //Your row line date that is : 3/17/2022
        $uniq_date = ($excel_date - 25569) * 86400;
        $date = gmdate('Y-m-d', $uniq_date);

        return Company::create([
            "owner" => request()->user()->id,
            "ifu" => $consular["ifu"],
            "denomination" => $consular["denomination"],
            "form_juridique" => $consular["form_juridique"],
            "principal_activity" => $consular["principal_activity"],
            "activity_area" => $consular["activity_area"],
            "phone" => $consular["phone"],
            "email" => $consular["email"],
            "creation_date" => $date,
            "departement" => $consular["departement"],
            "adresse" => $consular["adresse"],
            "rccm" => $consular["rccm"],
        ]);
    }

    public function chunkSize(): int
    {
        return 10;
    }
}

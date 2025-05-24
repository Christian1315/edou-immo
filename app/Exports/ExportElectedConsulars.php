<?php

namespace App\Exports;

use App\Models\ElectedConsular;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ExportElectedConsulars implements FromCollection, WithHeadings, WithStrictNullComparison #WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return ElectedConsular::all([
            "npi",
            "ifu",
            "lastname",
            "firstname",
            "sexe",
            "phone",
            "email",
            "birth_date",
            "country_of_birth",
            "place_of_birth",
            "nationnality"
        ]);
    }

    public function headings(): array
    {
        return [
            "NPI",
            "IFU",
            "LASTNAME",
            "FIRSTNAME",
            "SEXE",
            "PHONE",
            "EMAIL",
            "DATE DE NAISSANCE",
            "PAYS DE NAISSANCE",
            "LIEU DE NAISSANCE",
            "NATIONNALITE"
        ];
    }

    // public function map($row): array
    // {
    //     return [
    //         $row->npi,
    //         $row->ifu,
    //         $row->lastname,
    //         $row->firstname,
    //         $row->sexe,
    //         $row->phone,
    //         $row->email,
    //         $row->birth_date,
    //         $row->country_of_birth,
    //         $row->place_of_birth,
    //         $row->nationnality,
    //     ];
    // }
}

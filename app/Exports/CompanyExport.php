<?php

namespace App\Exports;

use App\Models\Company;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class CompanyExport implements FromCollection, WithHeadings, WithStrictNullComparison
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Company::all([
            "ifu",
            "denomination",
            "form_juridique",
            "principal_activity",
            "activity_area",
            "creation_date",
            "phone",
            "email",
            "departement",
            "adresse",
            "rccm",
        ]);
    }

    public function headings(): array
    {
        return [
            "IFU",
            "DENOMINATION",
            "FORME JURIDIQUE",
            "ACTIVITE PRINCIPALE",
            "DOMAINE D'ACTIVITE",
            "DATE DE CREATION",
            "TELEPHONE",
            "EMAIL",
            "DEPARTEMENT",
            "ADRESSE",
            "RCCM",
        ];
    }
}

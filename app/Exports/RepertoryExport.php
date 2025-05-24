<?php

namespace App\Exports;

use App\Models\Repertory;
use Maatwebsite\Excel\Concerns\FromCollection;

class RepertoryExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Repertory::all();
    }
}

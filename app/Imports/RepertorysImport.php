<?php

namespace App\Imports;

use App\Models\Repertory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class RepertorysImport implements ToModel, WithHeadingRow, WithChunkReading
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $contact)
    {
        return Repertory::create([
            'firstname'    => $contact['firstname'],
            'lastname'     => $contact['lastname'],
            'contact'    => $contact['contact'],
            'ministry'    => $contact['ministry'],
            'denomination'    => $contact['denomination'],
            'residence'    => $contact['residence'],
            'commune'    => $contact['commune'],
            'owner'    => request()->user()->id,
        ]);
    }

    public function chunkSize(): int
    {
        return 10;
    }
}

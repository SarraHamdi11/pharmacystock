<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Maladie;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CategoryImport implements ToModel, WithHeadingRow
{
    
    public function model(array $row)
    {
      
        $maladie = null;
        if (!empty($row['maladie_associee'])) {
            $maladie = Maladie::where('name', $row['maladie_associee'])->first();
        }

        
        return new Category([
            'name' => $row['nom_de_la_categorie'],
            'maladie_id' => $maladie ? $maladie->id : null,
        ]);
    }
}
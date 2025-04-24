<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CategoryExport implements FromCollection, WithHeadings
{
   
    public function collection()
    {
        
        $categories = Category::with('maladie')->get();
        
        
        return $categories->map(function($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'maladie' => $category->maladie ? $category->maladie->name : null,
            ];
        });
    }

    
    public function headings(): array
    {
        return [
            'ID',
            'Nom de la catégorie',
            'Maladie associée'
        ];
    }
}
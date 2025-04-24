<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\DB;


class ProductImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        $product = Product::where('id','=',$row['id'])->first();

        if ($product)
        {
            return;
        }

        $category = Category::where('name','=',$row['category'])->first();
        if(!$category)
        {
            $category = Category::create(['name'=>$row['category']]);
        }


        $supplier = Supplier::where(DB::Raw("Concat(first_name, ' ', last_name)"),'=',$row['supplier'])->first();

        if(!$supplier)
        {
            $fullName = $row['supplier'];
            $parts = explode(' ', trim($fullName), 2);

            $firstName = $parts[0] ?? '';
            $lastName = $parts[1] ?? $parts[0]; // Si pas de nom de famille, utiliser le même que prénom

            $supplier = Supplier::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone'=>' ',
            ]);
        }

        return new Product([
            'id' => $row['id'],
            'name' => $row['name'],
            'description'  => $row['description'],
            'price' =>$row['price'],
            'category_id'=>$category->id,
            'supplier_id'=>$supplier->id
        ]);

    }

     /**
     * Write code on Method
     *
     * @return response()
     */
    public function rules(): array
    {
        return [
            // 'name' => 'required|min:5|max:255',
            // 'description' => 'required|min:5',
            // 'price' => 'required',
            // 'supplier'=> 'required',
            // 'category'=> 'required'
        ];
    }


    public function headingRow(): int
    {
        return 5;
    }
}

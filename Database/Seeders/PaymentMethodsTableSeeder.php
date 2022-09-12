<?php

namespace Modules\Payment\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Payment\Entities\PaymentMethod;

class PaymentMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rows = [
            [
                'name' => [
                    "ar"=>"فوري ايداع بالكارت",
                    "en"=>"Fawry Credit Card",
                ],
                'description' => [
                    "ar"=>"فوري ايداع بالكارت",
                    "en"=>"Fawry Credit Card",
                ],
                'icon'=>"bx bx-circle",
                'color'=>"#b69ddd",
            ],
            [
                'name' => [
                    "ar"=>"فوري ايداع بالكود",
                    "en"=>"Fawry Code",
                ],
                'description' => [
                    "ar"=>"فوري ايداع بالكود",
                    "en"=>"Fawry Code",
                ],
                'icon'=>"bx bx-circle",
                'color'=>"#252525",
            ],
            [
                'name' => [
                    "ar"=>"باي موب",
                    "en"=>"Paymob",
                ],
                'description' => [
                    "ar"=>"الدقع بواسطه باي موب",
                    "en"=>"pay by paymob",
                ],
                'icon'=>"bx bx-circle",
                'color'=>"#ff0000",
            ],
            [
                'name' => [
                    "ar"=>"باي تاب",
                    "en"=>"Paytabs",
                ],
                'description' => [
                    "ar"=>"الدقع بواسطه باي تابس",
                    "en"=>"pay by paytabs",
                ],
                'icon'=>"bx bx-circle",
                'color'=>"#ff0000",
            ]
        ];

        foreach ($rows as $row){
            $method=PaymentMethod::whereJsonContains('name', ["en"=>$row['name']['en']]);
            if($method->count()){
                $method->update($row);
            }else
            PaymentMethod::create($row);
        }
    }
}

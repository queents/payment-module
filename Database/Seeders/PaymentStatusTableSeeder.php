<?php

namespace Modules\Payment\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Payment\Entities\PaymentStatus;

class PaymentStatusTableSeeder extends Seeder
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
                    "ar"=>"جديد",
                    "en"=>"NEW",
                ],
                'icon'=>"bx bx-circle",
                'color'=>"#b69ddd",
            ],
            [
                'name' => [
                    "ar"=>"مدفوع",
                    "en"=>"PAID",
                ],
                'icon'=>"bx bx-circle",
                'color'=>"#252525",
            ],
            [
                'name' => [
                    "ar"=>"ملغاه",
                    "en"=>"CANCELLED",
                ],
                'icon'=>"bx bx-circle",
                'color'=>"#252525",
            ],
            [
                'name' => [
                    "ar"=>"قيد التنفيذ",
                    "en"=>"PENDING",
                ],
                'icon'=>"bx bx-circle",
                'color'=>"#252525",
            ]
        ];

        foreach ($rows as $row){
            $method=PaymentStatus::whereJsonContains('name', ["en"=>$row['name']['en']]);
            if($method->count()){
                $method->update($row);
            }else
                PaymentStatus::create($row);
        }
    }
}

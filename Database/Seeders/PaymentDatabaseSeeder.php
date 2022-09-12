<?php

namespace Modules\Payment\Database\Seeders;

use Illuminate\Database\Seeder;

use Modules\Payment\Database\Seeders\PaymentIntegrationTableSeeder;

class PaymentDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PaymentMethodsTableSeeder::class);
        $this->call(PaymentIntegrationTableSeeder::class);
        $this->call(PaymentStatusTableSeeder::class);
    }
}

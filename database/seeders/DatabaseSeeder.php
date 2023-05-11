<?php

namespace Database\Seeders;

use App\Enums\UserTypeEnum;
use App\Utils\CnpjUtil;
use App\Utils\CpfUtil;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Test Common User',
            'email' => 'common@user.com',
            'type' => UserTypeEnum::COMMON->value,
            'cpf_cnpj' => CpfUtil::cpfGenerate(),
            'balance' => 1000
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Test Seller User',
            'email' => 'seller@user.com',
            'type' => UserTypeEnum::SELLER->value,
            'cpf_cnpj' => CnpjUtil::cnpjGenerate(),
            'balance' => 1000
        ]);
    }
}

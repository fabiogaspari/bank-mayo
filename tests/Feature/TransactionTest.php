<?php

namespace Tests\Feature;

use App\Enums\UserTypeEnum;
use App\Models\User;
use App\Utils\CnpjUtil;
use App\Utils\CpfUtil;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_tranference_is_success(): void
    {
        $userCommon = User::factory()->create([
            'balance' => 100,
            'cpf_cnpj' => CpfUtil::cpfGenerate(),
            'type' => UserTypeEnum::COMMON->value
        ]);

        $cnpj = CnpjUtil::cnpjGenerate();
        
        User::factory()->create([
            'balance' => 100,
            'cpf_cnpj' => $cnpj,
            'type' => UserTypeEnum::SELLER->value
        ]);

        $data = [
            'cpf_cnpj' => $cnpj,
            'money' => 99.99
        ];

        $response = $this
            ->actingAs($userCommon)
            ->postJson(route('transactions.store'), $data);

        $response->assertOk();
    }

    public function test_tranference_success_balances(): void
    {
        $userCommon = User::factory()->create([
            'balance' => 100,
            'cpf_cnpj' => CpfUtil::cpfGenerate(),
            'type' => UserTypeEnum::COMMON->value
        ]);

        $cnpj = CnpjUtil::cnpjGenerate();
        
        $userSeller = User::factory()->create([
            'balance' => 100,
            'cpf_cnpj' => $cnpj,
            'type' => UserTypeEnum::SELLER->value
        ]);

        $data = [
            'cpf_cnpj' => $cnpj,
            'money' => 90.90
        ];

        $response = $this
            ->actingAs($userCommon)
            ->postJson(route('transactions.store'), $data);

        $userCommon->refresh();
        $userSeller->refresh();

        $this->assertEquals(190.90, $userSeller->balance);
        $this->assertEquals(9.10, $userCommon->balance);

        $response->assertOk();
    }

    public function test_tranference_error_seller_transfer(): void
    {
        $userSeller = User::factory()->create([
            'balance' => 100,
            'cpf_cnpj' => CnpjUtil::cnpjGenerate(),
            'type' => UserTypeEnum::SELLER->value
        ]);

        $cpf = CpfUtil::cpfGenerate();

        User::factory()->create([
            'balance' => 100,
            'cpf_cnpj' => $cpf,
            'type' => UserTypeEnum::COMMON->value
        ]);

        $data = [
            'cpf_cnpj' => $cpf,
            'money' => 99.99
        ];

        $response = $this
            ->actingAs($userSeller)
            ->postJson(route('transactions.store'), $data);

        $response->assertUnauthorized();
    }

    public function test_tranference_is_error_without_balance(): void
    {
        $userCommon = User::factory()->create([
            'balance' => 100,
            'cpf_cnpj' => CpfUtil::cpfGenerate(),
            'type' => UserTypeEnum::COMMON->value
        ]);

        $cnpj = CnpjUtil::cnpjGenerate();
        
        User::factory()->create([
            'balance' => 100,
            'cpf_cnpj' => $cnpj,
            'type' => UserTypeEnum::SELLER->value
        ]);

        $data = [
            'cpf_cnpj' => $cnpj,
            'money' => 101
        ];

        $response = $this
            ->actingAs($userCommon)
            ->postJson(route('transactions.store'), $data);

        $response->assertStatus(400);
        $response->assertJson([
            "errors" => [
                "saldo_insuficiente" => "Saldo insuficiente. Seu saldo atual é: R$ 100,00"
            ]
        ]);
    }

    public function test_tranference_is_error_cant_send_to_myself(): void
    {
        $cpf = CpfUtil::cpfGenerate();
        $userCommon = User::factory()->create([
            'balance' => 100,
            'cpf_cnpj' => $cpf,
            'type' => UserTypeEnum::COMMON->value
        ]);

        $data = [
            'cpf_cnpj' => $cpf,
            'money' => 88
        ];

        $response = $this
            ->actingAs($userCommon)
            ->postJson(route('transactions.store'), $data);

        $response->assertStatus(400);
        $response->assertJson([
            "errors" => [
                "cant_myself" => "Você não pode enviar dinheiro para sí mesmo."
            ]
        ]);
    }

}

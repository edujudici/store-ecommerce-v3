<?php

namespace Tests\Unit\Models;

use App\Models\Voucher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * @group ModelTest
 */
class VoucherTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $voucher;

    public function setUp() :void
    {
        parent::setUp();

        $this->voucher = Voucher::factory()
            ->for(User::factory())
            ->create();
    }

    /** @test */
    public function vouchers_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('vouchers', [
                'vou_id', 'vou_id_base', 'user_uuid', 'vou_code', 'vou_value',
                'vou_expiration_date', 'vou_applied_date', 'vou_description',
                'vou_status',
            ]),
            1
        );
    }

    /** @test */
    public function a_voucher_belongs_to_a_user()
    {
        $this->assertInstanceOf(User::class, $this->voucher->user);
    }

    /** @test */
    public function get_status()
    {
        $status = $this->voucher::getStatus();
        $this->assertIsArray($status);
        $this->assertContains('Ativo', $status);
        $this->assertContains('Inativo', $status);
    }
}

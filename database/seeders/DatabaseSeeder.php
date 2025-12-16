<?php

namespace Database\Seeders;

use App\Enums\SymbolEnum;
use App\Models\Asset;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'balance' => 999999999.0,
        ]);

        $user = User::factory()->create([
            'name' => 'Second User',
            'email' => 'test2@example.com',
        ]);

        $assets = [];

        $btc = new Asset;

        $btc->amount = 1000.0;
        $btc->locked_amount = 0.0;
        $btc->symbol = SymbolEnum::BTC;

        $eth = clone $btc;
        $eth->symbol = SymbolEnum::ETH;

        $assets[] = $btc;
        $assets[] = $eth;

        $user->assets()->saveMany($assets);
    }
}

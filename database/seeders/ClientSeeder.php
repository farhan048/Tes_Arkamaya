<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Client::insert([
        [
            'client_name'   => 'NEC',
            'address'       => 'Jakarta',
        ],
        [
            'client_name'   => 'TAM',
            'address'       => 'Jakarta',
        ],
        [
            'client_name'   => 'TUA',
            'address'       => 'Bandung',
        ],        
     ]);
    }
}

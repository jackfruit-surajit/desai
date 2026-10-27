<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\State;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = [
            // 28 States
            ['state_name' => 'Andhra Pradesh', 'state_code' => 'AP', 'status' => 1],
            ['state_name' => 'Arunachal Pradesh', 'state_code' => 'AR', 'status' => 1],
            ['state_name' => 'Assam', 'state_code' => 'AS', 'status' => 1],
            ['state_name' => 'Bihar', 'state_code' => 'BR', 'status' => 1],
            ['state_name' => 'Chhattisgarh', 'state_code' => 'CG', 'status' => 1],
            ['state_name' => 'Goa', 'state_code' => 'GA', 'status' => 1],
            ['state_name' => 'Gujarat', 'state_code' => 'GJ', 'status' => 1],
            ['state_name' => 'Haryana', 'state_code' => 'HR', 'status' => 1],
            ['state_name' => 'Himachal Pradesh', 'state_code' => 'HP', 'status' => 1],
            ['state_name' => 'Jharkhand', 'state_code' => 'JH', 'status' => 1],
            ['state_name' => 'Karnataka', 'state_code' => 'KA', 'status' => 1],
            ['state_name' => 'Kerala', 'state_code' => 'KL', 'status' => 1],
            ['state_name' => 'Madhya Pradesh', 'state_code' => 'MP', 'status' => 1],
            ['state_name' => 'Maharashtra', 'state_code' => 'MH', 'status' => 1],
            ['state_name' => 'Manipur', 'state_code' => 'MN', 'status' => 1],
            ['state_name' => 'Meghalaya', 'state_code' => 'ML', 'status' => 1],
            ['state_name' => 'Mizoram', 'state_code' => 'MZ', 'status' => 1],
            ['state_name' => 'Nagaland', 'state_code' => 'NL', 'status' => 1],
            ['state_name' => 'Odisha', 'state_code' => 'OD', 'status' => 1],
            ['state_name' => 'Punjab', 'state_code' => 'PB', 'status' => 1],
            ['state_name' => 'Rajasthan', 'state_code' => 'RJ', 'status' => 1],
            ['state_name' => 'Sikkim', 'state_code' => 'SK', 'status' => 1],
            ['state_name' => 'Tamil Nadu', 'state_code' => 'TN', 'status' => 1],
            ['state_name' => 'Telangana', 'state_code' => 'TS', 'status' => 1],
            ['state_name' => 'Tripura', 'state_code' => 'TR', 'status' => 1],
            ['state_name' => 'Uttar Pradesh', 'state_code' => 'UP', 'status' => 1],
            ['state_name' => 'Uttarakhand', 'state_code' => 'UK', 'status' => 1],
            ['state_name' => 'West Bengal', 'state_code' => 'WB', 'status' => 1],

            // 8 Union Territories
            ['state_name' => 'Andaman and Nicobar Islands', 'state_code' => 'AN', 'status' => 1],
            ['state_name' => 'Chandigarh', 'state_code' => 'CH', 'status' => 1],
            ['state_name' => 'Dadra and Nagar Haveli and Daman and Diu', 'state_code' => 'DN', 'status' => 1],
            ['state_name' => 'Delhi', 'state_code' => 'DL', 'status' => 1],
            ['state_name' => 'Jammu and Kashmir', 'state_code' => 'JK', 'status' => 1],
            ['state_name' => 'Ladakh', 'state_code' => 'LA', 'status' => 1],
            ['state_name' => 'Lakshadweep', 'state_code' => 'LD', 'status' => 1],
            ['state_name' => 'Puducherry', 'state_code' => 'PY', 'status' => 1],
        ];

        foreach ($states as $state) {
            State::updateOrCreate(
                ['state_name' => $state['state_name']],
                [
                    'state_code' => $state['state_code'],
                    'status' => $state['status'],
                ]
            );
        }
    }
}


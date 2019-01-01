<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'Wali Nagari',
                'Ini wali nagari coy'
            ],
            [
                'Sekretaris',
                'Ini sekretaris'
            ],
            [
                'Kasi Keuangan',
                'Ini Kepala Seksi Keuangan'
            ]
        ];

        foreach ($roles as $key => $role) {
            Role::create([
                'title' => $role[0],
                'description' => $role[1]
            ]);
        }
    }
}

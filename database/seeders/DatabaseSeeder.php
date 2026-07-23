<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear tenant padre: Legal Tecnologías
        $legaltec = Tenant::firstOrCreate(
            ['slug' => 'legaltec'],
            [
                'name' => 'Legal Tecnologías',
                'status' => 'active',
                'plan' => 'enterprise',
                'max_users' => 100,
                'max_cajas' => 10,
                'activo' => true,
            ]
        );

        // Crear super admin si no existe
        User::firstOrCreate(
            ['email' => 'daniel@legaltecnologias.pe'],
            [
                'name' => 'Daniel León',
                'password' => bcrypt('admin123'),
                'rol' => 'super_admin',
                'tenant_id' => $legaltec->id,
                'activo' => true,
            ]
        );
    }
}
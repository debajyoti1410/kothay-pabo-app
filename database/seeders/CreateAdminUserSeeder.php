<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::create([
            'name'      => 'superadmin', 
            'email'     => 'superadmin@gmail.com',
            'password'  => bcrypt('superadmin2001')
        ]);
        
        $admin = User::create([
            'name'      => 'admin', 
            'email'     => 'admin@gmail.com',
            'password'  => bcrypt('admin1234')
        ]);
    
        $superAdminRole   = Role::create(['name' => 'Super Admin']);     
        $adminRole        = Role::create(['name' => 'Admin']);     
        $permissions      = Permission::pluck('id','id')->all();   
        $superAdminRole->syncPermissions($permissions);     
        $adminRole->syncPermissions($permissions);     
        $superAdmin->assignRole([$superAdminRole->id]);
        $admin->assignRole([$adminRole->id]);
    }
}

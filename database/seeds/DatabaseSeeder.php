<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Tool;
use App\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        // seed roles
        $role_employee = new Role();
        $role_employee->name = 'admin';
        $role_employee->description = 'An Admin User';
        $role_employee->save();

        $role_manager = new Role();
        $role_manager->name = 'customer';
        $role_manager->description = 'A Customer User';
        $role_manager->save();

        $role_manager = new Role();
        $role_manager->name = 'staff';
        $role_manager->description = 'A Staff User';
        $role_manager->save();

        $role_manager = new Role();
        $role_manager->name = 'block';
        $role_manager->description = 'Customer has block';
        $role_manager->save();

        // define user
        $role_admin = Role::where('name', 'admin')->first();
        $role_customer  = Role::where('name', 'customer')->first();
        $role_staff  = Role::where('name', 'staff')->first();
        $role_waiting  = Role::where('name', 'waiting')->first();
        $role_block  = Role::where('name', 'block')->first();

        //  seed make user
        $manager = new User();
        $manager->name = 'Admin Name';
        $manager->email = 'admin@gmail.com';
        $manager->password = bcrypt('123456');
        $manager->save();
        $manager->roles()->attach($role_admin);

        $staff = new User();
        $staff->name = 'Staff Name';
        $staff->email = 'staff@gmail.com';
        $staff->password = bcrypt('123456');
        $staff->save();
        $staff->roles()->attach($role_staff);
        
        $customer = new User();
        $customer->name = 'Customer Name';
        $customer->email = 'customer@gmail.com';
        $customer->password = bcrypt('123456');
        $customer->save();
        $customer->roles()->attach($role_customer);

        $block = new User();
        $block->name = 'Block Name';
        $block->email = 'block@gmail.com';
        $block->password = bcrypt('123456');
        $block->save();
        $block->roles()->attach($role_block);
       
    }
}

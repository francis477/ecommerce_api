
<?php

use App\Models\User;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Hardik',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'),
        ]);
        $admin_role = Role::create([
        'name' => 'superadmin',
        'guard_name' => 'web',
    ]);
    $admin = User::findOrFail(1);
    $role = Role::find(1);
    $admin->assignRole($role);
    
    }
}

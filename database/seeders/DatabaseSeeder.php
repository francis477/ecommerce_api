<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductReview;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // $this->call(UserSeeder::class);
        // $this->call(PermissionSeeder::class);
        $data = [
            ['name' => 'user.view'],
            ['name' => 'user.create'],
            ['name' => 'user.update'],
            ['name' => 'user.delete'],

            ['name' => 'customer.view'],
            ['name' => 'customer.create'],
            ['name' => 'customer.update'],
            ['name' => 'customer.delete'],

            ['name' => 'product.view'],
            ['name' => 'product.create'],
            ['name' => 'product.update'],
            ['name' => 'product.delete'],

            ['name' => 'category.view'],
            ['name' => 'category.create'],
            ['name' => 'category.update'],
            ['name' => 'category.delete'],

            ['name' => 'brand.view'],
            ['name' => 'brand.create'],
            ['name' => 'brand.update'],
            ['name' => 'brand.delete'],

            ['name' => 'role.view'],
            ['name' => 'role.create'],
            ['name' => 'role.update'],
            ['name' => 'role.delete'],

            ['name' => 'permission.view'],
            ['name' => 'permission.create'],
            ['name' => 'permission.update'],
            ['name' => 'permission.delete'],

            ['name' => 'contact.view'],
            ['name' => 'contact.create'],
            ['name' => 'contact.update'],
            ['name' => 'contact.delete'],

        ];

        $insert_data = [];
        $time_stamp = Carbon::now()->toDateTimeString();
        foreach ($data as $d) {
            $d['guard_name'] = 'web';
            $d['created_at'] = $time_stamp;
            $insert_data[] = $d;
        }
        Permission::insert($insert_data);


        User::create([
            'name' => 'francis477',
            'email' => 'francisteye477@gmail.com',
            'password' => Hash::make('admin123')
        ]);

        $admin_role = Role::create([
        'name' => 'superadmin',
        'guard_name' => 'web'
    ]);

    $admin_role->syncPermissions([
    'user.view', 'user.create', 'user.update', 'user.delete',
    'customer.view', 'customer.create', 'customer.update', 'customer.delete',
    'product.view', 'product.create', 'product.update', 'product.delete',
    'category.view', 'category.create', 'category.update', 'category.delete',
    'brand.view', 'brand.create', 'brand.update', 'brand.delete',
    'role.view', 'role.create', 'role.update', 'role.delete',
      'permission.view', 'permission.create', 'permission.update', 'permission.delete',
      'contact.view', 'contact.create', 'contact.update', 'contact.delete'
]);


$admin_new = Role::create([
    'name' => 'admin',
    'guard_name' => 'web'
]);

$admin_new->syncPermissions([
'customer.view', 'customer.create', 'customer.update', 'customer.delete',
'product.view', 'product.create', 'product.update', 'product.delete',
'category.view', 'category.create', 'category.update', 'category.delete',
'brand.view', 'brand.create', 'brand.update', 'brand.delete',
  'contact.view', 'contact.create', 'contact.update', 'contact.delete'
]);


$user_new = Role::create([
    'name' => 'user',
    'guard_name' => 'web'
]);

$user_new->syncPermissions([
'product.view', 'product.create', 'product.update', 'product.delete',
'category.view', 'category.create', 'category.update', 'category.delete',
'brand.view', 'brand.create', 'brand.update', 'brand.delete',
  'contact.view', 'contact.create', 'contact.update', 'contact.delete'
]);


    $admin = User::findOrFail(1);
    $role = Role::find(1);
    $admin->assignRole($role->id);


   Category::create([
    'name' => 'Computing',
    'slug' => 'Computing',
    'user_id' => 1
]);
Category::create([
    'name' => 'Electronic',
    'slug' => 'Electronic',
    'user_id' => 1
]);
Category::create([
    'name' => 'Phones',
    'slug' => 'Phones',
    'user_id' => 1
]);

Category::create([
    'name' => 'Fashion',
    'slug' => 'Fashion',
    'user_id' => 1
]);

Brand::create([
    'name' => 'Andoer',
    'slug' => 'Computing',
    'user_id' => 1
]);


Brand::create([
    'name' => 'Baofeng',
    'slug' => 'Baofeng',
    'user_id' => 1
]);


Brand::create([
    'name' => 'Generic',
    'slug' => 'Generic',
    'user_id' => 1
]);


Brand::create([
    'name' => 'Camelion',
    'slug' => 'Camelion',
    'user_id' => 1
]);




    }

    }


<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\PerModule;
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

        PerModule::create([
            'm_name' => 'User'
        ]);
        PerModule::create([
            'm_name' => 'Customer'
        ]);
        PerModule::create([
            'm_name' => 'Product'
        ]);
        PerModule::create([
            'm_name' => 'Category'
        ]);
        PerModule::create([
            'm_name' => 'Brand'
        ]);
        PerModule::create([
            'm_name' => 'Role'
        ]);
        PerModule::create([
            'm_name' => 'Permission'
        ]);
        PerModule::create([
            'm_name' => 'Contact'
        ]);
        $data = [
            ['name' => 'user.view','model_id' => 1,'label' => 'User'],
            ['name' => 'user.create','model_id' => 1,'label' => 'User'],
            ['name' => 'user.update','model_id' => 1,'label' => 'User'],
            ['name' => 'user.delete','model_id' => 1,'label' => 'User'],

            ['name' => 'customer.view','model_id' => 2,'label' => 'Customer'],
            ['name' => 'customer.create','model_id' => 2,'label' => 'Customer'],
            ['name' => 'customer.update','model_id' => 2,'label' => 'Customer'],
            ['name' => 'customer.delete','model_id' => 2,'label' => 'Customer'],

            ['name' => 'product.view','model_id' => 3,'label' => 'Product'],
            ['name' => 'product.create','model_id' => 3,'label' => 'Product'],
            ['name' => 'product.update','model_id' => 3,'label' => 'Product'],
            ['name' => 'product.delete','model_id' => 3,'label' => 'Product'],

            ['name' => 'category.view','model_id' => 4,'label' => 'Category'],
            ['name' => 'category.create','model_id' => 4,'label' => 'Category'],
            ['name' => 'category.update','model_id' => 4,'label' => 'Category'],
            ['name' => 'category.delete','model_id' => 4,'label' => 'Category'],

            ['name' => 'brand.view','model_id' => 5,'label' => 'Brand'],
            ['name' => 'brand.create','model_id' => 5,'label' => 'Brand'],
            ['name' => 'brand.update','model_id' => 5,'label' => 'Brand'],
            ['name' => 'brand.delete','model_id' => 5,'label' => 'Brand'],

            ['name' => 'role.view','model_id' => 6,'label' => 'Role'],
            ['name' => 'role.create','model_id' => 6,'label' => 'Role'],
            ['name' => 'role.update','model_id' => 6,'label' => 'Role'],
            ['name' => 'role.delete','model_id' => 6,'label' => 'Role'],

            ['name' => 'permission.view','model_id' => 7,'label' => 'Permission'],
            ['name' => 'permission.create','model_id' => 7,'label' => 'Permission'],
            ['name' => 'permission.update','model_id' => 7,'label' => 'Permission'],
            ['name' => 'permission.delete','model_id' => 7,'label' => 'Permission'],

            ['name' => 'contact.view','model_id' => 8,'label' => 'Contact'],
            ['name' => 'contact.create','model_id' => 8,'label' => 'Contact'],
            ['name' => 'contact.update','model_id' => 8,'label' => 'Contact'],
            ['name' => 'contact.delete','model_id' => 8,'label' => 'Contact'],

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


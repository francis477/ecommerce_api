<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public $my_role;

    public function index(Request $request)
    {
        $users = User::all();
        foreach ($users as $key => $row) {
            if (!empty($row->getRoleNames())) {
                foreach ($row->getRoleNames() as $key => $role) {
                    $this->my_role =  $role;
                }
            }
            $data[] =
                [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'email' => $row['email'],
                    "created_at" => $row['created_at'],
                    "updated_at" => $row['updated_at'],
                    'role' => $this->my_role,
                ];
        }
        $success = 'Created Successful';
        return response(['user' => $data, 'message' =>  $success,]);
    }



    //
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:55',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string',
            'role' => 'required'

        ]);

        if ($validator->fails()) {
            failedValidation($validator);
        }


        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->save();
        $role_id = $request->input('role');
        $role = Role::find($role_id);
        $user->assignRole($role);
        $success = 'Created Successful';
        return response(['message' =>  $success, 'user' => $user,'role'=> $role]);
    }



    public function showUserById($id)
    {
        $user = User::find($id);
        if (!empty($user->getRoleNames())) {
            foreach ($user->getRoleNames() as $key => $role) {
                $this->my_role =  $role;
            }
        }
        $data = [
            "id" => $user->id,
            "name" =>  $user->name,
            "email" =>  $user->email,
            "created_at" => $user->created_at,
            "updated_at" => $user->updated_at,
            "role" => $this->my_role
        ];


        $success = 'Created Successful';

        return response(['user' => $data]);
    }

    public function updateUserById(Request $request, $id)
    {

        $request->validate([
            'name' => 'required|max:55',
            'email' => 'required',
            'roles' => 'required'

        ]);

        $input = $request->all();
        $user = User::find($id);
        $user->update($input);
        $user->assignRole($request->input('roles'));

        if (!empty($user->getRoleNames())) {
            foreach ($user->getRoleNames() as $key => $role) {
                $this->my_role =  $role;
            }
        }

        $data = [
            "id" => $user->id,
            "name" =>  $user->name,
            "email" =>  $user->email,
            "created_at" => $user->created_at,
            "updated_at" => $user->updated_at,
            "role" => $this->my_role
        ];

        $success = 'Updated Successful';

        return response(['user' => $data, 'message' =>  $success,]);
    }

    public function deleteUserById($id)
    {
        User::find($id)->delete();
        $success = 'User deleted successfully';
        return response(['message' =>  $success,]);
    }


    public function login(Request $request){
        //Validate Fields
        $fields = $request->validate([
         'email'=>'required|string|email',
         'password'=>'required|string'
        ]);

        //Check email

        $user= User::where('email', $fields['email'])->first();

        //Check Password
        if(!$user || !Hash::check($fields['password'], $user->password) ){
            return response([
                'message'=>'Invalid Credentials'
            ], 401);
        }
    //Create Token
        $token = $user->createToken($user->id)->plainTextToken;
           //Get User Role
        $get_role = $user->roles;
        foreach ($get_role as $key => $value) {
            $row_id = $value['id'];
            $row_name = $value['name'];
        };

            $data =
             [
                "user_id" => $user->id,
                "user_name" => $user->name,
                "user_email" => $user->email,
                "createdAt" => $user->created_at,
            ];

        // $get_id= User::find($user->id);
        // if (!empty($get_id->getRoleNames())) {
        //     foreach ($get_id->getRoleNames() as $key => $role) {
        //         $this->my_role =  $role;
        //     }
        // }

        // $test = $user->roles->pluck('id');
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $row_id)
            ->get();

        $response= [
            'user' => $data,
            'role_id'=> $row_id,
            'role_name'=> $row_name,
            'permissions' => $rolePermissions,
            'token'=> $token


        ];

        return response($response, 201);
    }



    public function logout(Request $request) {

         $request->user()->tokens()->delete();
         $response = [
             'status'=>'success',
             'message' => 'Logout Successful'
         ];

         return response($response,200);
     }
}

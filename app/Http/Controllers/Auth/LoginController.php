<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    //

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
}

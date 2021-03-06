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
        $RoleId = $user->roles->pluck('id')->all();
        $get_id =   implode('' ,$RoleId);
        $convert_id = (int)$get_id;
        if($convert_id == null){
            return response([
                'message'=>'Access Denied!!!'
            ], 401);
        };



    //Create Token
        $token = $user->createToken($user->id)->plainTextToken;
           //Get User Role
        $get_role = $user->roles;
        foreach ($get_role as $key => $value) {
            $row_name = $value['name'];
        };


    //    $profile ='https://ui-avatars.com/api/?background=random&name='.urlencode($this->$user->name);

            $data =
             [
                "user_id" => $user->id,
                "user_name" => $user->name,
                "user_email" => $user->email,
               'pro_image' => $user->profile_image_url,
                // "createdAt" => $user->created_at,
                "role_name"=> $row_name,
                'role_id'=>$convert_id
            ];

        // $get_id= User::find($user->id);
        // if (!empty($get_id->getRoleNames())) {
        //     foreach ($get_id->getRoleNames() as $key => $role) {
        //         $this->my_role =  $role;
        //     }
        // }


        // $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
        //     ->where("role_has_permissions.role_id", $row_id)
        //     ->get();



        $response= [
            'status_code'=> 200,
            'status_message'=> "Login Successful",
            'data' =>
            [
               'user'=> $data,
               'token'=> $token


            ],
            // 'role_id'=> $row_id,


            // 'permissions' => $rolePermissions,




        ];

        return response($response, 201);
    }
}

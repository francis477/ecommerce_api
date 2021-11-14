<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public $my_role;

    public function index(Request $request)
    {
        $users = User::all();
        foreach ($users as $key => $row) {
           if(!empty($row->getRoleNames())){
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
                "updated_at" =>$row['updated_at'],
                  'role' => $this->my_role,
            ];

        }
        $success = 'Created Successful';
        return response(['user'=> $data, 'message' =>  $success,]);
    }



    //
    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required|max:55',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string',
            'roles' => 'required'

        ]);
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $user->save();

        $accessToken = $user->createToken('authToken')->accessToken;

        $user->assignRole($request->input('roles'));

            $success = 'Created Successful';

            return response(['user'=> $user, 'message' =>  $success,]);

    }



    public function showUserById($id)
    {
        $user = User::find($id);
            if(!empty($user->getRoleNames())){
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

        return response(['user'=> $data]);


    }

    public function updateUserById(Request $request, $id){

        $request->validate([
            'name' => 'required|max:55',
            'email' => 'required',
            'roles' => 'required'

        ]);

        $input = $request->all();
        $user = User::find($id);
        $user->update($input);
        $user->assignRole($request->input('roles'));

        if(!empty($user->getRoleNames())){
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

            return response(['user'=> $data, 'message' =>  $success,]);

    }

    public function deleteUserById($id)
    {
        User::find($id)->delete();
        $success = 'User deleted successfully';
        return response(['message' =>  $success,]);
    }


}

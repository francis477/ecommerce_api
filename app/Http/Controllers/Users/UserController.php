<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Facade\FlareClient\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    public $my_role;

    function __construct()
    {
        $this->middleware(['role:superadmin|admin']);


    }


    public function index(Request $request)
    {

        if (!auth()->user()->can('user.view')) {
            return abortAction();
        }

        $users = User::all();
        // $role = Role::find($role_id);
        // $user->assignRole($role);
        foreach ($users as $row) {
            if (!empty($row->getRoleNames())) {
                foreach ($row->getRoleNames() as $key => $role) {
                    $this->my_role =  $role;
                }
            }
            $name =$this->my_role;
            $data[] =
                [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'email' => $row['email'],
                    "created_at" => $row['created_at'],
                    "updated_at" => $row['updated_at'],
                    'role' => $name
                ];
        }
        $success = 'Created Successful';
        return response(['user' => $data, 'message' =>  $success,]);
    }



    //
    public function register(Request $request)
    {
        if (!auth()->user()->can('user.create')) {
            return abortAction();
        }

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
        if (!auth()->user()->can('user.view')) {
            return abortAction();

        }

            $user = User::where("id",$id)->first();

            if(!$user){
                return notFound();
              }
            $user_role = auth()->user();
            foreach ($user_role->roles as $value) {
                $role_id =  $value->id;
                $role_name =  $value->name;
            }


            $data = [
                "id" => $user->id,
                "name" =>  $user->name,
                "email" =>  $user->email,
                "created_at" => $user->created_at,
                "updated_at" => $user->updated_at,
                "role_id" =>  $role_id,
                'role_name' => $role_name
            ];
            return response(['user' => $data]);
    }

    public function updateUserById(Request $request, $id)
    {
        if (!auth()->user()->can('user.update')) {
            return abortAction();
        }

        $request->validate([
            'name' => 'required|max:55',
            'email' => 'required|email|unique:users,id,'.$request->user()->id,
            'roles' => 'required'

        ]);

        $input = $request->all();
        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
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
        if (!auth()->user()->can('user.delete')) {
            return abortAction();
        }
        User::find($id)->delete();
        $success = 'User deleted successfully';
        return response(['message' =>  $success,]);
    }

}

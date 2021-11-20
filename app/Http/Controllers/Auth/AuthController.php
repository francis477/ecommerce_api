<?php

namespace App\Http\Controllers\Auth;

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

class AuthController extends Controller
{
    public $my_role;

    function __construct()
    {
        $this->middleware(['role:superadmin|admin']);


    }

    public function profile($id)
    {


            $user = User::where("id",$id)->first();

            if(!$user){
                return notFound();
              }
              if (!empty($user->getRoleNames())) {
                foreach ($user->getRoleNames() as $key => $role) {
                    $this->my_role =  $role;
                }
            }

            // $new = auth()->user();
            $data = [
                "id" => $user->id,
                "name" =>  $user->name,
                "email" =>  $user->email,
                "role" => $this->my_role,
                // 'test' => $new
            ];
            return response(['user' => $data]);
    }


    public function update_user_profile(Request $request,$id)
    {
        $user = User::find($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:55',
            'email' => 'required|string|email'
        ]);
        if ($validator->fails()) {
            failedValidation($validator);
        }
         $user->name = $request->name;
         $user->email = $request->email;
         $user->save();
        $success = 'Updated Successful';
        return response(['message' =>  $success,]);

    }



    public function change_user_password(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'old_pass' => 'required',
            'new_pass' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            failedValidation($validator);
        }

        $user = User::find($id);
        if (!(Hash::check($request->old_pass, $user->password))) {
            // The passwords matches
            $success = 'Your current password does not matches with the password.';
            return response(['message' =>  $success,]);

        }

        if(strcmp($request->old_pass, $request->new_pass) == 0){
            // Current password and new password same
            $success = 'New Password cannot be same as your current password.';
            return response(['message' =>  $success,]);
        }
         //Change Password
         $user->password = Hash::make($request->new_pass);
         $user->save();
        $success = 'Updated Successful';
        return response(['message' =>  $success,]);
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

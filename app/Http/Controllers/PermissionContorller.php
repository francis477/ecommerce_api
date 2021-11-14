<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionContorller extends Controller
{
    //


    public function index()
    {
        $permission = Permission::all();
            $success = 'Created Successful';
            return response(['permission'=> $permission, 'message' =>  $success,]);

    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $main_arr = array();
        $name  = $request->name;
        $guard_name  = "web";
        for ($i=0;$i < sizeof($name); $i++) {
            $data = array(
                'name' => $name[$i],
                'guard_name'     => $guard_name,
            );
            $main_arr[] = $data;
        }
        $permission = Permission::insert($main_arr);

            $success = 'Created Successful';

            return response(['permission'=> $permission, 'message' =>  $success,]);

    }


    public function show($id)
    {
        $permission = Permission::find($id);
            $success = 'Created Successful';
            return response(['permission'=> $permission, 'message' =>  $success,]);

    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $permission = Permission::find($id);
        $permission->name = $request->input('name');
        $permission->save();

            $success = 'Created Successful';

            return response(['permission'=> $permission, 'message' =>  $success,]);

    }


    public function destroy($id)
    {
        Permission::find($id)->delete();
        $success = 'Permission deleted successfully';
        return response(['message' =>  $success,]);
    }

}

<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{

    function __construct()
    {
      $this->middleware(['role:superadmin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = Role::orderBy('id', 'DESC')->get();
        foreach ($roles as $value) {

            $data [] = [
                'id' => $value['id'],
                'name' => $value['name']
            ];
        }
        $success = 'Requested Successfully';
        return response(['message' =>  $success,'data' =>['items'=>$data]] );
    }

    public function createRole(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name'
        ]);
        $role = Role::create(
            [
                'name' => $request->input('name'),
                'guard_name' => "web"

                ]
        );
        $success = 'Created Successfully';
        return response(['data' => $role, 'message' =>  $success,]);
    }




    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required'
        ]);
        $role_id = $request->input('name');

        $role = Role::find($role_id);
        $role->syncPermissions($request->input('permission'));

        $success = 'Created Successfully';

        return response(['message' =>  $success,]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

            foreach ($rolePermissions as $value) {

                $permission_data[] = [
                    'id' =>  $value['id'],
                    'name' =>  $value['name'],
                ];

                # code...
            }

        $success = 'Requested Successfully';
        $roel_data = [
            'id' => $role->id,
            'name' => $role->name
        ];

        return response(
        [
        'message' =>  $success,
        'data' => [
            'items' => [
             'role' => $roel_data, 'permissions' => $permission_data
            ]
        ]]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();


        $success = 'Requested Successfully';

        return response(['role' => $role, 'permission' => $permission, 'rolePermissions' => $rolePermissions, 'message' =>  $success,]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);


        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->guard_name = "web";
        $role->save();
        $role->syncPermissions($request->input('permission'));

        $success = 'Updated Successfully';

        return response(['message' =>  $success,]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table("roles")->where('id', $id)->delete();
        $success = 'Deleted Successfully';
        return response(['message' =>  $success,]);
    }
}

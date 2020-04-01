<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role = Role::all();
        return view('roles.index', ['roles' => $role]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $role = new Role();
        $role->name = $request->name;
        $role->save();

        return redirect()->route('role.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::where('id', $id)->first();
        return view('roles.edit',['role' => $role]);
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
        $role = Role::where('id', $id)->first();
        $role->name = $request->name;
        $role->save();

        return redirect()->route('role.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::where('id', $id)->first();
        $role->delete();


        return redirect()->route('role.index');

    }






    public function permissions($id)
    {
        $role = Role::where('id', $id)->first();

        $permissions = Permission::all();

        foreach($permissions as $permission){
            if($role->hasPermissionTo($permission->name)){
                $permission->can = "checked";
            }else{
                $permission->can = "";
            }
        }

        return view('roles.permissions', ['role'=>$role, 'permissions'=>$permissions]);

    }







    public function permissionsSync(Request $request, $role)
    {

      $permissionsRequest = $request->except(['_token', '_method']);

      foreach($permissionsRequest as $key){
          $permissions[] = Permission::where('id', $key)->first();
      }


      $role = Role::where('id', $role)->first();

        if(!empty($permissions)){
            $role->syncPermissions($permissions);
        }else{
            $role->syncPermissions(null);
        }



       return redirect()->route('role.permissions', ['role'=>$role->id]);

    }


}

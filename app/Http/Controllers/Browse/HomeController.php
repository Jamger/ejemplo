<?php

namespace App\Http\Controllers\Browse;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
use App\role_has_permissions;
use Alert;

class HomeController extends Controller
{

    /*
    * Esta funcion permite validar si el usuario esta logeado dentro de la aplicacion
    */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function indexUsers()
    {
    	$users = User::all();
        $administrador = Role::find(2);
    	//dd($users);

    	return view('Browse.users')
                ->with('users', $users)
                ->with('administrador', $administrador);
    }

    public function formPermission()
    {

        //Contendra un objeto con todos permisos en la BD, y paginados de 2 en 2
    	$permissions = \App\Permission::paginate(2);

    	return view('Browse.permission.form', compact('permissions'));
    }

    public function formRoles()
    {

    	$roles = \App\Role::paginate(2);

    	return view('Browse.roles.form', compact('roles'));
    }

    /*
    * Esta funcion permite crear roles
    */
    public function createRoles(Request $request)
    {

        /*
        * $request = objeto con los campos del formulario
        */

        //Se llama a la clase Role, la cual tiene una funcion llamada create, la cual sirve para crear los roles
    	Role::create(['name' => $request->name_role]);

        //Se muestra un mensaje indicando que el rol a sido creado
    	Alert::success('¡Rol registrado!', 'Mensaje')->persistent('Ok');
        //Volvemos a la pagina anterior (Formulario de creacion de roles)
    	return redirect()->back();
    }

    public function createPermission(Request $request)
    {
    	Permission::create(['name' => $request->name_permission]);

    	Alert::success('¡Permiso registrado!', 'Mensaje')->persistent('Ok');
    	return redirect()->back();
    }

    public function formEditRoles($role)
    {

        $role = Role::find($role);
        $permissions = \App\Permission::GetRaw($role); 

        return view('browse.roles.editForm', compact('role', 
                                                     'permissions'));
    }

    public function editRoles(Request $request, $role)
    {

        $role = Role::find($role);

        $role->name = $request->name_role;
        $role->save();

        Alert::success('¡Registro actualizado!', 'Mensaje')->persistent('Ok');
        return redirect()->back();

    }

    public function assingPermissions(Request $request, $role)
    {

        $role = Role::find($role);
        $permissions = $request->permission;

        for ($i=0; $i < sizeof($permissions); $i++) 
        { 

            $role->givePermissionTo($request->permission[$i]);

        }

        Alert::success("¡Permisos asignados al rol '$role->name'!", 'Mensaje')->persistent('Ok');
        return redirect()->back();
        
    }

    public function destroyUsers($id)
    {
        $user = User::find($id);

        dd($user);
    }

    public function formEditUsers($user)
    {

        $user = User::find($user);
        $roles = Role::orderBy('name', 'ASC');

        return view('browse.editUsers', compact('user',
                                                'roles'));

    }

    public function editUsers(Request $request, $user) 
    {

        $user = User::find($user);

        $user->name = $request->name_user;

        $user->save();
        
        $user->syncRoles($request->name_rol);

        Alert::success('¡Registro actualizado!', 'Mensaje')->persistent('Ok');
        return redirect()->back();

    }

    public function formEditPermissions($permission)
    {

       $permission = Permission::find($permission);

       return view('browse.permission.editForm', compact('permission'));
    }

    public function editPermission(Request $request, $permission)
    {

        $permission = Permission::find($permission);

        $permission->name = $request->name_permission;
        $permission->save();

        Alert::success('¡Registro actualizado!', 'Mensaje')->persistent('Ok');
        return redirect()->back();
    }
}

<?php

namespace App\Http\Controllers;

use stdClass;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    
    /**
     * Display index page
     *
     * @return \Illuminate\View\View
     */
    public function getIndex()
    {
        $roles = Role::paginate(10);
        return view('admin.pages.role.index', ['roles' => $roles]);
    }

    /**
     * Display new form
     *
     * @return \Illuminate\View\View
     */
    public function displayNewForm()
    {
        $role = new stdClass();
        $role->id = null;
        $role->uuid = null;
        $role->title = null;

        return view('admin.pages.role.form', [
            'header_title' => 'Create new role',
            'role' => $role,
        ]);
    }

    /**
     * Display edit form
     *
     * @param string $uuid
     * 
     * @return \Illuminate\View\View
     */
    public function editForm(string $uuid)
    {
        $role = Role::where('uuid', $uuid)->first();

        return view('admin.pages.role.form', [
            'header_title' => 'Update role',
            'role' => $role,
        ]);

    }

    /**
     * Create role
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postSave(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:roles,title,' . $request->id,
        ]);

        $data = $request->except(['id']);
        
        if (!$request->has('id')) {
            $data['uuid'] = generateUuid();
        }

        Role::updateOrCreate(['id' => $request->id], $data);
        return response()->json(['message' => 'Role successfully saved.']);
    }

    /**
     * Delete role
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteRole(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        /** @todo What to do if there's user associated to this role? */
        Role::find($request->id)->delete();
        return response()->json(['message' => 'Role has been deleted.']);
    }
}

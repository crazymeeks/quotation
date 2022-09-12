<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    

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
        
        $data['permission_id'] = $request->permission;
        unset($data['permission']);
        if (!$request->has('id')) {
            $data['uuid'] = generateUuid();
        }

        Role::updateOrCreate(['id' => $request->id], $data);
        return response()->json(['message' => 'Role successfully saved.']);
    }
}

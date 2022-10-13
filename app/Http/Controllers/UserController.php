<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repository\UserRepository;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{


    /**
     * Display index page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.pages.users.index');
    }

    
    /**
     * Get users list
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Repository\UserRepository $userRepository
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDataTable(Request $request, UserRepository $userRepository)
    {
        $data = $userRepository->getDataTable($request);

        return $data;
    }

    /**
     * Display add new form
     *
     * @return \Illuminate\View\View
     */
    public function displayAddNewForm()
    {
        $user = new User();
        $roles = Role::get();

        return view('admin.pages.users.form', [
            'user' => $user,
            'roles' => $roles
        ]);
    }

    /**
     * Display edit form
     *
     * @param string $uuid
     * 
     * @return \Illuminate\View\View
     */
    public function displayEditForm(string $uuid)
    {
        $user = User::whereUuid($uuid)->first();
        $roles = Role::get();

        return view('admin.pages.users.form', [
            'user' => $user,
            'roles' => $roles
        ]);
    }


    
    /**
     * Save user
     *
     * @param \App\Http\Requests\UserRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function postSave(UserRequest $request)
    {
        try {
            
            DB::transaction(function() use ($request) {
                $cond = ['id' => $request->id];
                $data = $request->except(['role', 'user', 'id']);
                $data['role_id'] = $request->role;
                $data['name'] = sprintf("%s %s", $request->firstname, $request->lastname);
                if (!$request->has('id')) {
                    $data['uuid'] = generateUuid();
                }

                if ($request->has('password')) {
                    $data['password'] = bcrypt($request->password);
                }

                User::updateOrCreate($cond, $data);
            });
            return response()->json([
                'message' => 'User successfully saved.'
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            
            return response()->json([
                'message' => 'Unable to save user. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Delete user
     *
     * @param Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $request->validate([
            'uuid' => 'required',
        ]);
        $user = User::whereUuid($request->uuid)->first();

        if ($user !== null) {
            $user->deleted_at = now()->__toString();
            $user->username = sprintf("%s%s", $user->username, generate_string());
            $user->save();
            return response()->json([
                'message' => sprintf("%s was removed.", $user->firstname),
            ]);
        }

        return response()->json([
            'message' => 'Could not be remove. User not found.'
        ], 400);
    }
}

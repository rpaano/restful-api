<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $users = User::all();

        return $this->showAll($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ];

        $this->validate($request, $rules);

        $data                       = $request->all();
        $data['password']           = bcrypt($request->password);
        $data['verified']           = User::UNVERIFIED_USER;
        $data['verification_token'] = User::generateVerificationCode();
        $data['admin']              = User::REGULAR_USER;

        $user = User::create($data);

        return $this->showOne($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return $this->showOne($user);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'email'    => 'email|unique:users,email'. $user->id,
            'password' => 'min:6|confirmed',
            'admin'    => 'in:'. User::REGULAR_USER, User::ADMIN_USER,
        ];

        $this->validate($request, $rules);

        if ($request->has('name')){
            $user->name = $request->name;
        }

        if ($request->has('email') && $user->email != $request->email){
            $user->verified           = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationCode();
            $user->email              = $request->email;
        }

        if ($request->has('password')){
            $user->password = bcrypt($request->password);
        }

        if ($request->has('admin')){
            if (!$user->verified){
                return response()->json(['error' => 'Only verified user can modify the admin field', 'code' => 409], 409);
            }

            $user->admin = $request->admin;
        }

        if ($user->isDirty()) {
            return response()->json(['error' => 'You need to specify a new value to update', 'code' => 422], 422);
        }

        $user->save();

        return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return $this->showOne($user);
    }
}

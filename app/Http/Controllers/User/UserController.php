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
     * @param User $user
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'email'    => 'email|unique:users,email,'. $user->id,
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
                return $this->errorResponse('Only verified user can modify the admin field', 409);
            }

            $user->admin = $request->admin;
        }

        if (!$user->isDirty()) {
            return $this->errorResponse('You need to specify a new value to update', 422);
        }

        $user->save();

        return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $user->delete();

        return $this->showOne($user);
    }

    public function verify(User $user)
    {
        $user->verified = User::VERIFIED_USER;
        $user->verification_token = null;

        $user->save();

        return $this->showMessage("The account has been verified successfully");
    }
}

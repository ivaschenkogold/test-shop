<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Role;
use App\Traits\InterfaceTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use InterfaceTrait;

    public function index()
    {
        $users = User::getAdmins();
        return view($this->interface . ".user.index")->withUsers($users);
    }

    public function show(Request $request, Role $role, User $user)
    {
        $roles = $role->getWithoutAdmin();
        $user = $user->where('id', $request->id)->whereNotIn('role_id', [0, 1])->first();

        return view($this->interface . '.user.show')->withRoles($roles)->withUser($user);
    }

    public function create(Role $role)
    {
        $roles = $role->getWithoutAdmin();
        return view($this->interface . ".user.create")->withRoles($roles);
    }

    public function store(Request $request, User $user)
    {
        $validator = $this->storeValidation($request->all());
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role_id = $request->role;
        $user->save();

        return redirect()->route('user.index')->withStatus('Новый пользователь был успешно создан');
    }

    public function storeValidation($data)
    {
        return Validator::make($data, [
            'name' => 'required|string|min:1|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6|max:255',
            'role' => 'required|integer|min:2|exists:roles,id'
        ]);
    }

    public function edit(Request $request, User $user)
    {
        $validator = $this->editValidation($request->all());
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $user = $user->find($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->role_id = $request->role;
        $user->save();

        return redirect()->route('user.index')->withStatus('Пользователь был успешно сохранен');
    }

    public function editValidation($data)
    {
        return Validator::make($data, [
            'id' => 'required|integer|min:2|exists:users,id',
            'name' => 'required|string|min:1|max:255',
            'email' => 'required|email|unique:users,email,' . $data['id'] . ',id',
            'password' => 'nullable|confirmed|min:6|max:255',
            'role' => 'required|integer|min:2|exists:roles,id|not_in:0,1'
        ]);
    }

    public function delete(Request $request, User $user)
    {
        $validator = $this->deleteValidation($request->all());
        $validator->after(function ($validator) use ($user, $request) {
            $user = $user->find($request->delete_user_id);
            if ($user->role_id < 2) {
                $validator->errors()->add('user', 'Нельзя удалять пользователей этой роли');
            }
        });
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $user->find($request->delete_user_id)->delete();
        return redirect()->route('user.index')->withStatus("Пользователь был успешно удален");
    }

    public function deleteValidation($data)
    {
        return Validator::make($data, [
           'delete_user_id' => 'required|integer|min:2|exists:users,id'
        ]);
    }
}

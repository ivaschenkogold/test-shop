<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Role;
use App\Traits\InterfaceTrait;
use App\Models\Admin\Module;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    use InterfaceTrait;

    public function index(Request $request, Role $role)
    {
        $roles = $role->where('id', '<>', 1)->get();
        return view($this->interface . '.role.index')->withRoles($roles);
    }

    public function show(Request $request, Role $role, Module $module)
    {
        $role = $role->find($request->id);
        if ($role) {
            $modules = $module->all();
            $roles = $role->where('id', '<>', 1)->get();
            return view($this->interface . '.role.show')->withRole($role)->withModules($modules)->withRoles($roles);
        }
        return redirect('/404');
    }

    public function create(Module $module)
    {
        $modules = $module->all();
        return view($this->interface . '.role.create')->withModules($modules);
    }

    public function store(Request $request, Role $role)
    {
        $validator = $this->storeValidation($request->all());
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        $role->name = $request->name;
        $role->save();

        $role->funcs()->sync($request->func);

        return redirect()->route('role.index')->withStatus("Новая роль была успешно создана");
    }

    public function storeValidation($data)
    {
        return Validator::make($data, [
            'name' => 'required|min:1|max:100',
            'func.*' => 'integer|exists:functions,id'
        ]);
    }

    public function edit(Request $request, Role $role)
    {
        $validator = $this->editValidation($request->all());
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $role = $role->find($request->role_id);
        $role->name = $request->name;
        $role->save();

        $role->funcs()->sync($request->func);

        return redirect()->route('role.index')->withStatus('Роль была успешно сохранена');
    }

    public function editValidation($data)
    {
        return Validator::make($data, [
            'role_id' => 'required|integer|min:2|exists:roles,id',
            'name' => 'required|min:1|max:100',
            'func.*' => 'integer|exists:functions,id'
        ]);
    }

    public function delete(Request $request, Role $role)
    {
        $validator = $this->deleteValidation($request->all());
        $validator->after(function ($validator) use ($request, $role) {
            $delete_role = $role->find($request->delete_role_id);
            if ($delete_role->users->count() >= 1){
                $move_to_role = $role->find($request->move_users_to);
                if (!$move_to_role) {
                    $validator->errors()->add('move_users_to', 'Выберите роль, куда переместить пользователей');
                }elseif($move_to_role->id == $delete_role->id) {
                    $validator->errors()->add('move_users_to', 'Вы не можете переместить пользователейв удаляемую роль');
                } elseif ($move_to_role->id == 1 || $move_to_role->id == 0) {
                    $validator->errors()->add('move_users_to', 'Вы не можете переместить пользователейв в эту роль');
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $role = $role->find($request->delete_role_id);
        if ($role->users->count() > 1) {
            $role->users->update(['role_id' => $request->move_users_to]);
        }
        $role->delete();
        return redirect()->back()->withStatus('Роль была успешно удалена');
    }

    public function deleteValidation($data)
    {
        return Validator::make($data, [
            'delete_role_id' => 'required|integer|min:2|exists:roles,id'
        ]);
    }
}

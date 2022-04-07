<?php

namespace Cyaoz94\LaravelUtilities;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Cyaoz94\LaravelUtilities\Filters\RoleFilter;
use Cyaoz94\LaravelUtilities\Models\Role;
use Cyaoz94\LaravelUtilities\Resources\RoleResource;
use Cyaoz94\LaravelUtilities\Resources\RoleWithPermissionResource;

class RoleController extends CrudController
{
    public function __construct(Request $request)
    {
        $this->modelClass = Role::class;
        $this->filterClass = RoleFilter::class;
        $this->resourceClass = RoleResource::class;

        parent::__construct($request);
    }

    public function show($id)
    {
        $role = $this->modelClass::where('id', $id)->with('permissions')->firstOrFail();

        return $this->commonJsonResponse($this->resourceClass ? new $this->resourceClass($role) : $role);
    }

    public function store(Request $request, array $validationRules = [])
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array|exists:permissions,name',
        ]);

        $role = $this->modelClass::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return $this->commonJsonResponse([], 'Created Successfully.');
    }

    public function update(Request $request, $id, array $validationRules = [])
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'required|array|exists:permissions,name',
        ]);

        $role = $this->modelClass::findOrFail($id);
        $this->authorize('update', $role); // authorize with RolePolicy

        $role->name = $request->name;
        $role->save();

        $role->syncPermissions($request->permissions);

        return $this->commonJsonResponse([], 'Updated Successfully.');
    }

    public function destroy($id)
    {
        $role = $this->modelClass::findOrFail($id);
        $this->authorize('delete', $role); // authorize with RolePolicy
        $role->delete();

        return $this->commonJsonResponse([], 'Deleted Successfully.');
    }
}

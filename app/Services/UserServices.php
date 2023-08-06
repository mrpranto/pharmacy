<?php

namespace App\Services;

use App\Models\Role;
use App\Models\trait\FileHandler;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class UserServices extends BaseServices
{
    use FileHandler;

    /**
     * @param User $user
     */
    public function __construct(User $user, Role $role)
    {
        $this->model = $user;
        $this->role = $role;
    }

    /**
     * @return array[]
     */
    public function accessPermissions(): array
    {
        return [
            'permission' => [
                'create' => auth()->user()->can('app.user.create'),
                'edit' => auth()->user()->can('app.user.edit'),
                'show' => auth()->user()->can('app.user.show'),
                'delete' => auth()->user()->can('app.user.delete')
            ]
        ];
    }

    /**
     * @return LengthAwarePaginator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getUsers(): LengthAwarePaginator
    {
        return $this->model
            ->newQuery()
            ->with('role')
            ->when(request()->filled('search'), function ($q) {
                $q->where('name', 'like', '%' . request()->get('search') . '%')
                    ->orWhere('email', 'like', '%' . request()->get('search') . '%')
                    ->orWhere('phone_number', 'like', '%' . request()->get('search') . '%')
                    ->orWhereHas('role', function ($q) {
                        $q->where('name', 'like', '%' . request()->get('search') . '%');
                    });
            })
            ->where('id', '!=', 1)
            ->orderByDesc('id')
            ->paginate(request()->get('per_page') ?? pagination());
    }

    /**
     * @return Collection|array
     */
    public function getRoles(): Collection|array
    {
        return $this->role->newQuery()->select(['name', 'id'])->get();
    }

    /**
     * @param $request
     * @return $this
     */
    public function validateStore($request): static
    {
        $request->validate([
            'role_id' => 'required|numeric|exists:roles,id',
            'name' => 'required|string|max:191',
            'email' => 'nullable|email|max:191|unique:users,email',
            'phone_number' => 'required|string|max:20|unique:users,phone_number',
            'password' => 'required|string|min:8',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:1024'
        ]);

        return $this;
    }

    /**
     * @param $request
     * @return JsonResponse
     */
    public function store($request): JsonResponse
    {
        try {

            DB::transaction(function () use ($request) {
                $user = $this->model->newQuery()->create([
                    'role_id' => $request->role_id,
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                    'password' => $request->password,
                ]);

                if ($request->has('profile_picture')) {
                    $this->uploadProfilePicture($request->file('profile_picture'), $user);
                }
            });

            return response()->json(['success' => __t('user_create')]);

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    /**
     * @param $request
     * @param $id
     * @return $this
     */
    public function validateUpdate($request, $id): static
    {
        $request->validate([
            'role_id' => 'required|numeric|exists:roles,id',
            'name' => 'required|string|max:191',
            'email' => 'nullable|email|max:191|unique:users,email,' . $id,
            'phone_number' => 'required|string|max:20|unique:users,phone_number,' . $id,
            'password' => 'nullable|string|min:8',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:1024'
        ]);

        return $this;
    }

    /**
     * @param $request
     * @param $id
     * @return JsonResponse
     */
    public function update($request, $id): JsonResponse
    {
        try {

            DB::transaction(function () use ($request, $id) {

                $user = $this->model->newQuery()->where('id', $id)->first();

                $user->update([
                    'role_id' => $request->role_id,
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                    'password' => $request->password ?? $user->password,
                ]);

                if ($request->has('profile_picture')) {
                    $this->uploadProfilePicture($request->file('profile_picture'), $user);
                }
            });

            return response()->json(['success' => __t('user_update')]);

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    /**
     * @param $request
     * @param $user
     * @return void
     */
    public function uploadProfilePicture($profile_picture, $user): void
    {
        $this->deleteImage(optional($user->profilePicture)->path);

        $file_path = $this->uploadImage($profile_picture, User::PROFILE_PICTURE_TYPE);

        $user->profilePicture()->updateOrCreate([
            'type' => User::PROFILE_PICTURE_TYPE
        ], [
            'path' => $file_path
        ]);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        try {

            $this->model->newQuery()->where('id', $id)->delete();

            return response()->json(['success' => __t('user_delete')]);

        }catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage()]);
        }
    }
}

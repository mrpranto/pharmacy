<?php

namespace App\Services;

use App\Models\trait\FileHandler;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardServices extends BaseServices
{
    use FileHandler;

    /**
     * @param $request
     * @return $this
     */
    public function validateProfileUpdate($request): static
    {
        $request->validate([
            "name" => "required|string",
            "email" => "required|email|unique:users,email,".auth()->id(),
            "phone_number" => "required|numeric|unique:users,phone_number,".auth()->id(),
            "gender" => "required|in:male,female",
            "address" => "nullable|string",
            "profile_picture" => 'nullable|image|max:2048',
        ]);

        return $this;
    }


    /**
     * @param $request
     * @return RedirectResponse
     */
    public function updateProfile($request): RedirectResponse
    {
        try {

            DB::transaction(function () use ($request) {

                $user = Auth::user();

                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'gender' => $request->gender,
                    'address' => $request->address,
                ]);

                if ($request->has('profile_picture')) {
                    $this->uploadProfilePicture($request->file('profile_picture'), $user);
                }
            });

            return redirect()->back()->with('success', __t('profile_update'));

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    /**
     * @param $profile_picture
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
}

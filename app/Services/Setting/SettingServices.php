<?php

namespace App\Services\Setting;

use App\Models\Setting;
use App\Services\BaseServices;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FilesystemException;

class SettingServices extends BaseServices
{
    public function __construct(Setting $setting)
    {
        $this->model = $setting;
    }

    /**
     * @return mixed
     */
    public function getSetting(): mixed
    {
        $setting = $this->model
            ->newQuery()
            ->where('type', request('type'))
            ->first();

        if ($setting){

            if (request()->filled('general')){
                Cache::set('general_setting', $setting->settings_info);
            }

            return  $setting->settings_info;
        }

        return (object) [];
    }

    /**
     * @return RedirectResponse
     */
    public function storeSetting(): RedirectResponse
    {
        $settingData = request()->except('_token');

        foreach ($settingData['setting'] ?? [] as $key => $setting) {

            $existSetting = $this->model->newQuery()->where('type', $key)->first();

            if ($existSetting) {

                $existSetting->fill([
                    'type' => $key,
                    'settings_info' => $setting
                ])->save();

            } else {

                $this->model
                    ->fill([
                        'type' => $key,
                        'settings_info' => $setting
                    ])->save();
            }
        }

        Artisan::call('cache:clear');

        $settings = Setting::query()/*->where('type', 'general')*/->get();
        foreach ($settings as $setting){
            if ($setting->type == 'general'){
                Cache::set('general_setting', $setting->settings_info);
            }else{
                Cache::set($setting->type, $setting->settings_info);
            }
        }

        return redirect()->back()->with('success', 'Setting created successful.');
    }

    /**
     * @return RedirectResponse|void
     * @throws FilesystemException
     */
    public function backup()
    {
        try {
            if ($this->takeDatabaseBackup() === true){
                $files =  Storage::allFiles(config('backup.backup.name'));
                $file = $files[0];
                $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
                if ($disk->exists($file)) {
                    $fs = Storage::disk(config('backup.backup.destination.disks')[0])->getDriver();
                    $stream = $fs->readStream($file);
                    return \Response::stream(function () use ($stream) {
                        fpassthru($stream);
                    }, 200, [
                        "Content-Type" => $fs->mimeType($file),
                        "Content-Length" => $fs->fileSize($file),
                        "Content-disposition" => "attachment; filename=\"" . basename($file) . "\"",
                    ]);
                }else{
                    return redirect()->back()->with(['error' => 'No backup found.']);
                }
            }

        }catch (\Exception $exception){
            return redirect()->back()->with(['error' => $exception->getMessage()]);
        }
    }

    /**
     * @return bool|RedirectResponse
     */
    private function takeDatabaseBackup(): bool|RedirectResponse
    {
        try {

            $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
            $files =  Storage::allFiles(config('backup.backup.name'));
            if (is_array($files) && count($files) && $disk->exists($files[0])) {
                $disk->delete($files[0]);
            }
            Artisan::call('backup:run --only-db');

            return true;

        }catch (\Exception $exception){
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}

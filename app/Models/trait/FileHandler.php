<?php


namespace App\Models\trait;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait FileHandler
{
    protected $storage_prefix = 'public';

    /**
     * @param UploadedFile $file
     * @param $folder
     * @return string
     */
    public function storeFile(UploadedFile $file, $folder = 'avatar'): string
    {
        $name = Str::random(40) . "." . $file->getClientOriginalExtension();
        $file->storeAs("{$this->storage_prefix}/{$folder}", $name);
        return Storage::url($folder . '/' . $name);
    }

    /**
     * @param UploadedFile|null $uploadedFile
     * @param $folder
     * @return string|null
     */
    public function uploadImage(UploadedFile $uploadedFile = null, $folder = "logo"): ?string
    {
        if (is_null($uploadedFile))
            return null;
        $file = $this->saveImage($uploadedFile, $folder);
        if ($file->success)
            return Storage::url($file->path);
        return null;
    }

    /**
     * @param UploadedFile $file
     * @param $subdirectory
     * @return object
     */
    public function saveImage(UploadedFile $file, $subdirectory = 'logo'): object
    {
        $file_name = uniqid() . '.' . $file->getClientOriginalExtension();
        $file->storeAs($this->storage_prefix . '/' . $subdirectory, $file_name);
        return (object)["success" => true, "message" => "File has been uploaded successfully", "path" => $subdirectory . '/' . $file_name];
    }

    /**
     * @param string|null $path
     * @return bool
     */
    public function isFile(string $path = null): bool
    {
        return Storage::exists("{$this->storage_prefix}/{$path}");
    }

    /**
     * @param string|null $path
     * @return bool
     */
    public function deleteImage(string $path = null): bool
    {
        return $this->deleteFile($path);
    }

    /**
     * @param $path
     * @return array|string
     */
    public function removeStorage($path): array|string
    {
        return str_replace('/storage', '', $path);
    }

    /**
     * @param string|null $path
     * @return bool
     */
    public function deleteFile(string $path = null): bool
    {
        $path = $this->removeStorage($path);
        if ($this->isFile($path))
            return Storage::delete("{$this->storage_prefix}/{$path}");
        return false;
    }

    /**
     * @param array $paths
     * @return bool
     */
    public function deleteMultipleFile(array $paths): bool
    {
        foreach ($paths as $path) {
            $this->deleteFile($path);
        }

        return true;
    }

    /**
     * @param string|null $path
     * @return string|null
     */
    public function filePath(string $path = null): ?string
    {
        $path = $this->removeStorage($path);
        if ($this->isFile($path))
            return Storage::url("{$this->storage_prefix}/{$path}");
        return null;
    }

}

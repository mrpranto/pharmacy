<?php

namespace App\Models;

use App\Models\trait\BootTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory, BootTrait, BootTrait;

    const IMAGE_FOLDER = 'avatar';

    protected $fillable = [
        'type', 'path', 'created_by', 'updated_by'
    ];

    /**
     * @return mixed|string
     */
    public function getFullUrlAttribute(): mixed
    {
        if (in_array(config('filesystems.default'), ['local', 'public'])) {
            return request()->root() . $this->path;
        }
        return $this->path;
    }

    /**
     * @var string[]
     */
    protected $appends = ['full_url'];

    /**
     * @var string[]
     */
    protected $hidden = [
        'fileable_type', 'fileable_id'
    ];
}

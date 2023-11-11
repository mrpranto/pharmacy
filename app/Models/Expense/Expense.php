<?php

namespace App\Models\Expense;

use App\Models\File;
use App\Models\trait\BootTrait;
use App\Models\trait\CreatedByRelationship;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Expense extends Model
{
    use CreatedByRelationship, BootTrait;
    const EXPANSE_ATTACHMENT = 'expanse_attachment';

    protected $fillable = [
        'date', 'title', 'item_details', 'details', 'total_amount', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'item_details' => 'json'
    ];

    /**
     * @return MorphOne
     */
    public function expanseAttachment(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable')
            ->where('type', self::EXPANSE_ATTACHMENT);
    }
}

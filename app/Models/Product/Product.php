<?php

namespace App\Models\Product;

use App\Models\File;
use App\Models\Stock\Stock;
use App\Models\trait\ActiveScope;
use App\Models\trait\BootTrait;
use App\Models\trait\CreatedByRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, CreatedByRelationship, BootTrait, ActiveScope;

    protected $fillable = [
        'category_id', 'company_id', 'unit_id', 'barcode', 'name', 'purchase_type',
        'slug', 'variant_order', 'description', 'status', 'created_by', 'updated_by'
    ];

    const PURCHASE_TYPE_DIRECT_PRICE = '$';
    const PURCHASE_TYPE_PERCENTAGE = '%';

    const PRODUCT_PHOTO_TYPE = 'product_photo';

    /**
     * @var string[]
     */
    protected $with = ['productPhoto', 'category:id,name', 'company:id,name', 'unit:id,name,pack_size'];

    protected $appends = ['has_opening_stock'];

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    /**
     * @return MorphOne
     */
    public function productPhoto(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable')
            ->where('type', self::PRODUCT_PHOTO_TYPE);
    }

    /**
     * @return HasMany
     */
    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class, 'product_id', 'id')
            ->where('available_quantity', '>', 0);
    }

    /**
     * @return HasMany
     */
    public function attributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class, 'product_id', 'id')
            ->select(['product_id', 'key', 'value']);
    }


    /**
     * @return bool
     */
    public function getHasOpeningStockAttribute(): bool
    {
        $openingStock = $this->stocks()
            ->where('opening_stock_quantity' , '>', 0)
            ->count();

        if ($openingStock){
            return true;
        }
        return false;
    }
}

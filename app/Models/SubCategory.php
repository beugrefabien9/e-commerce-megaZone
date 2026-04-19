<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class SubCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'category_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subCategory) {
            if (empty($subCategory->slug)) {
                $subCategory->slug = Str::slug($subCategory->name);
            }
        });

        static::updating(function ($subCategory) {
            if ($subCategory->isDirty('name') && empty($subCategory->slug)) {
                $subCategory->slug = Str::slug($subCategory->name);
            }
        });
    }
}

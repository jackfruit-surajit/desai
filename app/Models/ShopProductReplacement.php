<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopProductReplacement extends Model
{
    protected $table = 'shop_product_replacements';

    protected $fillable = [
        'replacement_no',
        'shop_id',
        'driver_id',
        'warehouse_id',
        'replace_date',
        'total_products',
        'total_cases',
        'total_bottles',
        'product_id',
        'cases',
        'bottles',
        'replacement_product_id',
        'replacement_cases',
        'replacement_bottles',
        'reason',
        'status',
        'image',
        'notes',
        'created_at',
        'updated_at'
    ];

    /**
     * Relationship: The shop / customer returning the product.
     */
    public function shop()
    {
        return $this->belongsTo(Customer::class, 'shop_id');
    }

    /**
     * Relationship: The driver who collected the replacement from the shop.
     */
    public function driver()
    {
        return $this->belongsTo(Admin::class, 'driver_id');
    }

    /**
     * Relationship: The warehouse where stock movement / return is accounted.
     */
    public function warehouse()
    {
        return $this->belongsTo(Admin::class, 'warehouse_id');
    }

    /**
     * Relationship: The defective / old product returned by the shop.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Relationship: The fresh product given in replacement.
     */
    public function replacementProduct()
    {
        return $this->belongsTo(Product::class, 'replacement_product_id');
    }

    /**
     * Relationship: Multiple replacement items/details for this transaction.
     */
    public function details()
    {
        return $this->hasMany(ShopProductReplacementDetails::class, 'replacement_id');
    }

    /**
     * Alias for details().
     */
    public function items()
    {
        return $this->hasMany(ShopProductReplacementDetails::class, 'replacement_id');
    }
}

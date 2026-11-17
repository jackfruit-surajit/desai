<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopProductReplacementDetails extends Model
{
    protected $table = 'shop_product_replacement_details';

    protected $fillable = [
        'replacement_id',
        'shop_id',
        'driver_id',
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
     * Relationship: The parent replacement record.
     */
    public function replacement()
    {
        return $this->belongsTo(ShopProductReplacement::class, 'replacement_id');
    }

    /**
     * Relationship: The shop / customer.
     */
    public function shop()
    {
        return $this->belongsTo(Customer::class, 'shop_id');
    }

    /**
     * Relationship: The driver.
     */
    public function driver()
    {
        return $this->belongsTo(Admin::class, 'driver_id');
    }

    /**
     * Relationship: The defective / returned product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Relationship: The replacement product.
     */
    public function replacementProduct()
    {
        return $this->belongsTo(Product::class, 'replacement_product_id');
    }
}

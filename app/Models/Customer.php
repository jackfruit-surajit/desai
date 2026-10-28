<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';

    protected $fillable = [
        'name',
        'email',
        'mobile_no',
        'shop_name',
        'shop_photo',
        'gst_no',
        'latitude',
        'longitude',
        'area_id',
        'road_id',
        'address',
        'pan_no',
        'landmark',
        'state',
        'city',
        'pin_code',
        'status',
        'sequence',
        'created_by',
        'updated_at',
        'created_at'
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = !is_null($value) ? mb_strtoupper($value, 'UTF-8') : null;
    }

    public function setShopNameAttribute($value)
    {
        $this->attributes['shop_name'] = !is_null($value) ? mb_strtoupper($value, 'UTF-8') : null;
    }

    public function setGstNoAttribute($value)
    {
        $this->attributes['gst_no'] = !is_null($value) ? mb_strtoupper($value, 'UTF-8') : null;
    }

    public function setPanNoAttribute($value)
    {
        $this->attributes['pan_no'] = !is_null($value) ? mb_strtoupper($value, 'UTF-8') : null;
    }

    public function setStateAttribute($value)
    {
        $this->attributes['state'] = !is_null($value) ? mb_strtoupper($value, 'UTF-8') : null;
    }

    public function setCityAttribute($value)
    {
        $this->attributes['city'] = !is_null($value) ? mb_strtoupper($value, 'UTF-8') : null;
    }

    public function setLandmarkAttribute($value)
    {
        $this->attributes['landmark'] = !is_null($value) ? mb_strtoupper($value, 'UTF-8') : null;
    }

    public function setPinCodeAttribute($value)
    {
        $this->attributes['pin_code'] = !is_null($value) ? mb_strtoupper($value, 'UTF-8') : null;
    }

    public function setAddressAttribute($value)
    {
        $this->attributes['address'] = !is_null($value) ? mb_strtoupper($value, 'UTF-8') : null;
    }
}

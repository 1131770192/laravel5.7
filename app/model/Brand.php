<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
	protected $table = 'brand';
    protected $primaryKey = 'brand_id';
    // const CREATED_AT = 'addtime';
    public $timestamps = false;
}

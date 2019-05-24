<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	protected $table = 'category';
    protected $primaryKey = 'cate_id';
    const CREATED_AT = 'addtime';
    // public $timestamps = false;
}

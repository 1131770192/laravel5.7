<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
	protected $table = 'article';
    protected $primaryKey = 'article_id';
    const CREATED_AT = 'addtime';
}

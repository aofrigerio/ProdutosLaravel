<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImages extends Model{

	protected $fillable = ['product_id', 'images'];

	public function product() {
		return $this->belongsTo('App\Models\Product');
	}

}
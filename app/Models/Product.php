<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

	protected $fillable = ['name', 'description'];

	public function categories(){

		return $this->belongsToMany('App\Models\Category', 'product_categories');
	}

	public function images() {
		return $this->hasMany('App\Models\ProductImages');
	}

}


?>
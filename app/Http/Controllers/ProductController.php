<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImages;
use Validator;
use DB;


class ProductController extends Controller
{


	private $path = 'images/product';

	public function __contruct(){
		$this->middleware('auth');
	}


	public function index(){

		$products = Product::get();
		$categories = Category::get();
		$selected_cat = [];

		/*
		$data['products'] = $products;
		$data['categories'] = $categories;
		$data['selected_cat'] = $selected_cat;
		*/

		compact('products','categories','selected_cat'); //criar as variáveis na chamada view ao inves de usar $data

		return view('product.index', compact('products','categories','selected_cat'));

	}


	public function add()
	{
		$categories = Category::where('active', 1)->get();

		return view('product.add', compact('categories'));

	}


	public function save(Request $request)
	{

		$validator = Validator::make($request->all(),
			[
				'name' => 'required|min:10|max:255',
				'description' => 'required',
			]
		);

		//$images = $request->file('images');
		//dd($images);


		if(!$validator->fails()){

			$product = Product::create([
				'name' => $request->input('name'),
				'description' => $request->input('description')
			]);

			$images = $request->file('images');

			if($product){

				foreach ($images as $key => $row){
					if(!empty($row)){
						$fileName = time() . $key . '.' . $row->getClientOriginalExtension();
						$row->move($this->path, $fileName);
						$image = new ProductImages;
						$image->product_id = $product->id;
						$image->image = $fileName;
						$image->save();

					}
				}

				$product->categories()->sync($request->input('category'));

			}

		}
		else{
			dd($validator->errors());
		};
		return redirect()->route('product.index');
	}



	public function edit($id)
	{

		$product = Product::find($id);

		if(!empty($product)){

			$categories	= Category::get();
			$images = ProductImages::where('product_id', $product->id)->get();

			$selected_cat = array();


			foreach ($product->categories as $category) {
				$selected_cat[] = $category->pivot->category_id;
			}

			return view('product.edit', compact('product', 'categories', 'selected_cat', 'images'));

		}

		return redirect()->route('product.index');

	}


	public function update(Request $request, $id)
	{
		$images = $request->file('images');
		$category = $request->input('category');
		$product = Product::find($id);


		if(!empty($product)){
			if(!empty($images)){
				foreach ($images as $key => $row){
					if(!empty($row)){
						$fileName = time() . $key . '.' . $row->getClientOriginalExtension();

						$row->move($this->path, $fileName);
						$image = new ProductImages;
						$image->product_id = $product->id;
						$image->image = $fileName;
						$image->save();
					}
				}
			}


			if(!empty($category)){
				$product->categories()->sync($category);
			}

			$product->update([
				'name' => $request->input('name'),
				'description' => $request->input('description')
			]);

		}

		return redirect()->route('product.index');

	}


	public function delete($id)
	{

		$product = Product::find($id);

		if($product){
			$images = ProductImages::where('product_id',$product->id)->get();

			if(!empty($images)){
				foreach ($images as $row){
					if(file_exists($this->path . '/' . $row->image)){
						unlink($this->path . '/' . $row->image);
					}
				}
			}

		}

		$product->categories()->detach();
		$product->images()->delete();
		$result = $product->delete();

		return redirect()->route('product.index');

	}

	public function search(Request $request){

		$name = $request->input('name');
		$selected_cat = $request->input('category');
		$search = TRUE;

		$query = DB::table('products')
			->select('products.id', 'products.name', 'products.description')
			->where('products.active','=',1)
			->leftJoin('product_categories','products.id','=','product_categories.category_id','=','categories.id')
			->leftJoin('categories', 'product_categories.category_id','=','categories.id')
			->groupBy('products.id','products.name', 'products.description');

		if(!empty($name) && !empty($selected_cat))
		{			
			$query->where('products.name', 'like', '%' . $name . '%');
			$query->whereIn('categories.id', $selected_cat);
		}else if(!empty($name)){
			$query->where('products.name', 'like', '%' . $name . '%');
		}else if(!empty($selected_cat)){
			$query->whereIn('categories.id', $selected_cat);
		}

		$products = $query->get();
		$categories = Category::where('active',1)->get();

		if(empty($selected_cat)){
			$selected_cat = [];
		}

		return view('product.index', compact('products','categories','selected_cat','search'));

	}




}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImages;
use DB;
use Validador;


class CategoryController extends Controller
{

	private $path = 'images/category';


	public function index()
	{
		$categories = Category::paginate(10);
		return view('category.index', compact('categories'));
	}

	public function add()
	{
		return view('category.add');
	}

	public function save(Request $request)
	{

		if(!empty($request->file('image')) && $request->file('image')->isValid()){
			$fileName = time() . '.' . $request->file('image')->getClientOriginalExtension();
			$request->file('image')->move($this->path, $fileName);
		}


		$result = Category::create([
			'name' => $request->input('name'),
			'image' => $fileName
		]);

		return redirect()->route('category.index');

	}



	public function edit($id){

		$category = Category::find($id);

		if(!$category){
			return redirect()->route('category.index');
		}

		return view('category.edit', compact('category'));

	}

	public function delete($id)
	{
		$category = Category::find($id);

		if($category){
			$category->products()->detach();
			$result = $category->delete();
		}

		return redirect()->route('category.index');
	}


	public function search(Request $request)
	{

		$name = $request->input('name');
		$search = TRUE;

		if($name){
			$categories = Category::where('name', 'like', '%' . $name . '%')->get();
		}
			else{
				return redirect()->route('category.index');
			}

		return view('category.index', compact('categories','search'));
	}


}
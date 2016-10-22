<?php

class CategoryController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /category
	 *
	 * @return Response
	 */
	public function index()
	{
		$cats = Category::whereUserId(Auth::id())->get();//lists('title', 'id');

		return array('status'=> 'success', 'data' => $cats);
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /category/create
	 *
	 * @return Response
	 */
	public function create()
	{

	}

	/**
	 * Store a newly created resource in storage.
	 * POST /category
	 *
	 * @return Response
	 */
	public function store()
	{
		$inputs = Input::only('title');

		if(!isset($inputs))
			return array('status' => 'Error');

		if(isset($inputs['title']))
			$title = $inputs['title'];
		else
			return array('status' => 'Error');

		$cat = new Category;
		$cat->title = $title;
		$cat->user_id = Auth::id();
		$cat->save();

		return array('status' => 'success', 'data'=> $cat);
	}

	/**
	 * Display the specified resource.
	 * GET /category/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$cat = Category::with('transactions')->whereId($id)->get();
		return $cat;

	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /category/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit()
	{
		$id = Input::only('id');
		if(!isset($id)){
			return array('status' => 'Error');
		}
		if(isset($id['id']))
			$id = $id['id'];

		if(Category::whereId($id)->count() != 1){
			return array('status' => 'Wrong id');
		}

		$title = Input::only('title');
		if(!isset($title) ){
			return array('status' => 'Error');
		}

		if(isset($title['title']))
			$title = $title['title'];

		$cat = Category::whereId($id)->first();
		$cat->title = $title;
		$cat->save();

		return array('status' => 'success', 'data' => $cat);

	}

	/**
	 * Update the specified resource in storage.
	 * PUT /category/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /category/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy()
	{
		$id = Input::only('id');
		if(Category::whereId($id)->count() != 1){
			return array('status' => 'Wrong id');
		}
		
		return array('status' => 'success', 'data' => Category::whereId($id)->delete());
	}



	public function synch()
	{
		$cats = json_decode(Input::only('data')['data']);
		if(count($cats) > 0){
			$old_cats = Category::whereUserId(Auth::ID())->delete();
			foreach ($cats as $cat) {
				$nc = new Category;
				$nc->title = $cat->title;
				$nc->id = $cat->id;
				$nc->user_id = Auth::id();
				$nc->save();
			}
		}else{
			return array('status' => 'Error');
		}

		$new_cats = Category::whereUserId(Auth::id())->get();
		return array('status'=>'success', 'data'=> $new_cats);
	}

  public function transcat()
  {
    $cats = Category::with('transactions')->whereUserId(Auth::id())->get();
    return $cats;
  }
}
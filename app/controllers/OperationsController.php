<?php

class OperationsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /operations
	 *
	 * @return Response
	 */
	public function index()
	{
		$ops = Operation::whereUserId(Auth::id())->get();

		return array('status'=> 'success', 'data' => $ops);
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /operations/create
	 *
	 * @return Response
	 */
	public function create()
	{

	}

	/**
	 * Store a newly created resource in storage.
	 * POST /operations
	 *
	 * @return Response
	 */
	public function store()
	{
		$inputs = Input::only('sum', 'comment', 'category_id', 'tr_date');
		extract($inputs);

		if(!isset($sum) || !isset($comment) || !isset($category_id) || !isset($tr_date)){
			return array('status' => 'Error');
		}

		$user = Auth::user();

		$op = new Operation;
		$op->sum = $sum;
		
		$user->balance += $sum;
		$user->save();

		$op->comment = $comment;
		$op->category_id = $category_id;
		$op->tr_date = $tr_date;
		$op->user_id = Auth::id();
		$op->save();

		return array('status' => 'success', 'id' => $op->id);
	}

	/**
	 * Display the specified resource.
	 * GET /operations/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /operations/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /operations/{id}
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
	 * DELETE /operations/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

	public function synch()
	{
		$ops = json_decode(Input::only('data')['data']);
		if(count($ops) > 0){
			Operation::whereUserId(Auth::ID())->delete();
			foreach ($ops as $op) {
				$nop = new Operation;
				$nop->comment = $op->comment;
				$nop->sum = $op->sum;
				$nop->category_id = $op->category_id;
				$nop->tr_date = $op->tr_date;
				$nop->user_id = Auth::id();
				$nop->save();
			}
		}else{
			return array('status' => 'Error');
		}
		$new_ops = Operation::whereUserId(Auth::id())->get();
		return array('status'=>'success', 'data'=> $new_ops);
	}

}
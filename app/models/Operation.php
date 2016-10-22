<?php

class Operation extends \Eloquent {
	protected $fillable = array('id', 'category_id', 'comment', 'sum', 'user_id', 'tr_date');
  protected $hidden = array('user_id', 'created_at', 'updated_at');
  
  public function category(){
    return $this->belongsTo('Category', 'category_id');
  }
}
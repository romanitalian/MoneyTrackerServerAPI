<?php

class Category extends \Eloquent {
	protected $fillable = [];

  protected $hidden = ['created_at', 'updated_at', 'user_id'];

  protected $primaryKey = 'id';

  public function transactions(){
    return $this->hasMany('Operation', 'category_id');
  }
}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;




/**
 * RestaurantTime Model
 */
 
//class RestaurantTime extends Eloquent  {
class RestaurantTime extends Model  {
	public $timestamp =false;
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'restaurant_time';

    protected $fillable =	array('restaurant_id','week_day','open_time','close_time','created_at','updated_at');


} // end RestaurantTime class

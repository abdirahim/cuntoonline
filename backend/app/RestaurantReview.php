<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * RestaurantReview Model
 */
 
class RestaurantReview extends Model  {
	
	public $timestamp =false;
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'restaurant_reviews';
	
} // end RestaurantReview class

<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

/**
 * RestaurantMeal Model
 */
 
class RestaurantMeal extends Model  {
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'restaurant_meals';

/**
	* scope function
	*
	* @param $query 	as query object
	* 
	* @return query
	*/	
	public static function scopeOfPage($query){
        return $query->where('is_active',1);
    } //end scopeOfPage()

} // end RestaurantMeal class

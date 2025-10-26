<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;

/**
 * Restaurant Model
 */
 
//class Restaurant extends Eloquent  {
class Restaurant extends Model  {
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'restaurants';

	/**
	* scope function
	*
	* @param $query as query object
	* 
	* @return query
	*/	
	public function scopeOfPage($query)
    {
        return $query->where('is_active',1);
    } //end scopeOfPage()
	
	/**
	* function for get meals
	*
	* @param null
	* 
	* @return query
	*/	
	public function meals()
    {
        return $this->hasMany('App\RestaurantMeal','restaurant_id')->where('is_active',1);
    } //end meals()
     
	/**
	* function for get restaurant time
	*
	* @param null
	* 
	* @return query
	*/	
	public function restaurantTime(){
		$currentDay 	=	 date("l");
//        return $this->hasOne('App\RestaurantTime','restaurant_id')->where('week_day',constant($currentDay ));
     //   dd($currentDay);
//        return $this->hasOne('App\RestaurantTime','restaurant_id')->where('week_day',($currentDay ));
        return $this->hasOne('App\RestaurantTime','restaurant_id')->where('week_day',($currentDay ));

    } //end restaurantTime()
	
	/**
	* function for get review
	*
	* @param null
	* 
	* @return query
	*/	
	public function reviewRestaurant(){
        return $this->hasMany('App\RestaurantReview','restaurant_id')->where('is_display',1);
    } //end reviewRestaurant()
	
	/**
	* function for get avg rating
	*
	* @param null
	* 
	* @return query
	*/	
	public function avgRating(){
        return $this->hasMany('App\RestaurantReview','restaurant_id')->where('rating','<>','0');
    } //end avgRating()
	
    public static function getResult($search='',$categorySlug=''){
		$lat	=	Session::has('lat') ? Session::get('lat') : '';
		$lng	=	Session::has('lng') ? Session::get('lng') : '';
		
		if($lat != '' && $lng != ''){ // if $lng and $lat is not empty
			$DB		= Restaurant::with('restaurantTime','reviewRestaurant')->OfPage()->select(DB::raw("*,(POWER ((POWER( (69.1 * ( `lang` - ( ".$lng.") ) * cos( (".$lat.")/57.3)) , 2 ) + POWER( (69.1 * ( lat -  (".$lat.") )) , 2 )), .5)) AS distance"))->orderBy('is_recommended','DESC')->orderBy('distance','ASC');
		}else{
			$DB		= Restaurant::with('restaurantTime','reviewRestaurant')->OfPage()->orderBy('is_recommended','DESC');
		}
		if($search!=''){// list searched restaurants
			$restaurants 	=	$DB->where('name','like','%'.$search.'%');
		}else if($categorySlug != ''){// list selected cuisine restaurants
			$categoryId 	=	DropDown::where('slug',$categorySlug)->pluck('id');
			if($categoryId == ''){
				$categoryId = 0;
			}
			$restaurants 	=  $DB->whereRaw(DB::raw('FIND_IN_SET("'.$categoryId.'",cuisine)'));
		}else{// list all restaurants
			$restaurants	=  $DB;
		}
		$restaurantDetail	=	$restaurants->get();

		return $restaurantDetail;
		
	}//end getResult()
	
} // end Restaurant class

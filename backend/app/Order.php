<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

/**
 * Order Model
 */
 
class Order extends Model   {
	/**
	 * The database table used for orders.
	 *
	 * @var string
	 */

	protected $table = 'orders';

	/**
	* hasMany  function for bind OrderItem get meals
	*
	* @param null
	* 
	* @return query
	*/	
	public function orderItem(){
        return $this->hasMany('App\OrderItem','order_id');
    } //end orderItem()
	
	/**
	* hasMany  function for bind OrderItem get meals
	*
	* @param null
	* 
	* @return query
	*/	
	public function restaurantDetail(){
		return $this->belongsTo('App\Restaurant', 'restaurant_id');
    } //end orderItem()
    
} // end Order class

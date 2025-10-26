<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

	/**
	 * OrderItem Model
	 */
 
class OrderItem extends Model   {
	/**
	 * The database table used for orders.
	 *
	 * @var string
	 */

	protected $table = 'order_items';

} // end OrderItem class

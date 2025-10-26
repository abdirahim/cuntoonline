<?php 
use Illuminate\Database\Eloquent\Model;
/**
 * AdminSetting Model
 */
class AdminSetting extends Eloquent {

/**
 * The database table used by the model.
 *
 * @var string
 */
	protected $table = 'settings';
	
	protected $timestamp = false;

}// end AdminSetting class

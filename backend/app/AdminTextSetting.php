<?php 
use Illuminate\Database\Eloquent\Model;
/**
 * AdminTextSetting Model
 */
class AdminTextSetting extends Eloquent {

/**
 * The database table used by the model.
 *
 * @var string
 */
	protected $table = 'textsettings';
	
	public $timestamps = false;
	
	protected $fillable = array('key_value', 'value');

}// end AdminTextSetting class

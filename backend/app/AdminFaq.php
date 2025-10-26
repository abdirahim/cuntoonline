<?php 
use Illuminate\Database\Eloquent\Model;

/**
 * AdminFaq Model
 */
class AdminFaq extends Eloquent {

	
/**
 * The database table used by the model.
 */
 
	protected $table = 'faqs';
	
/**
* belongsTo function for bind AdminDropDown model  
*
* @param null
* 
* @return query
*/		
	public  function category(){
		return $this->belongsTo('AdminDropDown')->select('name','id');
	} //end category()
	
}// end AdminFaq class

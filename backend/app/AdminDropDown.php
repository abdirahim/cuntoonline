<?php
namespace App;
//use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model;

/**
 * AdminDropDown Model
 */
class AdminDropDown extends Model  {

	/**
	 * The database table used by the model.
	 *
	 */
 
 protected $table = 'dropdown_managers';
 
	/**
	* hasMany function  for bind AdminDropDownDescription model  
	*
	* @param null
	* 
	* @return query
	*/		
	public function description(){
		return $this->hasMany('AdminDropDownDescription','parent_id');
	}//end description()
 
	 /**
	* hasMany function for bind  Faq model 
	*
	* @param null
	* 
	* @return query
	*/	
	public  function faq(){ 
		return $this->hasMany('Faq','category_id');
	} //end faq()
}// end AdminDropDown class

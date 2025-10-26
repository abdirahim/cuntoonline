<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * CmsDescription Model
 */
class CmsDescription extends Model {

/**
 * The database table used by the model.
 *
 * @var string
 */
	protected $table = 'cms_page_descriptions';
	
/**
 * function for find result from database 
 *
 * @param null
 * 
 * @return array
 */		
	public static function showResult(){
		
		$currentLanguageId	=	Session::get('currentLanguageId');
		$result		=	 CmsDescription::select('source_col_name','source_col_description')->where('language_id' , $currentLanguageId);
		return $result;
	} //end showResult()
	
}// end CmsDescription class

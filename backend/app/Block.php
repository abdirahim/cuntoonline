<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Session;

/**
 * Block Model
 */
 
class Block extends Model   {
	
/**
 * The database table used by the model.
 *
 * @var string
 */
 
	protected $table = 'blocks';

/**
* hasMany bind function for bind BlockDescription model
*
* @param null
* 
* @return query
*/
	
	public function accordingLanguage() {
		$currentLanguageId	=	Session::get('currentLanguageId');
        return $this->hasMany('BlockDescription','parent_id')->select('description','parent_id')->where('language_id' , $currentLanguageId);
	} //end accordingLanguage()
	
/**
* scope function
*
* @param $query 	as query object
* @param $pageName 	as pageName
* 
* @return query
*/	
	public function scopeOfPage($query, $pageName) {
        return $query->where('page', $pageName)->where('is_active',1);
    } //end scopeOfPage()
	
/**
 * function for find result form database function
 *
 * @param $pageName 	as pageName
 * @param $fields 	as fields which need to select
 * 
 * @return array
 */	
	public static function getResult($pageName,$fields = array()){
	
		$currentLanguageId	=	Session::get('currentLanguageId');
		
		$blockResult		=	 Block::with('accordingLanguage')->OfPage($pageName)->select($fields)->get()->toArray();
		$response	=	array();
		foreach($blockResult as $key => $result){
			$blockSlug	=	$result['block'];
			$response[$blockSlug]	=	$result;
			if (isset($result['according_language']) && (is_array($result))) {
				if(isset($result['according_language'][0]) && !empty($result['according_language'][0])){ 
					$currentLanguageData	=	$result['according_language'][0];
					$response[$blockSlug]['description']	=	$currentLanguageData['description'];
					unset($result['according_language']);
				}
			}
		}
		
		return $response;
	} //end getResult()
	
} // end Block class

<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Session;

/**
 * Cms Model
 */

class Cms extends Model   {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */

	protected $table = 'cms_pages';

	/**
	* hasMany  function for bind CmsDescription and get result acoording language
	*
	* @param null
	*
	* @return query
	*/

	public function accordingLanguage()
    {
		$currentLanguageId	=	Session::get('currentLanguageId');
		//dd($currentLanguageId);
        return $this->hasOne('App\CmsDescription','parent_id')->select('source_col_description','parent_id','source_col_name')->where('language_id' , $currentLanguageId)->where('source_col_name','body');

    } //end accordingLanguage()

	/**
	 * function for find result from database
	 *
	 * @param $slug
	 * @param $fields as fields which need to select
	 *
	 * @return array
	 */

	public static function getResult($slug=''){
		$result		=	 Cms::with('accordingLanguage')
							->select('*')
							->where('slug',$slug)
							->first();

		return $result;
	} //end getResult()

} // end Cms class

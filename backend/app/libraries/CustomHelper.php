<?php
	/**
	 * Custom Helper
	 *
	 * Add your methods in the class below
	 */
	namespace App\Libraries;
    use Illuminate\Support\Facades\DB;
class CustomHelper {
	/**
	 * Function for check url format
	 *
	 * @param $url as url
	 *
	 * @return url. 
	 */	
	public static function http_check($url) {
		$return = $url;
		if ((!(substr($url, 0, 7) == 'http://')) && (!(substr($url, 0, 8) == 'https://'))) { $return = 'http://' . $url; }
		return $return;
	}//end http_check()

	

	/**
	 * Function for global xss clean
	 *
	 * @param null
	 *
	 * @return array. 
	 */		
	public static function globalXssClean()
	{
		// Recursive cleaning for array [] inputs, not just strings.
		$sanitized = static::arrayStripTags(Input::get());
		Input::merge($sanitized);
	}// end globalXssClean()

	/**
	 * Function for strip and trime data
	 *
	 * @param array
	 *
	 * @return array. 
	 */	
	public static function arrayStripTags($array)
	{
		$result = array();
	 
		foreach ($array as $key => $value) {
			// Don't allow tags on key either, maybe useful for dynamic forms.
			$key = strip_tags($key,ALLOWED_TAGS_XSS);
	 
			// If the value is an array, we will just recurse back into the
			// function to keep stripping the tags out of the array,
			// otherwise we will set the stripped value.
			if (is_array($value)) {
				$result[$key] = static::arrayStripTags($value);
			} else {
				// I am using strip_tags(), you may use htmlentities(),
				// also I am doing trim() here, you may remove it, if you wish.
				$result[$key] = trim(strip_tags($value,ALLOWED_TAGS_XSS));
			}
		}
	 
		return $result;
	}// end arrayStripTags()
	
	/* Function to generate thumbnails from video */
	public static function generateThumbnail($source, $target, $width='', $height=''){
		$commandString 	= FFMPEG_CONVERT_COMMAND.'ffmpeg -i '.$source.' -f mjpeg -ss 00:00:01 -vf scale='.$width.':'.$height.' '.$target;
		$command 		= exec($commandString);
	}
	
	/**
	* Function for check mobile request
	*
	* @return filetype. 
	*/
	public static function isMobile() {
		return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	}// end isMobile()
	
	/**
	* Function for traslate select box values
	*
	* @return filetype. 
	*/
	public static function traslateList($data=array()) {
		$dataNew	=	array();
		foreach($data as $key=> $value){
		$value=	trim($value);
			$dataNew[$key]	= trans("messages.$value");	
		}

		return $dataNew;
	}// end isMobile()
	
	
		/**
	* Function for number format
	*
	* $number as price to be formatted
	* 
	* @return formated number . 
	*/
	
	public static function  numberFormat($number =''){
		$result='';
		if(is_numeric($number)){
			$result		=	 number_format($number,2);
		}
		return $result;
	}//end professionalPortfolioList()
	

	/**
	* Function for get cuisine name 
	*
	* $id as id of cuisne 
	* 
	* @return cuisne name . 
	*/
	
	public static function  getCuisineName($id=''){

//		$name	=	 DB::table('dropdown_managers')->where('dropdown_type','cuisine')->whereIn('id',$id)->lists('name');
        $name	=	 DB::table('dropdown_managers')->where('dropdown_type','cuisine')->whereIn('id',$id)->pluck('name')->toArray();



        return $name;
	}//end professionalPortfolioList()
	
	
	
} // end CustomHelper

<?php

/**
 * Base Controller
 *
 * Add your methods in the class below
 *
 * This is the base controller called everytime on every request
 */

namespace App\Http\Controllers;
use Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Auth;
use Session;
use Response;
use Cache;
use Mail;
use Illuminate\Support\Str;
use App\Restaurant;
use Illuminate\Support\Facades\Config;


class BaseController extends Controller
{
    public function __construct()
    {
		// echo date('d-m-Y H:i:s',time());
        /* For set default language id*/
        if (!Session::has('currentLanguageId')) {

         // Session::put('currentLanguageId', Config::get('default_language.language_code'));
            Session::put('currentLanguageId', 1);

            //dd(Session::get('currentLanguageId'));

        }

        if (!Cache::has('languages')) {
//            $languages = DB::table('languages')->where('active', 1)->lists('title', 'lang_code');
            $languages = DB::table('languages')->where('active', 1)->pluck('title', 'lang_code');

            Cache::forever('languages', $languages);

//            $languageDetails = DB::table('languages')->where('active', 1)->lists('id', 'lang_code');
            $languageDetails = DB::table('languages')->where('active', 1)->pluck('id', 'lang_code');

            Cache::forever('languageDetails', $languageDetails);
        }

        /* For set meta data */
        if (Request::segment(1) != 'admin') {

            $seo_page_file_path = Request::segment(1);
            if ($seo_page_file_path == 'pages') {
                $pagePath = Request::segment(2);
                $seoData  = DB::table('cms_pages')->select('meta_title', 'meta_description', 'meta_keywords')->where('slug', $pagePath)->first();

                if (!empty($seoData)) {
                    $title           = $seoData->meta_title;
                    $metaKeywords    = $seoData->meta_keywords;
                    $metaDescription = $seoData->meta_description;
                } else {
                    $title           = config("constants.Site_title");
                    $metaKeywords    = Config::get("constants.Site_meta_keywords");
                    $metaDescription = Config::get("constants.Site_meta_description");
                }
            } else {
				$title           = config("constants.Site_title");
				$metaKeywords    = config("constants.Site_meta_keywords");
				$metaDescription = config("constants.Site_meta_description");
            }

            View::share('pageTitle', $title);
            View::share('metaKeywords', $metaKeywords);
            View::share('metaDescription', $metaDescription);
            /* For set meta data */
        }

    } // end function __construct()

    /**
     * Setup the layout used by the controller.
     *
     * @return layout
     */
    protected function setupLayout()
    {
        if (Request::segment(1) != 'admin') {

        }
        if (!is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }
    } //end setupLayout()

    /**
     * Function to make slug according model from any certain field
     *
     * @param title     as value of field
     * @param modelName as section model name
     * @param limit 	as limit of characters
     *
     * @return string
     */
    public function getSlug($title, $modelName, $limit = 30)
    {
        $slug      = substr(Str::slug($title), 0, $limit);
//        $model     = new $modelName();
        $model     = new Restaurant();
        $slugCount = count($model->whereRaw("slug REGEXP '^{$slug}(-[0-9]*)?$'")->get());
        return ($slugCount > 0) ? $this->getSlug("{$slug}-{$slugCount}", $modelName) : $slug;
    } //end getSlug()

    /**
     * Function to make slug without model name from any certain field
     *
     * @param title     as value of field
     * @param $fieldName as field name
     * @param tableName as table name
     * @param limit 	as limit of characters
     *
     * @return string
     */
    public function getSlugWithoutModel($title, $fieldName = '', $tableName, $limit = 30)
    {
        $slug      = substr(Str::slug($title), 0, $limit);
        $slug      = Str::slug($title);
        $DB        = DB::table($tableName);
        $slugCount = count($DB->whereRaw("$fieldName REGEXP '^{$slug}(-[0-9]*)?$'")->get());
        return ($slugCount > 0) ? $this->getSlugWithoutModel("{$slug}-{$slugCount}", $fieldName, $tableName) : $slug;
    } //end getSlugWithoutModel()

    /**
     * Function to search result in database
     *
     * @param data  as form data array
     *
     * @return query string
     */
    public function search($data)
    {
        unset($data['display']);
        unset($data['_token']);
        $ret = '';
        if (!empty($data)) {
            foreach ($data as $fieldName => $fieldValue) {
                $ret .= "where('$fieldName', 'LIKE',  '%' . $fieldValue . '%')";
            }
            return $ret;
        }
    } //end search()

    /**
     * Function to send email from website
     *
     * @param string $to            as to address
     * @param string $fullName      as full name of receiver
     * @param string $subject       as subject
     * @param string $messageBody   as message body
     *
     * @return void
     */
    public function sendMail($to, $fullName, $subject, $messageBody, $from = '', $files = false, $path = '', $attachmentName = '')
    {

        //var_dump($to, $fullName, $subject, $messageBody, $from = '', $files = false, $path = '', $attachmentName = '');exit();
        $mailContent = array('to: ' =>$to, 'fullName ' =>$fullName, 'subject' => $subject,
            'messageBody'=>$messageBody, 'from'=>$from, 'files'=>$files, 'path'=>$path,'attachmentName'=> $attachmentName);
            \Log::error("Email debugging .", $mailContent);

        $data                   = array();
        $data['to']             = $to;
//        $data['from']           = (!empty($from) ? $from : Config::get("Site.email"));
        $data['from']           = (!empty($from) ? $from : Config::get("constants.Site_email"));
        $data['fullName']       = $fullName;
        $data['subject']        = $subject;
        $data['filepath']       = $path;
        $data['attachmentName'] = $attachmentName;
		(string )View::make('emails.template', array('messageBody'=> $messageBody));
        if ($files === false) {
          //  var_dump('to '.$data['to']. 'fullname'.$data['fullName'].' from'.$data['from']. 'subject'.$data['subject'].' messageBody:'.$messageBody);exit;

            Mail::send('emails.template', array(
                'messageBody' => $messageBody
            ), function($message) use ($data)
            {
                $message->to($data['to'], $data['fullName'])->from($data['from'])->subject($data['subject']);

            });
        } else {
            if ($attachmentName != '') {
              //  var_dump('bbbbbb');exit;
                Mail::send('emails.template', array(
                    'messageBody' => $messageBody
                ), function($message) use ($data)
                {
                    $message->to($data['to'], $data['fullName'])->from($data['from'])->subject($data['subject'])->attach($data['filepath'], array(
                        'as' => $data['attachmentName']
                    ));
                });
            } else {
              //  var_dump('to '.$data['to']. 'fullname'.$data['fullName'].' from'.$data['from']. 'subject'.$data['subject']);exit;
                Mail::send('emails.template', array('messageBody' => $messageBody), function($message) use ($data) {
                    $message->to($data['to'], $data['fullName'])->from($data['from'])->subject($data['subject'])->attach($data['filepath']);
                });
            }
        }

        DB::table('email_logs')->insert(array(
            'email_to' => $data['to'],
            'email_from' => $data['from'],
            'subject' => $data['subject'],
            'message' => $messageBody,
            'created_at' => DB::raw('NOW()')
        ));
    } //end sendMail()

    /* Function to delete file
     *
     *@param $mainPath and $fileName
     *
     */
    public function deleteFileRecursive($mainPath, $fileName)
    {
        $commandString = exec('find ' . $mainPath . ' -type f -name ' . $fileName . '.* -exec rm -rf {} \;');
    } // end deleteFileRecursive()

    /**
     * Function to upload images by ckeditor
     *
     * @param null
     *
     * @return status
     */

    public function uploder()
    {
        require_once('upload_class.php');

        if (isset($_GET['CKEditorFuncNum'])) {
            // where you have put class.upload.php

            $msg      = ''; // Will be returned empty if no problems
            $callback = ($_GET['CKEditorFuncNum']); // Tells CKeditor which function you are executing
            if ($_FILES['upload']['type'] == 'image/jpeg' || $_FILES['upload']['type'] == 'image/jpg' || $_FILES['upload']['type'] == 'image/gif' || $_FILES['upload']['type'] == 'image/png') {

                $handle = new upload($_FILES['upload']); // Create a new upload object
                if ($handle->uploaded) {
                    $handle->image_resize = false;
                    $handle->process(CK_EDITOR_ROOT_PATH); // directory for the uploaded image
                    $image_url = CK_EDITOR_URL . $handle->file_dst_name; // URL for the uploaded image
                    if ($handle->processed) {
                        $handle->clean();
                    } else {
                        $msg = 'error : ' . $handle->error;
                    }
                }
            } else {
                $msg = 'error : Please select image file';
            }
            $output = '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction(' . $callback . ', "' . $image_url . '","' . $msg . '");</script>';
            echo $output;
            exit;
        } else {
            $this->redirect('/');
        }
    } // end uploder()

}// end BaseController class

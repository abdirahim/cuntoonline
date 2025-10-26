<?php

/**
 * Language Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/language
 */

class LanguageController extends BaseController
{
    
    /**
     * Function for display list of all languages
     *
     * @param null
     *
     * @return view page. 
     */
    
    public function listLanguage()
    {
        ### breadcrumbs Start ###
        Breadcrumb::addBreadcrumb('Dashboard', URL::to('admin/dashboard'));
        Breadcrumb::addBreadcrumb('language');
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        $DB = AdminLanguage::query();
        
        $searchVariable = array();
        $inputGet       = Input::get();
        
        if (Input::get() && isset($inputGet['display'])) {
            $search     = true;
            $searchData = Input::get();
            unset($searchData['display']);
            unset($searchData['_token']);
            foreach ($searchData as $field_name => $fieldValue) {
                if (!empty($fieldValue)) {
                    $DB->where("$field_name", 'like', '%' . $fieldValue . '%');
                    $searchVariable = array_merge($searchVariable, array(
                        $field_name => $fieldValue
                    ));
                }
            }
        }
        
        $default_lang = AdminSetting::where('key', 'default_language.language_code')->pluck('value');
        
        $sortBy = (Input::get('sortBy')) ? Input::get('sortBy') : 'created';
        $order  = (Input::get('order')) ? Input::get('order') : 'DESC';
        
        $result = $DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
        
        return View::make('admin.languagemanager.index', compact('sortBy', 'order', 'breadcrumbs', 'result', 'searchVariable', 'default_lang'));
    } //end listLanguage()
    
    /**
     * Function for display add languages page
     *
     * @param null
     *
     * @return view page. 
     */
    
    public function addLanguage()
    {
        ### breadcrumbs Start ###
        Breadcrumb::addBreadcrumb('Dashboard', URL::to('admin/dashboard'));
        Breadcrumb::addBreadcrumb('language', URL::to('admin/language'));
        Breadcrumb::addBreadcrumb('Add Language');
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        return View::make('admin.languagemanager.add', compact('breadcrumbs'));
    } //end addLanguage()
    
    
    /**
     * Function for save added languages
     *
     * @param null
     *
     * @return view page. 
     */
    
    public function saveLanguage()
    {  
        $formData = Input::all();
        
        if (!empty($formData)) {
            $validator = Validator::make(Input::all(), array(
                'title' => 'required',
                'languagecode' => 'required',
                'foldercode' => 'required'
            ));
        }
        
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        } else {
            DB::table('languages')->insert(array(
                'title' => Input::get('title'),
                'lang_code' => Input::get('languagecode'),
                'folder_code' => Input::get('foldercode'),
                'created' => DB::raw('NOW()')
            ));
            
            Session::flash('flash_notice', trans("messages.language_manager.add_msg"));
            return Redirect::to('admin/language');
            
        }
    } //end saveLanguage()
    
    /**
     * Function for update active/inactive status
     *
     * @param $Id and $status 
     *
     * @return view page. 
     */
    
    public function updateLanguageStatus($Id = 0, $status = 0)
    { 
        DB::table('languages')->where('id', '=', $Id)->update(array(
            'active' => $status
        ));
        Session::flash('flash_notice', trans("messages.language_manager.status_msg"));
        return Redirect::to('admin/language');
        
    } //end updateLanguageStatus()
    
    /**
     * Function for delete language
     *
     * @param $Id as language id
     *
     * @return view page. 
     */
    
    public function deleteLanguage($Id = 0)
    {
        if ($Id) {
            $result = DB::table('languages')->where('id', '=', $Id)->delete();
        }
        Session::flash('flash_notice', trans("messages.language_manager.delete_msg"));
        return Redirect::to('admin/language');
    } //end deleteLanguage()
    
    /**
     * Function for set defaultlanguage
     *
     * @param $language_id as language id
     * $name as title
     * $folder_code as folder code 
     *
     * @return view page. 
     */
    
    public function updateDefaultLanguage($language_id = 0, $name = 0, $folder_code = 0)
    {
        ### delete existing record ###
        $obj = AdminSetting::where('key', 'default_language.language_code')->delete();
        $obj = AdminSetting::where('key', 'default_language.name')->delete();
        $obj = AdminSetting::where('key', 'default_language.folder_code')->delete();
        
        ### insert into settings table ###
        
        $languageDataArray = array(
            '0' => array(
                'key' => 'default_language.language_code',
                'value' => $language_id,
                'title' => 'Default language for front',
                'input_type' => 'text',
                'editable' => '1',
                'created_at' => DB::raw('NOW()')
            ),
            '1' => array(
                'key' => 'default_language.name',
                'value' => $name,
                'title' => 'Default language name',
                'input_type' => 'text',
                'editable' => '1',
                'created_at' => DB::raw('NOW()')
            ),
            '2' => array(
                'key' => 'default_language.folder_code',
                'value' => $folder_code,
                'title' => 'Default language folder code',
                'input_type' => 'text',
                'editable' => '1',
                'created_at' => DB::raw('NOW()')
            )
        );
        
        foreach ($languageDataArray as $value) {
            DB::table('settings')->insert($value);
        }
        
        ### write into file ###
        
        //$this->settingFileWrite();
        
        Session::put('language_id', $language_id);
        
        Session::flash('flash_notice', trans("messages.language_manager.mark_default_msg"));
        return Redirect::to('admin/language');
    } //end updateDefaultLanguage()
    
    /**
     * function for write file on update and create
     *
     *@param null
     *
     * @return void
     */
    
    public function settingFileWrite()
    { 
        $DB   = AdminSetting::query();
        $list = $DB->orderBy('key', 'ASC')->get(array(
            'key',
            'value'
        ))->toArray();
        
        $file        = SETTING_FILE_PATH;
        $settingfile = '<?php ' . "\n";
        foreach ($list as $value) {
            $val = str_replace('"', "'", $value['value']);
            $settingfile .= 'Config::set("' . $value['key'] . '", "' . $val . '");' . "\n";
        }
        $bytes_written = File::put($file, $settingfile);
        if ($bytes_written === false) {
            die("Error writing to file");
        }
        
    } //end settingFileWrite()
    
} //end LanguageController

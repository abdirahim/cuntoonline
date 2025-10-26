<?php

/**
 * text Settings Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/textsetting
 */

class TextSettingController extends BaseController
{
    
    /**
     * function for list all settings
     *
     * @param  null
     * 
     * @return view page
     */
    public function textList()
    {
		
        ### breadcrumbs Start ###
        Breadcrumb::addBreadcrumb('Dashboard', URL::to('admin/dashboard'));
        Breadcrumb::addBreadcrumb('Text Setting');
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        $DB = AdminTextSetting::query();
        
        $searchVariable = array();
        $inputGet       = Input::get();
        
        if ((Input::get() && isset($inputGet['display'])) || isset($inputGet['page'])) {
            $searchData = Input::get();
            unset($searchData['display']);
            unset($searchData['_token']);
            
            if (isset($searchData['page'])) {
                unset($searchData['page']);
            }
            
            foreach ($searchData as $fieldName => $fieldValue) {
                
                if (!empty($fieldValue)) {
                    if ($fieldName == 'module') {
                        $DB->where("key_value", 'like', '%' . $fieldValue . '%');
                    } else {
                        $DB->where("$fieldName", 'like', '%' . $fieldValue . '%');
                    }
                    $searchVariable = array_merge($searchVariable, array(
                        $fieldName => $fieldValue
                    ));
                }
            }
        }
        
        $sortBy = (Input::get('sortBy')) ? Input::get('sortBy') : 'updated';
        $order  = (Input::get('order')) ? Input::get('order') : 'DESC';
        
        $result = $DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
        
        return View::make('admin.textsetting.index', compact('sortBy', 'order', 'breadcrumbs', 'result', 'searchVariable'));
    } // end listSetting()
    
    /**
     * function for display add text page  
     *
     * @param  null
     * 
     * @return view page
     */
    
    public function addText()
    {
        
        ### breadcrumbs Start ###
        Breadcrumb::addBreadcrumb('Dashboard', URL::to('admin/dashboard'));
        Breadcrumb::addBreadcrumb('Text Setting', URL::to('admin/text-setting'));
        Breadcrumb::addBreadcrumb('Add Text');
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        return View::make('admin.textsetting.add', compact('breadcrumbs'));
    } // end addText()
    
    /**
     * function for save added text
     *
     * @param  null
     * 
     * @return view page
     */
    
    public function saveText()
    {
        
        $formData = Input::all();
        
        if (!empty($formData)) {
            $validator = Validator::make(Input::all(), array(
                'key' => 'required',
                'value' => 'required'
                
            ));
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            } else {
                
                $obj            = new AdminTextSetting;
                $obj->key_value = Input::get('key');
                $obj->value     = Input::get('value');
                $obj->created   = DB::raw('NOW()');
                $obj->updated   = DB::raw('NOW()');
                
                $obj->save();
            }
            $this->settingFileWrite();
            
            Session::flash('flash_notice', trans("messages.settings.text_add_msg"));
            return Redirect::to('admin/text-setting');
            
        }
    } //end saveText()
    
    
    /**
     * function for display edit text page 
     *
     * @param  $Id as text id 
     * 
     * @return view page
     */
    
    public function editText($Id = 0)
    {
        
        $result = AdminTextSetting::where('id', $Id)->first();
        
        ### breadcrumbs Start ###
        Breadcrumb::addBreadcrumb('Dashboard', URL::to('admin/dashboard'));
        Breadcrumb::addBreadcrumb('Text Setting', URL::to('admin/text-setting'));
        Breadcrumb::addBreadcrumb('Edit Text');
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        return View::make('admin.textsetting.edit', compact('breadcrumbs', 'result'));
    } //end editText()
    
    
    /**
     * function for update text
     *
     * @param $Id as text id
     * 
     * @return view page
     */
    
    public function updateText($Id = 0)
    {
        
        $formData = Input::all();
        
        if (!empty($formData)) {
            $validator = Validator::make(Input::all(), array(
                //'key'                => 'required',
                'value' => 'required'
            ));
            
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            } else {
                
                $obj          = AdminTextSetting::find($Id);
                //$obj->key_value     = Input::get('key');
                $obj->value   = Input::get('value');
                $obj->updated = DB::raw('NOW()');
                $obj->save();
            }
            $this->settingFileWrite();
        }
        
        Session::flash('flash_notice', trans("messages.settings.text_edit_msg"));
        return Redirect::to('admin/text-setting');
        
    } //end updateText()
    
    /**
     * function for delete text
     *
     * @param $Id as text id
     * 
     * @return view page
     */
    
    public function deleteText($Id = 0)
    {
        if ($Id) {
            $result = AdminTextSetting::where('id', $Id)->delete();
        }
        Session::flash('flash_notice', trans("messages.settings.text_delete_msg"));
        return Redirect::to('admin/text-setting');
    } //end deleteText()
    
    /**
     * Function for write file on create and update text  or message 
     *
     * @param null
     *
     * @return void. 
     */
    public function settingFileWrite()
    {
        
        $DB   = AdminTextSetting::query();
        $list = $DB->get()->toArray();
        
        $settingfile = '<?php return array(';
        
        foreach ($list as $value) {
            $settingfile .= '"' . $value['key_value'] . '"=>"' . $value['value'] . '",' . "\n";
        }
        
        $settingfile .= ');';
        
        $file = ROOT . DS . 'app' . DS . 'lang' . DS . 'en' . DS . 'messages.php';
        
        $bytes_written = File::put($file, $settingfile);
        
        if ($bytes_written === false) {
            die("Error writing to file");
        }
        
    } // end settingFileWrite()    
    
} //end TextSettingController class

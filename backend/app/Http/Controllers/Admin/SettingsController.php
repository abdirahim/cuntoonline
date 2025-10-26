<?php

/**
 * Settings Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/settings
 */

class SettingsController extends BaseController
{
    
    /**
     * function for list all settings
     *
     * @param  null
     * 
     * @return view page
     */
    public function listSetting()
    {
        
        ### breadcrumbs Start ###
        Breadcrumb::addBreadcrumb('Dashboard', URL::to('admin/dashboard'));
        Breadcrumb::addBreadcrumb('App Setting');
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        $DB             = AdminSetting::query();
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
                    $DB->where("$fieldName", 'like', '%' . $fieldValue . '%');
                    $searchVariable = array_merge($searchVariable, array(
                        $fieldName => $fieldValue
                    ));
                }
            }
        }
        $sortBy = (Input::get('sortBy')) ? Input::get('sortBy') : 'updated_at';
        $order  = (Input::get('order')) ? Input::get('order') : 'DESC';
        
        
        $result = $DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
        
        return View::make('admin.settings.index', compact('breadcrumbs', 'result', 'searchVariable', 'sortBy', 'order'));
    } // end listSetting()
    
    /**
     * prefix function
     *
     * @param $prefix as prefix
     * 
     * @return void
     */
    public function prefix($prefix = null)
    {

        
        ### breadcrumbs Start ###
        Breadcrumb::addBreadcrumb('Dashboard', URL::to('admin/dashboard'));
        
        //Convert the string into camel case and remove whitespaces and underscore
        $prefix_camel = ucwords(preg_replace("/[\s_]/", " ", $prefix));
        
        Breadcrumb::addBreadcrumb($prefix_camel);
        
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        $result = AdminSetting::where('key_value', 'like', $prefix . '%')->orderBy('weight', 'ASC')->get()->toArray();
        
        return View::make('admin.settings.prefix', compact('breadcrumbs', 'result', 'prefix', 'prefix_camel'));
        
    } // end prefix()
    
    /**
     * update prefix function
     *
     * @param $prefix as prefix
     * 
     * @return void
     */
    
    public function updatePrefix($prefix = null)
    {
        $allData = Input::all();  
        if (!empty($allData)) {
            
            if (!empty($allData['Setting'])) {
                foreach ($allData['Setting'] as $key => $value) {
					
                    if (!empty($value['id']) && !empty($value['key_value'])) {

                        if ($value['type'] == 'checkbox') {
                            $val = (isset($value['value'])) ? 1 : 0;
                        } else {
							
                            $val = (isset($value['value'])) ? $value['value'] : '';
                        }
                        
                        AdminSetting::where('id', $value['id'])->update(array(
                            'key_value' => $value['key_value'],
                            'value' => $val
                        ));
                    }
                }
            }
        }
        $this->settingFileWrite();
        Session::flash('flash_notice', 'Settings updated successfully.');
        return Redirect::intended('admin/settings/prefix/' . $prefix);
    } //updatePrefix()
    
    /**
     * function add new settings view page
     *
     *@param null
     * @return void
     */
    public function addSetting()
    {
        
        ### breadcrumbs Start ###
        Breadcrumb::addBreadcrumb('Dashboard', URL::to('admin/dashboard'));
        Breadcrumb::addBreadcrumb('Add setting');
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        return View::make('admin.settings.add', compact('breadcrumbs'));
    } //end addSetting()
    
    /**
     * function for save added new settings
     *
     *@param null
     *
     * @return void
     */
    public function saveSetting()
    {
        $validator = Validator::make(Input::all(), array(
            'title' => 'required',
            'key' => 'required',
            'value' => 'required',
            'input_type' => 'required'
        ));
        if ($validator->fails()) {
            return Redirect::to('admin/settings/add-setting')->withErrors($validator)->withInput();
        } else {
            
            $obj = new AdminSetting;
            
            $obj->title      = Input::get('title');
            $obj->key_value  = Input::get('key');
            $obj->value      = Input::get('value');
            $obj->input_type = Input::get('input_type');
            $obj->editable   = Input::get('editable') ? Input::get('editable') : 0;
            
            $obj->save();
        }
        
        $this->settingFileWrite();
        
        Session::flash('flash_notice', 'Setting added successfully.');
        return Redirect::intended('admin/settings');
    } //end saveSetting()
    
    /**
     * function edit settings view page
     *
     *@param $Id as Id
     *
     * @return void
     */
    public function editSetting($Id)
    {
        ### breadcrumbs Start ###
        Breadcrumb::addBreadcrumb('Dashboard', URL::to('admin/dashboard'));
        Breadcrumb::addBreadcrumb('Edit setting');
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        $result = AdminSetting::find($Id);
        
        return View::make('admin.settings.edit', compact('breadcrumbs', 'result'));
    } //end editSetting()
    
    /**
     * function for update setting
     *
     * @param $Id as Id
     *
     * @return void
     */
    public function updateSetting($Id)
    {
        $validator = Validator::make(Input::all(), array(
            'title' => 'required',
            'key' => 'required',
            'value' => 'required',
            'input_type' => 'required'
        ));
        if ($validator->fails()) {
            return Redirect::to('admin/settings/edit-setting/' . $Id)->withErrors($validator)->withInput();
        } else {
            $obj = AdminSetting::find($Id);
            
            $obj->title      = Input::get('title');
            $obj->key_value  = Input::get('key');
            $obj->value      = Input::get('value');
            $obj->input_type = Input::get('input_type');
            $obj->editable   = Input::get('editable');
            $obj->save();
        }
        $this->settingFileWrite();
        Session::flash('flash_notice', 'Setting updated successfully.');
        return Redirect::intended('admin/settings');
    } //end updateSetting()
    
    /**
     * function for delete setting
     *
     * @param $Id as Id
     *
     * @return void
     */
    public function deleteSetting($Id = 0)
    {
        if ($Id) {
            $obj = AdminSetting::find($Id);
            $obj->delete();
            Session::flash('flash_notice', 'Setting deleted successfully.');
        }
        $this->settingFileWrite();
        return Redirect::intended('admin/settings');
    } //end deleteSetting()
    
    /**
     * function for write file on update and create
     *
     *@param $Id as Id
     *
     * @return void
     */
    public function settingFileWrite()
    {
        $DB   = AdminSetting::query();
        $list = $DB->orderBy('key_value', 'ASC')->get(array(
            'key_value',
            'value'
        ))->toArray();
        
        $file        = SETTING_FILE_PATH;
        $settingfile = '<?php ' . "\n";
        foreach ($list as $value) {
            $val = str_replace('"', "'", $value['value']);
            $settingfile .= 'Config::set("' . $value['key_value'] . '", "' . $val . '");' . "\n";
        }
        $bytes_written = File::put($file, $settingfile);
        if ($bytes_written === false) {
            die("Error writing to file");
        }
    } //end settingFileWrite()
    
} //end SettingsController class

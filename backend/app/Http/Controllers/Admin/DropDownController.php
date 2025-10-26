<?php
/**
 * DropDownController Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/dropdown
 */

class DropDownController extends BaseController
{
    
    /**
     * Function for display all DropDown    
     *
     * @param $type as category of dropdown 
     *
     * @return view page. 
     */
    public function listDropDown($type = '')
    {
        
        ### breadcrumbs Start ###
        // Breadcrums   is  added   here dynamically
        Breadcrumb::addBreadcrumb('Dashboard', URL::to('admin/dashboard'));
        Breadcrumb::addBreadcrumb(studly_case($type), URL::to('admin/dropdown-manager/' . $type));
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        $DB             = AdminDropDown::query()->where('dropdown_type', $type);
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
        
        return View::make('admin.dropdown.index', compact('breadcrumbs', 'result', 'searchVariable', 'sortBy', 'order', 'type'));
    } // end listDropDown()
    
    /**
     * Function for display page  for add new DropDown  
     *
     * @param $type as category of dropdown 
     *
     * @return view page. 
     */
    public function addDropDown($type = '')
    {
        
        ### breadcrumbs Start ###
        // Breadcrums   is  added   here dynamically
        Breadcrumb::addBreadcrumb('Dashboard', URL::to('admin/dashboard'));
        Breadcrumb::addBreadcrumb(studly_case($type), URL::to('admin/dropdown-manager/' . $type));
        Breadcrumb::addBreadcrumb('Add '.studly_case($type));
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        $languages = DB::table('languages')->where('active', '=', '1')->get(array(
            'title',
            'id'
        ));
        
        $default_language = Config::get('default_language');
        $language_code    = $default_language['language_code'];
        
        return View::make('admin.dropdown.add', compact('languages', 'language_code', 'breadcrumbs', 'type'));
    } //end addDropDown()
    
    /**
     * Function for save added DropDown page
     *
     * @param null
     *
     * @return redirect page. 
     */
    function saveDropDown($type = '')
    {
        $thisData             = Input::all();
        $default_language     = Config::get('default_language');
        $language_code        = $default_language['language_code'];
        $dafaultLanguageArray = $thisData['data'][$language_code];
        
        $validator = Validator::make(array(
            'name' => $dafaultLanguageArray['name'],
            'dropdown_type' => $type
            
        ), array(
            'name' => 'required'
            
        ));
        
        if ($validator->fails()) {
            return Redirect::to('admin/dropdown-manager/add-dropdown/' . $type)->withErrors($validator)->withInput();
        } else {
            
            $dropdown                = new AdminDropDown;
            $dropdown->slug          = $this->getSlugWithoutModel($dafaultLanguageArray['name'], 'slug', 'dropdown_managers');
            $dropdown->name          = $dafaultLanguageArray['name'];
            $dropdown->dropdown_type = $type;
            
            $dropdown->save();
            
            $dropdownId = $dropdown->id;
            
            foreach ($thisData['data'] as $language_id => $value) {
                $modelDropDownDescription              = new AdminDropDownDescription();
                $modelDropDownDescription->language_id = $language_id;
                $modelDropDownDescription->parent_id   = $dropdownId;
                $modelDropDownDescription->name        = $value['name'];
                $modelDropDownDescription->save();
            }
            Session::flash('flash_notice', studly_case($type) . ' ' . trans("messages.masters.add_msg"));
            return Redirect::to('admin/dropdown-manager/' . $type);
        }
    } //end saveDropDown()
    
    /**
     * Function for display page  for edit DropDown page
     *
     * @param $Id ad id of DropDown 
     * @param $type as category of dropdown 
     *
     * @return view page. 
     */
    public function editDropDown($Id, $type)
    {
        
        ### breadcrumbs Start ###
        // Breadcrums   is  added   here dynamically
        Breadcrumb::addBreadcrumb('Dashboard', URL::to('admin/dashboard'));
        Breadcrumb::addBreadcrumb(studly_case($type), URL::to('admin/dropdown-manager/' . $type));
        Breadcrumb::addBreadcrumb('Edit '.studly_case($type));
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        $dropdown            = AdminDropDown::find($Id);
        $dropdownDescription = DB::table('dropdown_manager_descriptions')->where('parent_id', '=', $Id)->get();
        $multiLanguage       = array();
        
        if (!empty($dropdownDescription)) {
            foreach ($dropdownDescription as $description) {
                $multiLanguage[$description->language_id]['name'] = $description->name;
            }
        }
        
        $languages = DB::table('languages')->where('active', '=', '1')->get(array(
            'title',
            'id'
        ));
        
        $default_language = Config::get('default_language');
        $language_code    = $default_language['language_code'];
        
        return View::make('admin.dropdown.edit', array(
            'breadcrumbs' => $breadcrumbs,
            'languages' => $languages,
            'language_code' => $language_code,
            'dropdown' => $dropdown,
            'multiLanguage' => $multiLanguage,
            'type' => $type
        ));
    } // end editDropDown()
    
    /**
     * Function for update DropDown 
     *
     * @param $Id ad id of DropDown 
     * @param $type as category of dropdown 
     *
     * @return redirect page. 
     */
    function updateDropDown($Id, $type = '')
    {
        $this_data            = Input::all();
        $dropdown             = AdminDropDown::find($Id);
        $default_language     = Config::get('default_language');
        $language_code        = $default_language['language_code'];
        $dafaultLanguageArray = $this_data['data'][$language_code];
        
        $validator = Validator::make(array(
            'name' => $dafaultLanguageArray['name'],
            'image' => Input::file('image')
        ), array(
            'name' => 'required',
            'image' => 'mimes:jpeg,jpg,png,gif'
        ));
        
        if ($validator->fails()) {
            return Redirect::to('admin/dropdown-manager/edit-dropdown/' . $Id . '/' . $type)->withErrors($validator)->withInput();
        } else {
            $dropdown->name = $dafaultLanguageArray['name'];
            
            if (Input::hasFile('image')) {
                $extension = Input::file('image')->getClientOriginalExtension();
                $fileName  = time() . '-resource-image.' . $extension;
                if (Input::file('image')->move(MASTERS_IMAGE_ROOT_PATH, $fileName)) {
                    $dropdown->image = $fileName;
                }
            }
            $dropdown->save();
            
            $dropdownId = $dropdown->id;
            $dropdownId = $Id;
            
            DB::table('dropdown_manager_descriptions')->where('parent_id', '=', $Id)->delete();
            
            foreach ($this_data['data'] as $language_id => $value) {
                $modelDropDownDescription              = new AdminDropDownDescription();
                $modelDropDownDescription->language_id = $language_id;
                $modelDropDownDescription->name        = $value['name'];
                $modelDropDownDescription->parent_id   = $dropdownId;
                $modelDropDownDescription->save();
            }
            Session::flash('flash_notice', studly_case($type) . ' ' . trans("messages.masters.edit_msg"));
            return Redirect::intended('admin/dropdown-manager/' . $type);
        }
    } // end updateDropDown()
    
    /**
     * Function for update DropDown  status
     *
     * @param $Id as id of DropDown 
     * @param $Status as status of DropDown 
     * @param $type as category of dropdown 
     *
     * @return redirect page. 
     */
    public function updateDropDownStatus($Id = 0, $Status = 0, $type = '')
    {
        $model            = AdminDropDown::find($Id);
        $model->is_active = $Status;
        $model->save();
        Session::flash('flash_notice', studly_case($type) . ' ' . trans("messages.masters.status_msg"));
        return Redirect::to('admin/dropdown-manager/' . $type);
    } // end updateDropDownStatus()
    
    /**
     * Function for delete DropDown 
     *
     * @param $Id as id of DropDown 
     * @param $type as category of dropdown 
     *
     * @return redirect page. 
     */
    public function deleteDropDown($Id = 0, $type = '')
    {
        $dropdown = AdminDropDown::find($Id);
        $dropdown->description()->delete();
        if ($type == 'faq') {
            $dropdown->faq()->delete();
        }
        $dropdown->delete();
        Session::flash('flash_notice', studly_case($type) . ' ' . trans("messages.masters.delete_msg"));
        return Redirect::to('admin/dropdown-manager/' . $type);
    } // end deleteDropDown()
	
	 /**
	 * Function for  to show or  hide particular  cusine from  front 
	 *
	 * @param $cusineId as id of review
	 * @param $cusineStatus as status of cusine
	 *
	 * @return redirect page.
	 */

	public function cusineDislayStatus($cusineId = 0, $cusineStatus = 0){
		DB::table('dropdown_managers')->where('id', '=', $cusineId)->update(array('is_display' => $cusineStatus));
		if($cusineStatus){
			$message  =	'Cusine show on front Successfully.';
		}else{
			$message  =	'Cusine hide from front Successfully.';
		}
		Session::flash('flash_notice',$message); 
		return Redirect::back(); 
	}// end cusineDislayStatus()
    
} // end DropDownController

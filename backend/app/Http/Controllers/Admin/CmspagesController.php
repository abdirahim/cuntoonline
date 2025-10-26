<?php

/**
 * Cmspages Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/cmspages
 */

class CmspagesController extends BaseController
{
    
    /**
     * Function for display all cms page
     *
     * @param null
     *
     * @return view page. 
     */
    
    public function listCms()
    {
        
        ### breadcrumbs Start ###
        // Breadcrums   is  added   here dynamically
        Breadcrumb::addBreadcrumb('Dashboard', URL::to('admin/dashboard'));
        Breadcrumb::addBreadcrumb('Cms Manager', URL::to('admin/cms-manager'));
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        $DB             = AdminCmspage::query();
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
        
        return View::make('admin.cmspages.index', compact('breadcrumbs', 'result', 'searchVariable', 'sortBy', 'order'));
    } // end listcms()
    
    /**
     * Function for display page  for add new cms page 
     *
     * @param null
     *
     * @return view page. 
     */
    public function addCms()
    {
        
        ### breadcrumbs Start ###
        // Breadcrums   is  added   here dynamically
        Breadcrumb::addBreadcrumb('Dashboard', URL::to('admin/dashboard'));
        Breadcrumb::addBreadcrumb('Cms Manager', URL::to('admin/cms-manager'));
        Breadcrumb::addBreadcrumb('Add Cms Page');
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        $languages = DB::table('languages')->where('active', '=', '1')->get(array(
            'title',
            'id'
        ));
        
        $default_language = Config::get('default_language');
        $language_code    = $default_language['language_code'];
        
        return View::make('admin.cmspages.add', compact('languages', 'language_code', 'breadcrumbs'));
    } //end addCms()
    
    /**
     * Function for save added cms page
     *
     * @param null
     *
     * @return redirect page. 
     */
    function saveCms()
    {
        $thisData = Input::all();
        
        $default_language     = Config::get('default_language');
        $language_code        = $default_language['language_code'];
        $dafaultLanguageArray = $thisData['data'][$language_code];
        
        $validator = Validator::make(array(
            'name' => Input::get('name'),
            'title' => $dafaultLanguageArray['title'],
            'body' => $dafaultLanguageArray['body'],
            'meta_title' => $dafaultLanguageArray['meta_title'],
            'meta_description' => $dafaultLanguageArray['meta_description'],
            'meta_keywords' => $dafaultLanguageArray['meta_keywords']
        ), array(
            'name' => 'required',
            'title' => 'required',
            'body' => 'required',
            'meta_title' => 'required',
            'meta_description' => 'required',
            'meta_keywords' => 'required'
        ));
        
        if ($validator->fails()) {
            return Redirect::to('admin/cms-manager/add-cms')->withErrors($validator)->withInput();
        } else {
            $cms = new AdminCmspage;
            
            $cms->name             = Input::get('name');
            $cms->title            = $dafaultLanguageArray['title'];
            $cms->body             = $dafaultLanguageArray['body'];
            $cms->meta_title       = $dafaultLanguageArray['meta_title'];
            $cms->meta_description = $dafaultLanguageArray['meta_description'];
            $cms->meta_keywords    = $dafaultLanguageArray['meta_keywords'];
            $cms->slug             = $this->getSlug($dafaultLanguageArray['title'], 'AdminCmspage');
            
            $cms->save();
            $cms_page_id = $cms->id;
            
            
            foreach ($thisData['data'] as $language_id => $cms) {
                if (is_array($cms))
                    foreach ($cms as $key => $value) {
                        DB::table('cms_page_descriptions')->insert(array(
                            'language_id' => $language_id,
                            'parent_id' => $cms_page_id,
                            'source_col_name' => $key,
                            'source_col_description' => $value
                        ));
                    }
            }
            Session::flash('flash_notice', trans("messages.management.cms_add_msg"));
            return Redirect::intended('admin/cms-manager')->with('message', 'You are now logged in!');
        }
    } //end saveCms()
    
    /**
     * Function for display page  for edit cms page
     *
     * @param $Id ad id of cms page
     *
     * @return view page. 
     */
    public function editCms($Id)
    {
        
        ### breadcrumbs Start ###
        // Breadcrums   is  added   here dynamically
        Breadcrumb::addBreadcrumb('Dashboard', URL::to('admin/dashboard'));
        Breadcrumb::addBreadcrumb('Cms Manager', URL::to('admin/cms-manager'));
        Breadcrumb::addBreadcrumb('Edit Cms Page');
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        $adminCmspage = AdminCmspage::find($Id);
        
        $cmsPageDescription = DB::table('cms_page_descriptions')->where('parent_id', '=', $Id)->get();
        $multiLanguage      = array();
        if (!empty($cmsPageDescription)) {
            foreach ($cmsPageDescription as $description) {
                $multiLanguage[$description->language_id][$description->source_col_name] = $description->source_col_description;
            }
        }
        
        $languages = DB::table('languages')->where('active', '=', '1')->get(array(
            'title',
            'id'
        ));
        
        $default_language = Config::get('default_language');
        $language_code    = $default_language['language_code'];
        
        return View::make('admin.cmspages.edit', array(
            'breadcrumbs' => $breadcrumbs,
            'languages' => $languages,
            'language_code' => $language_code,
            'adminCmspage' => $adminCmspage,
            'multiLanguage' => $multiLanguage
        ));
    } // end editCms()
    
    /**
     * Function for update cms page
     *
     * @param $Id ad id of cms page
     *
     * @return redirect page. 
     */
    function updateCms($Id)
    {
        $this_data = Input::all();
        
        $default_language     = Config::get('default_language');
        $language_code        = $default_language['language_code'];
        $dafaultLanguageArray = $this_data['data'][$language_code];
        
        $validator = Validator::make(array(
            'name' => Input::get('name'),
            'title' => $dafaultLanguageArray['title'],
            'body' => $dafaultLanguageArray['body'],
            'meta_title' => $dafaultLanguageArray['meta_title'],
            'meta_description' => $dafaultLanguageArray['meta_description'],
            'meta_keywords' => $dafaultLanguageArray['meta_keywords']
        ), array(
            'name' => 'required',
            'title' => 'required',
            'body' => 'required',
            'meta_title' => 'required',
            'meta_description' => 'required',
            'meta_keywords' => 'required'
        ));
        
        if ($validator->fails()) {
            return Redirect::to('admin/cms-manager/edit-cms/' . $Id)->withErrors($validator)->withInput();
        } else {
            AdminCmspage::where('id', $Id)->update(array(
                'name' => Input::get('name'),
                'title' => $dafaultLanguageArray['title'],
                'body' => $dafaultLanguageArray['body'],
                'meta_title' => $dafaultLanguageArray['meta_title'],
                'meta_description' => $dafaultLanguageArray['meta_description'],
                'meta_keywords' => $dafaultLanguageArray['meta_keywords']
            ));
            $cms_page_id = $Id;
            
            DB::table('cms_page_descriptions')->where('parent_id', '=', $Id)->delete();
            foreach ($this_data['data'] as $language_id => $cms) {
                if (is_array($cms))
                    foreach ($cms as $key => $value) {
                        DB::table('cms_page_descriptions')->insert(array(
                            'language_id' => $language_id,
                            'parent_id' => $cms_page_id,
                            'source_col_name' => $key,
                            'source_col_description' => $value
                        ));
                    }
            }
            Session::flash('flash_notice', trans("messages.management.cms_edit_msg"));
            return Redirect::intended('admin/cms-manager');
        }
    } // end updateCms()
    
    /**
     * Function for update cms page status
     *
     * @param $Id as id of cms page
     * @param $Status as status of cms page
     *
     * @return redirect page. 
     */
    public function updateCmsStatus($Id = 0, $Status = 0)
    {
        AdminCmspage::where('id', '=', $Id)->update(array(
            'is_active' => $Status
        ));
        Session::flash('flash_notice', trans("messages.management.cms_status_msg"));
        
        return Redirect::to('admin/cms-manager');
    } // end updateCmsStatus()
    
} // end CmspagesController()

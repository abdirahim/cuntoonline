<?php

/**
 * BlockController Controller 
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/blockmgmt/block
 */

class BlockController extends BaseController
{
    
    /**
     * Function for display all Block 
     *
     * @param null
     *
     * @return view page. 
     */
    
    public function listBlock()
    {
        ### breadcrumbs Start ###
        // Breadcrums   is  added   here dynamically
        Breadcrumb::addBreadcrumb('Dashboard', URL::to('admin/dashboard'));
        Breadcrumb::addBreadcrumb('Block Manager', URL::to('admin/block-manager'));
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        $DB             = AdminBlock::query();
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
        
        return View::make('admin.blockmgmt.index', compact('breadcrumbs', 'result', 'searchVariable', 'sortBy', 'order'));
    } // end listBlock()
    
    /**
     * Function for display page  for add new Block  
     *
     * @param null
     *
     * @return view page. 
     */
    public function addBlock()
    {
        
        ### breadcrumbs Start ###
        // Breadcrums   is  added   here dynamically
        Breadcrumb::addBreadcrumb('Dashboard', URL::to('admin/dashboard'));
        Breadcrumb::addBreadcrumb('Block Manager', URL::to('admin/block-manager'));
        Breadcrumb::addBreadcrumb('Add Block ');
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        $languages        = DB::table('languages')->where('active', '=', '1')->get(array(
            'title',
            'id'
        ));
        $default_language = Config::get('default_language');
        $language_code    = $default_language['language_code'];
        
        return View::make('admin.blockmgmt.add', compact('languages', 'language_code', 'breadcrumbs'));
    } //end addBlock()
    
    /**
     * Function for save added Block page
     *
     * @param null
     *
     * @return redirect page. 
     */
    function saveBlock()
    {
        $thisData = Input::all();
        
        $default_language     = Config::get('default_language');
        $language_code        = $default_language['language_code'];
        $dafaultLanguageArray = $thisData['data'][$language_code];
        
        $validator = Validator::make(array(
            'page_name' => Input::get('page_name'),
            'block_name' => Input::get('block_name'),
            'description' => $dafaultLanguageArray['description']
        ), array(
            'page_name' => 'required',
            'block_name' => 'required',
            'description' => 'required'
        ));
        
        if ($validator->fails()) {
            return Redirect::to('admin/block-manager/add-block')->withErrors($validator)->withInput();
        } else {
            $Block = new AdminBlock;
            
            $Block->page_name   = Input::get('page_name');
            $Block->block_name  = Input::get('block_name');
            $Block->page        = Input::get('page_name');
            $Block->block       = $this->getSlugWithoutModel(Input::get('block_name'), 'block', 'blocks');
            $Block->description = $dafaultLanguageArray['description'];
            $Block->save();
            
            $BlockId = $Block->id;
            foreach ($thisData['data'] as $language_id => $Block) {
                if (is_array($Block))
                    foreach ($Block as $key => $value) {
                        $modelBlockDescription              = new AdminBlockDescription();
                        $modelBlockDescription->language_id = $language_id;
                        $modelBlockDescription->parent_id   = $BlockId;
                        $modelBlockDescription->description = $value;
                        $modelBlockDescription->save();
                    }
            }
            Session::flash('flash_notice', 'Block added successfully.');
            return Redirect::to('admin/block-manager');
        }
    } //end saveBlock()
    
    /**
     * Function for display page  for edit Block page
     *
     * @param $Id ad id of Block page
     *
     * @return view page. 
     */
    public function editBlock($Id)
    {
        
        ### breadcrumbs Start ###
        // Breadcrums   is  added   here dynamically
        Breadcrumb::addBreadcrumb('Dashboard', URL::to('admin/dashboard'));
        Breadcrumb::addBreadcrumb('Block Manager', URL::to('admin/block-manager'));
        Breadcrumb::addBreadcrumb('Edit');
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        $block = AdminBlock::find($Id);
        
        $BlockDescription = DB::table('blocks_descriptions')->where('parent_id', '=', $Id)->get();
        
        $multiLanguage = array();
        if (!empty($BlockDescription)) {
            foreach ($BlockDescription as $description) {
                $multiLanguage[$description->language_id]['description'] = $description->description;
            }
        }
        
        $languages = DB::table('languages')->where('active', '=', '1')->get(array(
            'title',
            'id'
        ));
        
        $default_language = Config::get('default_language');
        $language_code    = $default_language['language_code'];
        
        return View::make('admin.blockmgmt.edit', array(
            'breadcrumbs' => $breadcrumbs,
            'languages' => $languages,
            'language_code' => $language_code,
            'block' => $block,
            'multiLanguage' => $multiLanguage
        ));
    } // end editBlock()
    
    /**
     * Function for update Block 
     *
     * @param $Id ad id of Block 
     *
     * @return redirect page. 
     */
    function updateBlock($Id)
    {
        $this_data            = Input::all();
        $block                = AdminBlock::find($Id);
        $default_language     = Config::get('default_language');
        $language_code        = $default_language['language_code'];
        $dafaultLanguageArray = $this_data['data'][$language_code];
        
        $validator = Validator::make(array(
            'page_name' => Input::get('page_name'),
            'block_name' => Input::get('block_name'),
            'description' => $dafaultLanguageArray['description']
        ), array(
            'page_name' => 'required',
            'block_name' => 'required',
            'description' => 'required'
        ));
        
        if ($validator->fails()) {
            return Redirect::to('admin/block-manager/edit-block/' . $Id)->withErrors($validator)->withInput();
        } else {
            
            $block->page_name   = Input::get('page_name');
            $block->block_name  = Input::get('block_name');
            $block->description = $dafaultLanguageArray['description'];
            $block->save();
            
            $BlockId = $Id;
            DB::table('blocks_descriptions')->where('parent_id', '=', $Id)->delete();
            
            foreach ($this_data['data'] as $language_id => $Block) {
                if (is_array($Block))
                    foreach ($Block as $key => $value) {
                        $modelBlockDescription              = new AdminBlockDescription();
                        $modelBlockDescription->language_id = $language_id;
                        $modelBlockDescription->parent_id   = $BlockId;
                        $modelBlockDescription->description = $value;
                        $modelBlockDescription->save();
                    }
            }
            Session::flash('flash_notice', 'Block updated successfully.');
            return Redirect::intended('admin/block-manager');
        }
    } // end updateBlock()
    
    /**
     * Function for update Block  status
     *
     * @param $Id as id of Block 
     * @param $Status as status of Block 
     *
     * @return redirect page. 
     */
    public function updateBlockStatus($Id = 0, $Status = 0)
    {
        $model            = AdminBlock::find($Id);
        $model->is_active = $Status;
        $model->save();
        Session::flash('flash_notice', 'Block status updated successfully.');
        return Redirect::to('admin/block-manager');
    } // end updateBlockStatus()
    
    /**
     * Function for delete Block 
     *
     * @param $Id as id of Block 
     *
     * @return redirect page. 
     */
    public function deleteBlock($Id = 0)
    {
        $block = AdminBlock::find($Id);
        $block->description()->delete();
        $block->delete();
        Session::flash('flash_notice', 'Block deleted successfully.');
        return Redirect::to('admin/block-manager');
    } // end deleteBlock()
    
} // end BlockController

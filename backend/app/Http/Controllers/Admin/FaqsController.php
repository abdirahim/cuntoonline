<?php

/**
 * Faqs Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/faq
 */

class FaqsController extends BaseController
{
    
    /**
     * Function for display list of all faq's
     *
     * @param null
     *
     * @return view page. 
     */
    public function listFaq()
    {
        
        ### breadcrumbs Start ###
        ### Breadcrums   is  added   here dynamically ###
        Breadcrumb::addBreadcrumb('Dashboard', URL::to('admin/faqs-manager'));
        Breadcrumb::addBreadcrumb('Faq Manager', URL::to('admin/faqs-manager'));
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        $DB             = AdminFaq::with('category')->select();
        $searchVariable = array();
        $inputGet       = Input::get();
        
        
        if (Input::get() && isset($inputGet['display']) || isset($inputGet['page'])) {
            $searchData = Input::get();
            unset($searchData['display']);
            unset($searchData['_token']);
            
            if (isset($searchData['page'])) {
                unset($searchData['page']);
            }
            
            foreach ($searchData as $fieldName => $fieldValue) {
                if (!empty($fieldValue)) {
                    if ($fieldName == 'category_id') {
                        $DB->where("category_id", $fieldValue);
                    } else {
                        $DB->where("$fieldName", 'like', '%' . $fieldValue . '%');
                    }
                    $searchVariable = array_merge($searchVariable, array(
                        $fieldName => $fieldValue
                    ));
                }
            }
        }
        
        $sortBy = (Input::get('sortBy')) ? Input::get('sortBy') : 'updated_at';
        $order  = (Input::get('order')) ? Input::get('order') : 'DESC';
        
        $listDownloadCategory = AdminDropDown::where('dropdown_type', 'faq')->orderBy('created_at', 'asc')->lists('name', 'id');
        $result               = $DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
        
        return View::make('admin.faq.index', compact('breadcrumbs', 'result', 'searchVariable', 'sortBy', 'order', 'listDownloadCategory'));
    } // end listFaq()
    
    /**
     * Function for display page for add faq
     *
     * @param null
     *
     * @return view page. 
     */
    public function addFaq()
    {
        
        ### breadcrumbs Start ###
        ### Breadcrums   is  added   here dynamically ###
        Breadcrumb::addBreadcrumb('Dashboard', URL::to('admin/faqs-manager'));
        Breadcrumb::addBreadcrumb('Faq Manager', URL::to('admin/faqs-manager'));
        Breadcrumb::addBreadcrumb('Add');
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        $languages = DB::table('languages')->where('active', '=', '1')->get(array(
            'title',
            'id'
        ));
        
        $default_language = Config::get('default_language');
        $language_code    = $default_language['language_code'];
        
        $listDownloadCategory = AdminDropDown::where('dropdown_type', 'faq')->lists('name', 'id');
        
        return View::make('admin.faq.add', compact('languages', 'language_code', 'breadcrumbs', 'listDownloadCategory'));
    } // end addFaq()
    
    /**
     * Function for save created faq
     *
     * @param null
     *
     * @return redirect page. 
     */
    function saveFaq()
    {
        
        $thisData = Input::all();
        $default_language     = Config::get('default_language');
        $language_code        = $default_language['language_code'];
        $dafaultLanguageArray = $thisData['data'][$language_code];
        
        $validator = Validator::make(array(
            'question' => $dafaultLanguageArray['question'],
            'answer' => $dafaultLanguageArray['answer']
        ), array(
            'question' => 'required',
            'answer' => 'required'
        ));
        
        if ($validator->fails()) {
            return Redirect::to('admin/faqs-manager/add-faqs')->withErrors($validator)->withInput();
        } else {
            
            $obj = new AdminFaq;
            
            $obj->question = $dafaultLanguageArray['question'];
            $obj->answer   = $dafaultLanguageArray['answer'];
            $obj->save();
            $faq_id = $obj->id;
            
            foreach ($thisData['data'] as $language_id => $faqs) {
                if (is_array($faqs)) {
                    DB::table('faq_descriptions')->insert(array(
                        'language_id' => $language_id,
                        'parent_id' => $faq_id,
                        'question' => $faqs['question'],
                        'answer' => $faqs['answer']
                    ));
                }
            }
            
            Session::flash('flash_notice', trans("messages.management.faq_add_msg"));
            return Redirect::intended('admin/faqs-manager');
        }
    } // end saveFaq()
    
    /**
     * Function for display page for edit faq
     *
     * @param $Id as id of faq
     *
     * @return view page. 
     */
    public function editFaq($Id)
    {
        
        ### breadcrumbs Start ###
        ### Breadcrums   is  added   here dynamically ###
        Breadcrumb::addBreadcrumb('Dashboard', URL::to('admin/faqs-manager'));
        Breadcrumb::addBreadcrumb('Faq Manager', URL::to('admin/faqs-manager'));
        Breadcrumb::addBreadcrumb('Edit');
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        $AdminFaq = AdminFaq::find($Id);
        
        $faqDescription = DB::table('faq_descriptions')->where('parent_id', '=', $Id)->get();
        
        
        $multiLanguage = array();
        if (!empty($faqDescription)) {
            foreach ($faqDescription as $key => $description) {
                $multiLanguage[$description->language_id]['question'] = $description->question;
                $multiLanguage[$description->language_id]['answer']   = $description->answer;
            }
        }
        
        $languages = DB::table('languages')->where('active', '=', '1')->get(array(
            'title',
            'id'
        ));
        
        $default_language = Config::get('default_language');
        $language_code    = $default_language['language_code'];
        
        return View::make('admin.faq.edit', compact('breadcrumbs', 'languages', 'language_code', 'AdminFaq', 'multiLanguage'));
    } //editFaq()
    
    /**
     * Function for update faq
     *
     * @param $Id as id of faq
     *
     * @return redirect page. 
     */
    function updateFaq($Id)
    {
        
        $thisData = Input::all();
        
        $default_language     = Config::get('default_language');
        $language_code        = $default_language['language_code'];
        $dafaultLanguageArray = $thisData['data'][$language_code];
        
        $validator = Validator::make(array(
            'question' => $dafaultLanguageArray['question'],
            'answer' => $dafaultLanguageArray['answer']
        ), array(
            'question' => 'required',
            'answer' => 'required'
        ));
        
        if ($validator->fails()) {
            return Redirect::to('admin/faqs-manager/edit-faqs/' . $Id)->withErrors($validator)->withInput();
        } else {
            
            $obj = AdminFaq::find($Id);
            
            $obj->question = $dafaultLanguageArray['question'];
            $obj->answer   = $dafaultLanguageArray['answer'];
            $obj->save();
            $faq_id = $obj->id;
            
            DB::table('faq_descriptions')->where('parent_id', '=', $Id)->delete();
            
            foreach ($thisData['data'] as $language_id => $faqs) {
                if (is_array($faqs)) {
                    DB::table('faq_descriptions')->insert(array(
                        'language_id' => $language_id,
                        'parent_id' => $faq_id,
                        'question' => $faqs['question'],
                        'answer' => $faqs['answer']
                    ));
                }
            }
            
            Session::flash('flash_notice', trans("messages.management.faq_edit_msg"));
            return Redirect::to('admin/faqs-manager');
        }
    } // end updateFaq()
    
    /**
     * Function for update faq status
     *
     * @param $Id as id of faq
     * @param $Status as status of faq
     *
     * @return redirect page. 
     */
    public function updateFaqStatus($Id = 0, $Status = 0)
    {
        AdminFaq::where('id', '=', $Id)->update(array(
            'is_active' => $Status
        ));
        Session::flash('flash_notice', trans("messages.management.faq_status_msg"));
        return Redirect::back();
    } // end updateFaqStatus()
    
    /**
     * Function for delete faq
     *
     * @param $Id as id of faq
     *
     * @return redirect page. 
     */
    public function deleteFaq($Id = 0)
    {
        if ($Id) {
            $obj = AdminFaq::find($Id);
            $obj->delete();
            Session::flash('flash_notice', trans("messages.management.faq_delete_msg"));
        }
        return Redirect::intended('admin/faqs-manager');
    } // end deleteFaq()
    
} // end FaqsController

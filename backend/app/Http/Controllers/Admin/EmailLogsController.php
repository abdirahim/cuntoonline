<?php

/**
 * EmailLogsController
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/emaillogs
 */

class EmailLogsController extends BaseController
{
    /*
     * Function for display email detail from database  
     *
     * @param null
     *
     * @return view page. 
     */
    public function listEmail()
    {
        
        ### breadcrumbs Start ###
        Breadcrumb::addBreadcrumb('Dashboard', URL::to('admin/dashboard'));
        Breadcrumb::addBreadcrumb('Email Logs Manager');
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        $DB = AdminEmailLog::query();
        
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
        
        $sortBy = (Input::get('sortBy')) ? Input::get('sortBy') : 'created_at';
        $order  = (Input::get('order')) ? Input::get('order') : 'DESC';
        
        $result = $DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
        return View::make('admin.emaillogs.index', compact('result', 'searchVariable', 'breadcrumbs', 'sortBy', 'order'));
    } //end listEmail()
    
    /*
     * Function for dispaly email details on popup  
     *
     * @param $id as mail id 
     *
     * @return view page. 
     */
    public function EmailDetail($id)
    {
        if (Request::ajax()) {
            $result = AdminEmailLog::where('id', $id)->get();
            return View::make('admin.emaillogs.popup', compact('result'));
        }
    } // end EmailDetail()
    
} // end EmailLogsController

<?php

/**
 * Users Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/usermgmt
 */

class UsersController extends BaseController
{
    
    /**
     * Function for display list of all users
     *
     * @param null
     *
     * @return view page. 
     */
    
    public function listUsers()
    {
        
        ### breadcrumbs Start ###
        Breadcrumb::addBreadcrumb('Dashboard', URL::to('admin/dashboard'));
        Breadcrumb::addBreadcrumb('Users');
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        $DB = AdminUser::query();
        
        $searchVariable = array();
        $inputGet       = Input::get();
        
        /* seacrching on the basis of username and email */
        
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
        
        $result = $DB->with('userLastLogin')->where('id', '<>', ADMIN_ID)->where('is_deleted', 0)->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
        
        return View::make('admin.usermgmt.index', compact('breadcrumbs', 'result', 'searchVariable', 'sortBy', 'order', 'userType', 'type'));
    } // end listUsers()
    
    /**
     * Function for add users
     *
     * @param null
     *
     * @return view page. 
     */
    public function addUser()
    {
        
        ### breadcrumbs Start ###
        Breadcrumb::addBreadcrumb('Dashboard', URL::to('admin/dashboard'));
        Breadcrumb::addBreadcrumb('Users', URL::to('admin/users'));
        Breadcrumb::addBreadcrumb('Add User');
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        return View::make('admin.usermgmt.add', compact('breadcrumbs'));
        
    } //end addCompany()
    
    /**
     * Function for save added users
     *
     * @param null
     *
     * @return view page. 
     */
    public function saveUser()
    {
        
        $formData = Input::all();
        if (!empty($formData)) {
            $validator = Validator::make(Input::all(), array(
                'full_name' => 'required',
                'email' => 'required|email|unique:users,email,NULL,id,is_deleted,0',
                'password' => 'required|min:6',
                'confirm_password' => 'required|min:6|same:password'
            ));
            
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            } else {
                
                $userRoleId = FRONT_USER;
                
                $obj                  = new AdminUser;
                $obj->slug            = $this->getSlug(Input::get('full_name'), 'User');
                $obj->full_name       = ucwords(Input::get('full_name'));
                $obj->email           = Input::get('email');
                $obj->password        = Hash::make(Input::get('password'));
                $obj->user_role_id    = $userRoleId;
                $validateString       = md5(time() . Input::get('email'));
                $obj->validate_string = $validateString;
                $obj->is_verified     = 1;
                $obj->active          = 1;
                $obj->save();
                
                ### mail email and password to new registered user ###
                
                $settingsEmail = Config::get('Site.email');
                $full_name     = ucwords(Input::get('full_name'));
                $email         = Input::get('email');
                $password      = Input::get('password');
                $route_url     = URL::to('login');
                $click_link    = $route_url;
                
                $emailActions   = EmailAction::where('action', '=', 'user_registration')->get()->toArray();
                $emailTemplates = EmailTemplate::where('action', '=', 'user_registration')->get(array(
                    'name',
                    'subject',
                    'action',
                    'body'
                ))->toArray();
                
                $cons      = explode(',', $emailActions[0]['options']);
                $constants = array();
                
                foreach ($cons as $key => $val) {
                    $constants[] = '{' . $val . '}';
                }
                
                $subject     = $emailTemplates[0]['subject'];
                $rep_Array   = array(
                    $full_name,
                    $email,
                    $password,
                    $click_link,
                    $route_url
                );
                $messageBody = str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
                
                $mail = $this->sendMail($email, $full_name, $subject, $messageBody, $settingsEmail);
                Session::flash('success', trans("messages.user_managmt.user_add_msg"));
                return Redirect::to('admin/users');
            }
        }
    } // saveUser()
    
    /**
     * Function for display page for edit user
     *
     * @param $userId as id of user
     *
     * @return view page. 
     */
    
    public function editUser($userId = 0)
    {
        
        ### breadcrumbs Start ###
        Breadcrumb::addBreadcrumb('Dashboard', URL::to('admin/dashboard'));
        Breadcrumb::addBreadcrumb('Users', URL::to('admin/users'));
        Breadcrumb::addBreadcrumb('edit');
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        if ($userId) {
            $userDetails = AdminUser::find($userId); 
            return View::make('admin.usermgmt.edit', compact('userDetails', 'countryList', 'regionList', 'breadcrumbs'));
        }
    } // end editUser()
    
    /**
     * Function for update user detail
     *
     * @param $userId as id of user
     *
     * @return redirect page. 
     */
    public function updateUser($userId = 0)
    {
        
        $thisData = Input::all();
        
        $validationRules = array(
            'full_name' => 'required',
            'email' => 'required|email',
            'password' => 'min:6',
            'confirm_password' => 'min:6|same:password'
        );
        
        $email_check = AdminUser::where('id', $userId)->pluck('email');
        
        if ($email_check == '') {
            $rules           = array(
                'email' => 'required|email|unique:users'
            );
            $validationRules = array_merge($validationRules, $rules);
        }
        
        if (Input::get('password') != '') {
            $validationRules = array_merge($validationRules, array(
                'confirm_password' => 'required|min:6|same:password'
            ));
        }
        
        $validator = Validator::make($thisData, $validationRules);
        
        if ($validator->fails()) {
            return Redirect::to('/admin/users/edit-user/' . $userId)->withErrors($validator)->withInput();
        } else {
            
            ## Update user's information in users table ##
            
            $obj = AdminUser::find($userId);
            
            if ($email_check == '') {
                $obj->email = Input::get('email');
            }
            
            $get_password         = Input::get('password');
            $get_confirm_password = Input::get('confirm_password');
            if (!empty($get_password) && !empty($get_confirm_password) && $get_password == $get_confirm_password) {
                $obj->password = Hash::make(Input::get('password'));
                
            }
            $obj->full_name = ucwords(Input::get('full_name'));
            $obj->save();
            
            
            return Redirect::to('/admin/users')->with('success', trans("messages.user_managmt.user_edit_msg"));
        }
    } // end updateUser()
    
    /**
     * Function for mark a user as deleted 
     *
     * @param $userId as id of user
     *
     * @return redirect page. 
     */
    public function deleteUser($userId = 0)
    {
        if ($userId) {
            $userModel = AdminUser::find($userId);
            DB::table('users')->where('id', '=', $userId)->update(array(
                'is_deleted' => 1
            ));
            $userModel = $userModel->delete();
            Session::flash('flash_notice', trans("messages.user_managmt.user_delete_msg"));
        }
        return Redirect::to('admin/users/');
    } // end deleteUser()
    
    /**
     * Function for update user status
     *
     * @param $userId as id of user
     * @param $userStatus as status of user
     *
     * @return redirect page. 
     */
    public function updateUserStatus($userId = 0, $userStatus = 0)
    {
        DB::table('users')->where('id', '=', $userId)->update(array(
            'active' => $userStatus
        ));
        Session::flash('flash_notice', trans("messages.user_managmt.status_msg"));
        return Redirect::to('admin/users');
    } // end updateUserStatus()
        
} //end UsersController

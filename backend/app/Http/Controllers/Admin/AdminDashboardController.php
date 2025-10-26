<?php

/**
 * AdminDashBoard Controller
 *
 * Add your methods in the class below
 *
 * This file will render views\admin\dashboard
 */
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\BaseController;
use mjanssen\BreadcrumbsBundle\Breadcrumbs;
use App\AdminUser;
use View;

class AdminDashBoardController extends BaseController
{
    
    /**
     * Function for display admin dashboard
     *
     * @param null
     *
     * @return view page. 
     */
    public function showdashboard()
    {
        
        ###Breadcrumb starts ###
      //  Breadcrumbs::addBreadcrumb('Dashboard');
        Breadcrumbs::addBreadcrumb('Dashboard', 'admin/dashboard');
        Breadcrumbs::generate();
//        $data = array(
//            'breadcrumbs' => Breadcrumbs::generate()
//        );
        ###Breadcrumb End ###
        
        $month = date('m');
        $year  = date('Y');
        for ($i = 0; $i < 12; $i++) {
            $months[] = date("Y-m", strtotime(date('Y-m-01') . " -$i months"));
        }
        
        $months = array_reverse($months);
        $num    = 0;
        foreach ($months as $month) {
            
            $monthStartDate          = date('Y-m-01 00:00:00', strtotime($month));
            $monthEndDate            = date('Y-m-t 23:59:59', strtotime($month));
            $allUsers[$num]['month'] = $month;
            $allUsers[$num]['users'] = AdminUser::where('user_role_id', config("constants.FRONT_USER"))->where('created_at', '>', $monthStartDate)->where('created_at', '<', $monthEndDate)->where('active', 1)->count();
            $num++;
        }
        
        $totalUser = AdminUser::where('user_role_id', config("constants.FRONT_USER"))->count();
        
        $totalActiveUser = AdminUser::where('user_role_id', config("constants.FRONT_USER"))->where('active', 1)->where('is_verified', 1)->count();
        
        $totalInActiveUser = AdminUser::where('user_role_id', config("constants.FRONT_USER"))->where('active', 0)->where('is_verified', 1)->count();
        $totalPendingUser  = AdminUser::where('user_role_id', config("constants.FRONT_USER"))->where('active', 1)->where('is_verified', 0)->count();
        return View::make('admin.dashboard.dashboard', compact('allUsers', 'totalUser', 'totalActiveUser', 'totalInActiveUser', 'totalPendingUser'));
    } // end showdashboard()
    
    /**
     * Function for display admin account detail
     *
     * @param null
     *
     * @return view page. 
     */
    public function myaccount()
    {
        ### Breadcrumb starts ###
        Breadcrumb::addBreadcrumb('Dashboard', 'dashboard');
        Breadcrumb::addBreadcrumb('MyAccount');
        $data = array(
            'breadcrumbs' => Breadcrumb::generate()
        );
        ### Breadcrumb End ###
        
        return View::make('admin.dashboard.myaccount', $data);
    } // end myaccount()
    
    /**
     * Function for update admin account update
     *
     * @param null
     *
     * @return redirect page. 
     */
    public function myaccountUpdate()
    {
        $old_password     = Input::get('old_password');
        $password         = Input::get('new_password');
        $confirm_password = Input::get('confirm_password');
        
        $ValidationRule = array(
            'full_name' => 'required',
            'email' => 'required|email'
        );
        
        
        if ($old_password != '' || $password != '' || $confirm_password != '') {
            
            $rules          = array(
                'old_password' => 'required|min:6',
                'new_password' => 'required|min:6',
                'confirm_password' => 'required|min:6|same:new_password'
            );
            $ValidationRule = array_merge($ValidationRule, $rules);
        }
        
        $validator = Validator::make(Input::all(), $ValidationRule);
        
        
        if ($validator->fails()) {
            return Redirect::to('admin/myaccount')->withErrors($validator)->withInput();
        } else {
            
            $user             = User::find(Auth::user()->id);
            $old_password     = Input::get('old_password');
            $password         = Input::get('new_password');
            $confirm_password = Input::get('confirm_password');
            $full_name        = Input::get('full_name');
			$email        	  = Input::get('email');
            
            if ($old_password != '') {
                
                if (!Hash::check($old_password, $user->getAuthPassword())) {
                    return Redirect::intended('admin/myaccount')->with('error', 'Your old password is incorrect.');
                }
            }
            if (!empty($old_password) && !empty($password) && !empty($confirm_password)) {
                if (Hash::check($old_password, $user->getAuthPassword())) {
                    $user->password  = Hash::make($password);
                    $user->full_name = $full_name;
                    ### save the new password ###
                    if ($user->save()) {
                        return Redirect::intended('admin/myaccount')->with('success', 'Information updated successfully.');
                    }
                } else {
                    return Redirect::intended('admin/myaccount')->with('error', 'Your old password is incorrect.');
                }
            } else {
                $user->full_name = $full_name;
				$user->email 	 =  $email;
                if ($user->save()) {
                    return Redirect::intended('admin/myaccount')->with('success', 'Information updated successfully.');
                }
            }
        }
    } // end myaccountUpdate()
    
   
} //end AdminDashBoardController()

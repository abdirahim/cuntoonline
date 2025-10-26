<?php

/**
 * AdminLogin Controller
 *
 * Add your methods in the class below
 *
 * This file will render views\admin\login
 */
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\BaseController;
use Auth;
use Request;
use View;
use Input;
use Session;
use Redirect;
class AdminLoginController extends BaseController
{
    
    /**
     * Function for display admin  login page
     *
     * @param null
     *
     * @return view page. 
     */
    public function login()
    {


        if (Auth::check()) {

            if (Auth::user()->user_role_id == config("constants.ADMIN_ID")) {
                Return Redirect::to('admin/dashboard');
            }
        }
        if (Request::isMethod('post')) {

            $userdata = array(
                'email' => Input::get('email'),
                'password' => Input::get('password'),
                'user_role_id' => config("constants.ADMIN_ID")

            );

            if (Auth::attempt($userdata, true)) {

                Session::put('user', Auth::user());

                $data = Session::all();



                Session::flash('flash_notice', 'You are now logged in!');



                return Redirect::intended('admin/dashboard')->with('message', 'You are now logged in!');


            } else {

                Session::flash('error', 'Email or Password is incorrect.');
                return Redirect::back()->withInput();
            }
        } else {

            return View::make('admin.login.index');
        }
    } // end login()
    
    /**
     * Function for logout admin users
     *
     * @param null
     *
     * @return rerirect page. 
     */
    public function logout()
    {
        Auth::logout();
        Session::flash('flash_notice', 'You are now logged out!');
        return Redirect::to('/admin')->with('message', 'You are now logged out!');
    } //endLogout()
    
    /**
     * Function is used to display forget password page
     *
     * @param null
     *
     * @return view page. 
     */
    public function forgetPassword()
    {
        return View::make('admin.login.forget_password');
    } // end forgetPassword()
    
    /**
     * Function is used for reset password
     *
     * @param $validate_string as validator string
     *
     * @return view page. 
     */
    public function resetPassword($validate_string = null)
    {
        
        if ($validate_string != "" && $validate_string != null) {
            
            $userDetail = DB::table('users')->where('active', '1')->where('forgot_password_validate_string', $validate_string)->first();
            
            if (!empty($userDetail)) {
                return View::make('admin.login.reset_password', compact('validate_string'));
            } else {
                return Redirect::to('/admin')->with('error', trans('messages.Sorry, you are using wrong link.'));
            }
            
        } else {
            return Redirect::to('/admin')->with('error', trans('messages.Sorry, you are using wrong link.'));
        }
    } // end resetPassword()
    
    /**
     * Function is used to send email for forgot password process
     *
     * @param null
     *
     * @return url. 
     */
    public function sendPassword()
    {
        
        $messages = array(
            'email.required' => trans('messages.The email field is required.'),
            'email.email' => trans('messages.The email must be a valid email address.')
        );
        
        $validator = Validator::make(Input::all(), array(
            'email' => 'required|email'
        ), $messages);
        
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput()->with(compact(''));
        } else {
            $email      = Input::get('email');
            $userDetail = DB::table('users')->where('email', $email)->where('id', config("constants.ADMIN_ID"))->first();
            if (!empty($userDetail)) {
                if ($userDetail->active == 1) {
                    if ($userDetail->is_verified == 1) {
                        
                        $forgot_password_validate_string = md5($userDetail->email);
                        DB::table('users')->where('email', $email)->update(array(
                            'forgot_password_validate_string' => $forgot_password_validate_string
                        ));
                        
                        $settingsEmail = Config::get('Site.email');
                        $email         = $userDetail->email;
                        
                        $full_name   = $userDetail->full_name;
                        $route_url   = URL::to('admin/reset_password/' . $forgot_password_validate_string);
                        $varify_link = $route_url;
                        
                        $emailActions   = EmailAction::where('action', '=', 'forgot_password')->get()->toArray();
                        $emailTemplates = EmailTemplate::where('action', '=', 'forgot_password')->get(array(
                            'name',
                            'subject',
                            'action',
                            'body'
                        ))->toArray();
                        $cons           = explode(',', $emailActions[0]['options']);
                        $constants      = array();
                        
                        foreach ($cons as $key => $val) {
                            $constants[] = '{' . $val . '}';
                        }
                        $subject     = $emailTemplates[0]['subject'];
                        $rep_Array   = array(
                            $full_name,
                            $varify_link,
                            $route_url
                        );
                        $messageBody = str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
                        
                        $this->sendMail($email, $full_name, $subject, $messageBody, $settingsEmail);
                        Session::flash('flash_notice', trans('messages.An email has been sent to your inbox. To reset your password please follow the steps mentioned in the email.'));
                        return Redirect::to('/admin');
                    } else {
                        return Redirect::back()->with('error', trans('messages.Your account has not been verified yet.'));
                    }
                } else {
                    return Redirect::back()->with('error', trans('messages.Your account has been temporarily disabled. Please contact administrator to unlock.'));
                }
            } else {
                return Redirect::back()->with('error', trans('messages.Your email is not registered with Cunto Online.'));
            }
        }
    } // sendPassword()    
    
    /**
     * Function is used for save reset password
     *
     * @param $validate_string as validator string
     *
     * @return view page. 
     */
    public function resetPasswordSave($validate_string = null)
    {
        
        $newPassword     = Input::get('new_password');
        $validate_string = Input::get('validate_string');
        
        $messages = array(
            'new_password.required' => trans('messages.The New Password field is required.'),
            'new_password_confirmation.required' => trans('messages.The confirm password field is required.'),
            'new_password.confirmed' => trans('messages.The confirm password must be match to new password.'),
            'new_password.min' => trans('messages.The password must be at least 6 characters.'),
            'new_password_confirmation.min' => trans('messages.The confirm password must be at least 6 characters.')
        );
        
        $validator = Validator::make(Input::all(), array(
            'new_password' => 'required|min:6|confirmed',
            'new_password_confirmation' => 'required|min:6'
            
        ), $messages);
        if ($validator->fails()) {
            return Redirect::to('admin/reset_password/' . $validate_string)->withErrors($validator)->withInput()->with(compact(''));
        } else {
            $userInfo = DB::table('users')->where('forgot_password_validate_string', $validate_string)->first();
            
            DB::table('users')->where('forgot_password_validate_string', $validate_string)->update(array(
                'password' => Hash::make($newPassword),
                'forgot_password_validate_string' => ''
            ));
            $settingsEmail = Config::get('Site.email');
            $action        = "reset_password";
            
            $emailActions   = EmailAction::where('action', '=', 'reset_password')->get()->toArray();
            $emailTemplates = EmailTemplate::where('action', '=', 'reset_password')->get(array(
                'name',
                'subject',
                'action',
                'body'
            ))->toArray();
            $cons           = explode(',', $emailActions[0]['options']);
            $constants      = array();
            foreach ($cons as $key => $val) {
                $constants[] = '{' . $val . '}';
            }
            
            $subject     = $emailTemplates[0]['subject'];
            $rep_Array   = array(
                $userInfo->full_name
            );
            $messageBody = str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
            
            $this->sendMail($userInfo->email, $userInfo->full_name, $subject, $messageBody, $settingsEmail);
            Session::flash('flash_notice', trans('messages.Thank you for resetting your password. Please login to access your account.'));
            
            return Redirect::to('/admin');
        }
    } // end resetPasswordSave()
    
} // end AdminLoginController

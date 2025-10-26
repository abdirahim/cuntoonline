<?php

/**
 * MyaccountController Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/myaccount
 */
namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;

class MyaccountController extends BaseController
{
    
    /**
     * Function for display edit user profile
     *
     * @param null
     *
     * @return view page. 
     */
    public function editProfile()
    {
        if (Request::ajax()) {
            $formData = Input::all();
            if (!empty($formData)) {
                
                $ValidationRule = array(
                    'full_name' => 'required',
                    'email' => 'required|email'
                );
                
                $email_check = User::where('id', Auth::user()->id)->pluck('email');
                
                if ($email_check == '') {
                    $rules          = array(
                        'email' => 'required|email|unique:users,email,NULL,id,is_deleted,0'
                    );
                    $ValidationRule = array_merge($ValidationRule, $rules);
                }
                
                $get_password         = Input::get('password');
                $get_confirm_password = Input::get('confirm_password');
                
                if ($get_password != '' || $get_confirm_password != '') {
                    
                    $rules          = array(
                        'password' => 'required|min:6',
                        'confirm_password' => 'required|min:6|same:password'
                    );
                    $ValidationRule = array_merge($ValidationRule, $rules);
                }
                
                $validator = Validator::make(Input::all(), $ValidationRule);
                
                if ($validator->fails()) {
                    $allErrors = '<ul>';
                    foreach ($validator->errors()->all('<li>:message</li>') as $message) {
                        $allErrors .= $message;
                    }
                    
                    $allErrors .= '</ul>';
                    $response = array(
                        'success' => false,
                        'errors' => $allErrors
                    );
                    return Response::json($response);
                    die;
                } else {
                    
                    $obj = User::find(Auth::user()->id);
                    if (!empty($get_password) && !empty($get_confirm_password) && $get_password == $get_confirm_password) {
                        $obj->password = Hash::make(Input::get('password'));
                    }
                    $obj->full_name = Input::get('full_name');
                    if ($email_check == '') {
                        $obj->email = Input::get('email');
                    }
                    $obj->save();
                    $response = array(
                        'success' => '1'
                    );
                    return Response::json($response);
                    die;
                }
            }
        }
        
        $email = User::where('id', Auth::user()->id)->pluck('email');
        View::share('pageTitle', Auth::user()->full_name . ' : Profile');
        return View::make('myaccount.edit_profile', compact('email'));
    } //end editProfile()
    
} // end MyaccountController class

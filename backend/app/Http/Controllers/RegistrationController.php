<?php

/**
 * Registration Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/registration
 */
namespace App\Http\Controllers;

use Illuminate\Routing\Controller as Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\User;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Hash;
use App\EmailAction;
use App\EmailTemplate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Exception;

class RegistrationController extends BaseController
{
    /**
     * Function for display registration page
     *
     * @param null
     *
     * @return view page.
     */

    public function getIndex()
    {
        View::share('pageTitle', 'Sign Up');
        return View::make('registration.index');
    } // end getIndex()

    /**
     * Function for save user registration details
     *
     * @param null
     *
     * @return url.
     */
    public function postIndex()
    {

        if (Request::ajax()) {

            $formData = Input::all();
            if (!empty($formData)) {

                // validation rule
                $validationRules = array (
                    'full_name' => 'required',
                    'email' => 'required|email|unique:users,email,NULL,id,is_deleted,0',
                    'password' => 'required|min:6',
                    'confirm_password' => 'required|min:6|same:password'
                );

                $validator = Validator::make(Input::all(), $validationRules);

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

                    $fullName             = ucwords(Input::get('full_name'));
                    $obj                  = new User;
                    $validateString       = md5(time() . Input::get('email'));
                    $obj->validate_string = $validateString;

                    $obj->full_name    = $fullName;
                    $obj->email        = Input::get('email');
                    $obj->slug         = $this->getSlug($fullName, 'User');
                    $obj->password     = Hash::make(Input::get('password'));
                    $obj->user_role_id = config("constants.FRONT_USER");
                    $obj->is_verified  = config("constants.UNVERIFIED");
                    $obj->active       = config("constants.ACTIVE");

                   //var_dump($obj);exit;
                    $obj->save();

                    // send email verification mail
                    $emailActions = EmailAction::where('action', '=', 'account_verification')->get()->toArray();

                    $emailTemplates = EmailTemplate::where('action', '=', 'account_verification')->get(array(
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

                    $loginLink = config("constants.WEBSITE_URL") . 'account-verification/' . $validateString . "?enc=" . md5(time());

                    $verificationUrl = $loginLink;
                    $loginLink       = '<a style="font-weight:bold;text-decoration:none;color:#000;" target="_blank" href="' . $loginLink . '">Click here</a>';

                    $subject   = $emailTemplates[0]['subject'];
                    $rep_Array = array(
                        $fullName,
                        $loginLink,
                        $verificationUrl
                    );

                    $messageBody = str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
                  // error_log(__LINE__.Input::get('email').' '.$fullName.' '.$subject.' '.$messageBody);
//                    try {
                    $this->sendMail(Input::get('email'), $fullName, $subject, $messageBody);

//                    } catch (Exception $e) {
//
//                        return $e->getMessage();
//                    }

                    $response = array(
                        'success' => '1'
                    );
                    return Response::json($response);
                    die;
                }
            }
        }
    } // end postIndex()

    /**
     * Function for send verification link to user by email
     *
     * @param $validate_string as validate string which stored in database
     *
     * @return url.
     */
    public function send_verification_link($validate_string = null)
    {
        if ($validate_string == '') {
            return Redirect::to('/')->with('error', trans('messages.wrong.link'));
        }

        $userDetail   = DB::table('users')->where('active', 1)->where('validate_string', $validate_string)->first();
        $emailActions = EmailAction::where('action', '=', 'account_verification')->get()->toArray();

        $emailTemplates = EmailTemplate::where('action', '=', 'account_verification')->get(array(
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

        $username        = Input::get('firstname');
        $loginLink       = URL::to('account-verification/' . $validate_string);
        $verificationUrl = $loginLink;
        $loginLink       = '<a style="font-weight:bold;text-decoration:none;color:#000;" target="_blank" href="' . $loginLink . '">click here</a>';
        //var_dump($userDetail);exit;
        $subject   = $emailTemplates[0]['subject'];
        $rep_Array = array(
            $userDetail->full_name,
            $loginLink,
            $verificationUrl
        );

        $messageBody = str_replace($constants, $rep_Array, $emailTemplates[0]['body']);

        $this->sendMail($userDetail->email, $userDetail->full_name, $subject, $messageBody);
        Session::flash('flash_notice', trans('messages.sent_verification_msg'));
        return Redirect::to('/login');
    } // end send_verification_link()

    /**
     * Function for user account verification
     *
     * @param $validate_string as validate string
     *
     * @return void
     */

    function accountVerification($validate_string = '')
    {
        $userInfo = User::where('validate_string', '=', $validate_string)->get(array(
            'validate_string',
            'is_verified',
            'id',
            'email',
            'full_name'
        ))->toArray();
      //  var_dump($userInfo);exit;
        if (!empty($userInfo)) {
           // var_dump($userInfo['0']['is_verified']);exit();
            if ($userInfo['0']['is_verified'] == 1) {
                Session::flash('flash_notice', trans('messages.account_already_verify_msg'));
                return Redirect::to('/login');
            } else {
                User::where('id', $userInfo['0']['id'])->update(array(
                    'validate_string' => '',
                    'is_verified' => config("constants.VERIFIED"),
                    'active' => config("constants.ACTIVE")
                ));

                Session::flash('flash_notice', trans('messages.account_verified_msg'));
                return Redirect::to('/login');
            }
        } else {
            Session::flash('error', trans('messages.wrong.link'));
            return Redirect::to('/');
        }
    } // end accountVerification()

} // end RegistrationController class

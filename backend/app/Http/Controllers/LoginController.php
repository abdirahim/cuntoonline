<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use URL;
use Response;
use Session;
use Redirect;
use OAuth;


class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @return Response
     */

    public function login($validate_string = '')
    {
        ###/ redirect to same page after login ###
        $redirect_filter = Input::get('redirect', 0);
//        if (Request::ajax()) {
        if(request()->ajax()){


            $formData = Input::all();

            if (!empty($formData)) {


                ### validation rule ###
                $validator = Validator::make(Input::all(), array(
                    'email' => 'required|email',
                    'password' => 'required|min:6'
                ));

                $redirectUrl = '';

                if ($validator->fails()) {


                    $allErrors = '<ul>';
                    foreach ($validator->errors()->all('<li>:message</li>') as $message) {
                        $allErrors .= $message;
                    }
                    $allErrors .= '</ul>';
                    $success      = false;
                    $errorMessage = $allErrors;

                } else {

                    $userdata = array(
                        'email' => Input::get('email'),
                        'password' => Input::get('password'),
                        'is_deleted' => 0
                    );

                    $remember = (Input::has('remember_me')) ? true : false;
                    if (Auth::attempt($userdata, $remember)) {



                        ###  check that user is direct login or redirect to other side ###

                        if (Auth::user()->active == '1') {

                            if (Auth::user()->is_verified == '1') {
                                ### login table entry ###
                                if (Auth::user()->id != config("constants.ADMIN_ID") ) {
                                    DB::table('users_login')->insert(array(
                                        'user_id' => Auth::user()->id,
                                        'created_at' => DB::raw('NOW()')
                                    ));
                                }

                                $url          = URL::to('/');
                                $success      = 1;
                                $errorMessage = '';
                                $redirectUrl  = $url;
                            } else {
                                $link              = URL::to('/send-verification-link');
                                $validateString    = Auth::user()->validate_string;
                                $success           = false;
                                $errorVerification = trans("messages.email_verification_msg");
                                $clickhere         = trans("messages.click here");

                                $errorMessage = $errorVerification . ' <a style="color:#00FF7F" href="' . $link . '/' . $validateString . '">' . $clickhere . '</a>';
                                Auth::logout();
                            }
                        } else {
                            $success      = false;
                            $errorMessage = trans("messages.account_disable_msg");
                            Auth::logout();
                        }

                    } else {

                        $success      = false;
                        $errorMessage = trans("messages.credential_incorrect");
                    }

                }

                $response = array(
                    'success' => $success,
                    'redirect' => $redirectUrl,
                    'errors' => trans("messages.$errorMessage"),
                    'redirect_filter' => $redirect_filter
                );

                return Response::json($response);
                die;
            }
        }
        if (Auth::check()) {
            dd('auth check');
            return Redirect::to('/');
        }

        View::share('pageTitle', 'Login');
        return View::make('login', array(
            'validate_string' => $validate_string,
            'redirect_filter' => $redirect_filter
        ));

    } // end login()


//    public function authenticate()
//    {
//        if (Auth::attempt(['email' => $email, 'password' => $password])) {
//            // Authentication passed...
//            return redirect()->intended('dashboard');
//        }
//    }


/**
 * Function is used to send email for forget password process
 *
 * @param null
 *
 * @return json response.
 */
public function forgetPassword()
{
    if (Request::ajax()) {

        $validator = Validator::make(Input::all(), array(
            'email' => 'required|email'
        ));

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
        } else {
            $email      = Input::get('email');
            $userDetail = User::where('email', $email)->first();
            if (!empty($userDetail)) {
                if ($userDetail->active == 1) {
                    if ($userDetail->is_verified == 1) {
                        $forgot_password_validate_string = md5($userDetail->email);
                        User::where('email', $email)->update(array(
                            'forgot_password_validate_string' => $forgot_password_validate_string
                        ));

                        $settingsEmail = Config::get('Site.email');
                        $email         = $userDetail->email;
                        $username      = $userDetail->full_name;
                        $full_name     = $userDetail->full_name;
                        $route_url     = URL::to('reset_password/' . $forgot_password_validate_string);
                        $varify_link   = $route_url;

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
                            $username,
                            $varify_link,
                            $route_url
                        );
                        $messageBody = str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
                        $this->sendMail($email, $full_name, $subject, $messageBody, $settingsEmail);

                        $response = array(
                            'success' => 1
                        );
                    } else {

                        $link              = URL::to('/send-verification-link');
                        $validateString    = User::where('email', $email)->pluck('validate_string');
                        $success           = false;
                        $errorVerification = trans("messages.email_verification_msg");
                        $clickhere         = trans("messages.click here");
                        if ($validateString) {
                            $errorMessage = $errorVerification . ' <a style="color:#00FF7F" href="' . $link . '/' . $validateString . '">' . $clickhere . '</a>';
                            Auth::logout();
                        } else {
                            $errorMessage = trans("messages.account_not_verified");
                        }
                        $response = array(
                            'success' => false,
                            'errors' => $errorMessage
                        );
                    }
                } else {
                    $response = array(
                        'success' => false,
                        'errors' => trans("messages.account_disable_msg")
                    );
                }
            } else {
                $response = array(
                    'success' => false,
                    'errors' => trans("messages.email_not_register_msg")
                );
            }
        }
        return Response::json($response);
        die;
    } else {
        View::share('pageTitle', 'Forget Password');
        return View::make('registration.forget_password');
    }
} // forgetPassword()

/**
 * Function is used to show reset password page
 *
 * @param $validate_string as validate string which stored in database
 *
 * @return view page.
 */
public function resetPassword($validate_string = null)
{
    if ($validate_string != "" && $validate_string != null) {
        $userDetail = User::where('active', 1)->where('forgot_password_validate_string', $validate_string)->first();
        if (!empty($userDetail)) {
            return View::make('registration.reset_password', compact('validate_string'));
        } else {
            return Redirect::to('/')->with('error', trans('messages.wrong.link'));
        }
    } else {
        return Redirect::to('/')->with('error', trans('messages.wrong.link'));
    }
} // end resetPassword()

/**
 * Function is used to reset user current password
 *
 * @param $validate_string as validate string which stored in database
 *
 * @return url.
 */
public function resetPasswordSave($validate_string = null)
{


    $formData = Input::all();
    if (!empty($formData)) {
        $newPassword     = Input::get('new_password');
        $validate_string = Input::get('validate_string');

        $validator = Validator::make(Input::all(), array(
            'new_password' => 'required|min:6|confirmed',
            'new_password_confirmation' => 'required|min:6'

        ));

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
        } else {

            $userInfo = User::where('forgot_password_validate_string', $validate_string)->first();

            User::where('forgot_password_validate_string', $validate_string)->update(array(
                'password' => Hash::make($newPassword),
                'forgot_password_validate_string' => ''
            ));

            ####### mail to user that password has been change successfully  #######

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

            $subject   = $emailTemplates[0]['subject'];
            $rep_Array = array(
                $userInfo->full_name
            );

            $messageBody = str_replace($constants, $rep_Array, $emailTemplates[0]['body']);

            $this->sendMail($userInfo->email, $userInfo->full_name, $subject, $messageBody, $settingsEmail);

            $response = array(
                'success' => 1
            );
            return Response::json($response);
        }
    }

} // end resetPasswordSave()

/**
 * Function is used to logout from account
 *
 * @param null
 *
 * @return url.
 */

public function logout()
{
    if (Auth::check()) {
        Session::forget('cart_item');
        Session::forget('deliver_detail');
        Auth::logout();
        Session::flash('logout', trans('messages.logout_msg'));
    }
    return Redirect::to('/');
} // end logout()

/**
 * Function for login with facebook
 *
 * @param null
 *
 * @return view page.
 */

public function loginWithFacebook()
{
    // get data from input
    $code = Input::get('code');

    // get fb service
    $fb = OAuth::consumer('Facebook');

    // check if code is valid

    // if code is provided get user data and sign in

    if (!empty($code)) {
        // This was a callback request from facebook, get the token
        $token = $fb->requestAccessToken($code);

        // Send a request with it
        $result = json_decode($fb->request('/me?fields=id,name,email'), true);

        $user = User::where('email', $result['email'])->where('is_deleted', 0)->first();

        if (empty($user)) {
            $userData['email']        = $result['email'];
            $userData['full_name']    = $result['name'];
            $userData['facebook_id']  = $result['id'];
            $userData['active']       = 1;
            $userData['is_verified']  = 1;
            $userData['slug']         = $this->getSlug($result['name'], 'User');
            $userData['user_role_id'] = FRONT_USER;
            $userData['created_at']   = DB::raw('NOW()');
            $userData['updated_at']   = DB::raw('NOW()');
            $userId                   = User::insertGetId($userData);
            Auth::loginUsingId($userId);
            ####### login table entry #######
            if (Auth::user()->user_role_id != ADMIN_ID) {
                Userlogin::insert(array(
                    'user_id' => Auth::user()->id,
                    'created_at' => DB::raw('NOW()')
                ));
            }
            $yourURL = URL::to('/');
            echo ("<script>location.href='$yourURL'</script>");
        } else {
            if ($user->active == '0') {
                Session::flash('error', trans("messages.account_disable_msg"));
                echo ("<script>location.href='$yourURL'</script>");
            } else {
                Auth::loginUsingId($user->id);
                $yourURL = URL::to('/');
                echo ("<script>location.href='$yourURL'</script>");
            }
        }
    } else {
        $errorFacebook = Input::get('error');
        $errorTwitter  = Input::get('denied');
        $error_code    = Input::get('error_code');
        $error_message = Input::get('error_message');

        if ($error_code != '' && $error_message != '') {
            return Redirect::to('/')->with('error', $error_message);
        }

        if ($errorFacebook != '' || $errorTwitter != '') {
            return Redirect::to('/');
        }

        // get fb authorization
        $url = $fb->getAuthorizationUri();

        // return to facebook login url
        return Redirect::to((string) $url);
    }
}

/**
 * Function for login with google
 *
 * @param null
 *
 * @return json reponse.
 */
public function loginWithGoogle()
{

    // get data from input
    $code = Input::get('code');

    OAuth::setHttpClient('CurlClient');
    // get google service
    $googleService = OAuth::consumer('Google', Config::get('setting_social.google_redirect_url'));

    // check if code is valid

    // if code is provided get user data and sign in
    if (!empty($code)) {

        // This was a callback request from google, get the token
        $token = $googleService->requestAccessToken($code);

        // Send a request with it
        $result = json_decode($googleService->request('https://www.googleapis.com/oauth2/v1/userinfo'), true);

        $user = User::where('email', $result['email'])->where('is_deleted', 0)->first();

        if (empty($user)) {
            $userData['email']        = $result['email'];
            $userData['full_name']    = $result['name'];
            $userData['facebook_id']  = $result['id'];
            $userData['active']       = 1;
            $userData['is_verified']  = 1;
            $userData['slug']         = $this->getSlug($result['name'], 'User');
            $userData['user_role_id'] = FRONT_USER;
            $userData['created_at']   = DB::raw('NOW()');
            $userData['updated_at']   = DB::raw('NOW()');
            $userId                   = User::insertGetId($userData);
            Auth::loginUsingId($userId);
            ####### login table entry #######
            if (Auth::user()->user_role_id != ADMIN_ID) {
                Userlogin::insert(array(
                    'user_id' => Auth::user()->id,
                    'created_at' => DB::raw('NOW()')
                ));
            }
            $yourURL = URL::to('/');
            echo ("<script>location.href='$yourURL'</script>");
        } else {
            if ($user->active == '0') {
                Session::flash('error', trans("messages.account_disable_msg"));
                echo ("<script>location.href='$yourURL'</script>");
            } else {
                Auth::loginUsingId($user->id);
                $yourURL = URL::to('/');
                echo ("<script>location.href='$yourURL'</script>");
            }
        }
    }
    // if not ask for permission first
    else {
        // get googleService authorization
        $url = $googleService->getAuthorizationUri();

        // return to google login url
        return Redirect::to((string) $url);
    }
}

/**
 * Function for login with twitter
 *
 * @param null
 *
 * @return json reponse.
 */
public function loginWithTwitter()
{
    // get data from input
    $token  = Input::get('oauth_token');
    $verify = Input::get('oauth_verifier');

    // get twitter service
    $tw = OAuth::consumer('Twitter');

    // check if code is valid

    // if code is provided get user data and sign in
    if (!empty($token) && !empty($verify)) {

        // This was a callback request from twitter, get the token
        $token = $tw->requestAccessToken($token, $verify);

        // Send a request with it
        $result = json_decode($tw->request('account/verify_credentials.json'), true);

        $user = User::where('twitter_id', $result['id'])->where('is_deleted', 0)->first();

        if (empty($user)) {
            $userData['email']        = '';
            $userData['full_name']    = $result['name'];
            $userData['twitter_id']   = $result['id'];
            $userData['active']       = 1;
            $userData['is_verified']  = 1;
            $userData['slug']         = $this->getSlug($result['name'], 'User');
            $userData['user_role_id'] = FRONT_USER;
            $userData['created_at']   = DB::raw('NOW()');
            $userData['updated_at']   = DB::raw('NOW()');
            $userId                   = User::insertGetId($userData);
            Auth::loginUsingId($userId);
            ####### login table entry #######
            if (Auth::user()->user_role_id != ADMIN_ID) {
                Userlogin::insert(array(
                    'user_id' => Auth::user()->id,
                    'created_at' => DB::raw('NOW()')
                ));
            }
            $yourURL = URL::to('/');
            echo ("<script>location.href='$yourURL'</script>");
        } else {
            if ($user->active == '0') {
                Session::flash('error', trans("messages.account_disable_msg"));
                echo ("<script>location.href='$yourURL'</script>");
            } else {
                Auth::loginUsingId($user->id);
                $yourURL = URL::to('/');
                echo ("<script>location.href='$yourURL'</script>");
            }
        }
    } else {
        // get request token
        $reqToken = $tw->requestRequestToken();

        // get Authorization Uri sending the request token
        $url = $tw->getAuthorizationUri(array(
            'oauth_token' => $reqToken->getRequestToken()
        ));

        // return to twitter login url
        return Redirect::to((string) $url);
    }
} //end loginWithTwitter()

} // end LoginController class


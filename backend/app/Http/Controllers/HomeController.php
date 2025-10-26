<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Restaurant;
use Illuminate\Support\Facades\View;
use App\AdminDropDown;
use Auth;
use Session;
use Response;
use App\Cms;

class HomeController extends BaseController
{

    /**
     * Function to set lat,long in session
     *
     * @param null
     *
     * @return void
     */

    public function setLatLong()
    {
        Session::put('lat', Request::get('lat'));
        Session::put('lng', Request::get('lng'));
        Session::put('location', '1');
    } //end setLatLong()

    /**
     * Function to display website home page
     *
     * @param $categorySlug as cuisine slug
     *
     * @return view page
     */
    public function index(Request $request, $categorySlug = '')
    {
        $search   = $request->input('name');

        $cuisines = DB::select(DB::raw("SELECT *,(select count(*) from `restaurants` where FIND_IN_SET(dropdown_managers.id,cuisine)  and `is_active`= 1 ) as `count` FROM `dropdown_managers` WHERE `dropdown_type`='cuisine' and `is_display`=1"));

        $categoryId = 'all';

        if ($categorySlug != '') {
            $categoryId = DropDown::where('slug', $categorySlug)->where('is_display',1)->pluck('id');
        }

        if ($categoryId == '') {
            return View::make('errors.404');
        }

        $restaurants = Restaurant::getResult($search, $categorySlug);

//        dd($restaurants);

//        #relations: array:2 [▼
//        "restaurantTime" => RestaurantTime {#382 ▼
//        +timestamp: fals
//          #attributes: array:7 [▼
//            "id" => 209
//            "restaurant_id" => 56
//            "week_day" => "Wednesday"
//            "open_time" => "2:00 PM"
//            "close_time" => "11:00PM"
//            "created_at" => "2022-01-25 21:31:58"
//            "updated_at" => "2022-01-25 21:31:58

        //dd($restaurants);
//        foreach($restaurants as $restaurant) {
//            dd($restaurant->restaurantTime->week_day);
//        }


        return View::make('index', compact('restaurants', 'cuisines', 'categoryId'));

    } //end index()

    /**
     * Function to display restaurant detail page
     *
     * @param $slug as restaurant slug
     *
     * @return view page
     */
    public function restaurantDetail(Request $request, $slug = '')
    {
        Session::forget('cart_item.deliver_type');
//        $deliverType = Request::get('deliver_type');
        $deliverType = $request->input('deliver_type');
        $cartDetail  = (Session::has('cart_item')) ? Session::get('cart_item') : array();

        $cart                = (!empty($cartDetail)) ? $cartDetail : array();
        $displayDeliveryType = true;
        $checkoutButton      = true;
        $restaurantDetail    = Restaurant::with('meals')->where('slug', $slug)->first();
        if (empty($restaurantDetail)) {
            return View::make('errors.404');
        }
        View::share('pageTitle', $restaurantDetail->name);
        $foodMenu       = explode(',', $restaurantDetail->food_menu);
//        $foodCategories = AdminDropDown::where('dropdown_type', 'food-category')->whereIn('id', $foodMenu)->lists('name', 'id');
        $foodCategories = AdminDropDown::where('dropdown_type', 'food-category')->whereIn('id', $foodMenu)->pluck('name', 'id');

        $check_rating_exist = 0;

        if (Auth::check()) {
            $check_rating_exist = DB::table('restaurant_reviews')->where('user_id', Auth::user()->id)->where('restaurant_id', $restaurantDetail->id)->where('rating', '<>', '')->count();
        }

        $review_list = DB::table('restaurant_reviews')->where('restaurant_id', $restaurantDetail->id)->where('is_display', 1)->orderBy('created_at', 'DESC')->get();

        $openTimeDetail  = DB::table('restaurant_time')->where('restaurant_id', $restaurantDetail->id)->get();
        $editDetailArray = array();


       // dd(array($openTimeDetail));
        if (!empty($openTimeDetail)) {
            foreach ($openTimeDetail as $value2) {
                 // dd($value2);
              $editDetailArray["$value2->week_day"] = $value2;
            }
        }

        $avgRating = ceil(number_format(DB::table('restaurant_reviews')->where('restaurant_id', $restaurantDetail->id)->where('rating', '<>', '0')->avg('rating'), 1));
        return Response::view('restaurant_detail', compact('restaurantDetail', 'cart', 'deliverType', 'displayDeliveryType', 'checkoutButton', 'foodCategories', 'review_list', 'check_rating_exist', 'editDetailArray', 'avgRating'))->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
    } //end restaurantDetail()

    /**
     * Function to display contact us page
     *
     * @param null
     *
     * @return view page
     */
    public function contactUs()
    {
        $result = Cms::getResult('contact-us');
        if (empty($result)) {
            if (empty($cmsPagesDetail)) {
                return Redirect::to('/');
            }
        }
        return View::make('cms.contact_us', compact('result'));

    } //end contactUs()

    /**
     * Function to display cms page on website
     *
     * @param slug as slug of cms page
     *
     * @return view page
     */
    public function showCms($slug)
    {
        $result = Cms::getResult($slug);
        //dd($result);
        if (empty($result)) {
            return View::make('errors.404');
        }
        return View::make('cms.index', compact('result'));
    } //end showCms()

    /**
     * Function for save review and rating for restaurant
     *
     * @param $restaurantId as restaurant id
     *
     * @return view page.
     */
    public function saveReviews($restaurantId)
    {

        if (Request::has('score')) {
            $rating = Request::get('score');
        } else {
            $rating = 0;
        }
        $validationRules = array();
        $validationRules = array(
            'comment' => 'required'
        );

        $messages = array(
            'comment.required' => trans('messages.The comment field is required.')
        );

        $validator = Validator::make(Request::all(), $validationRules, $messages);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        } else {
            DB::table('restaurant_reviews')->insert(array(
                'user_id' => Auth::user()->id,
                'restaurant_id' => $restaurantId,
                'name' => Auth::user()->full_name,
                'comment' => trim(nl2br(strip_tags(Input::get('comment')))),
                'rating' => (Input::get('score')) ? Input::get('score') : 0,
                'created_at' => DB::raw('NOW()')
            ));
            $slug = Restaurant::where('id', $restaurantId)->pluck('slug');
            // Redirect to latest comment
            return Redirect::to('restaurant-detail/' . $slug . '#for_comment');
        }
    } // end saveReviews()

}

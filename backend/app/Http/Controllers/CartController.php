<?php
/**
 * CartController Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views
 */
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\AdminDropDown;
use Auth;
use Session;
use Response;
use Illuminate\Support\Facades\Input;
use App\RestaurantMeal;
use App\UserAddress;
use App\Block;
use Illuminate\Support\Facades\Validator;
use App\Order;
use App\OrderItem;
use Redirect;



class CartController extends BaseController
{
    
    /** 
     * Function for add items into cart
     *
     * @param null
     * 
     * @return json array
     */
    public function addCart()
    {
        $specificInstruction       = Input::get('specific_instruction');
        $addType                   = Input::get('type');
        $meal_id                   = Input::get('meal_id');
        $restaurantId              = RestaurantMeal::where('id', $meal_id)->value('restaurant_id');
        $foodCategory              = Input::get('food_category');
        $sessionCart               = Session::get('cart_item', array());
        $specificInstruction       = Input::get('specific_instruction');
        $deliverType               = Input::get('deliver_type');
        $userCart                  = array();
        $userCart['items']         = array();
        $sessionCart               = (Session::has('cart_item')) ? Session::get('cart_item') : $userCart;
        $isDisplayDeliveryButton   = Input::get('deliver_button', false);
        $isDisplayCollectionButton = Input::get('collection_button', false);

        
        switch ($addType) {
            case 'plus':
                $uniqueProductId                                    = Input::get('unique_product_id', $meal_id);
                $sessionCart['items'][$uniqueProductId]["quantity"] = $sessionCart['items'][$uniqueProductId]['quantity'] + 1;
                break;
            case 'minus':
                $uniqueProductId = Input::get('unique_product_id', $meal_id);
                if ($sessionCart['items'][$uniqueProductId]['quantity'] - 1 <= 0) { // if quantity is less then 1 then unset from array
                    unset($sessionCart['items'][$uniqueProductId]);
                } else {
                    $sessionCart['items'][$uniqueProductId]["quantity"] = $sessionCart['items'][$uniqueProductId]['quantity'] - 1;
                }
                
                break;
            default:
                ### product add ###
                $productId       = Input::get('meal_id', $meal_id);
                $uniqueProductId = rand(1, 100000);
                $productDetail   = RestaurantMeal::where('id', $productId)->first();


                
                $itemArray = array(
                    'name' => $productDetail->name,
                    'restaurant_id' => $restaurantId,
                    'quantity' => 1,
                    'specific_instruction' => $specificInstruction,
                    'meal_id' => $productDetail->id,
                    'price' => $productDetail->price,
                    'food_category' => $foodCategory
                );
                
                $sessionCart['items'][$uniqueProductId] = $itemArray;
        }
        
        $displayDeliveryType = Input::get('displayDeliveryType');
        $checkoutButton      = Input::get('checkoutButton');
        
        Session::put('cart_item', $sessionCart);
        $cart = $sessionCart;
        
        $countCart = count($cart['items']);
        
        $response = array(
            'countCart' => $countCart,
            'htmlcart' => (String) View::make('elements.cart', compact('cart', 'displayDeliveryType', 'checkoutButton', 'isDisplayDeliveryButton', 'isDisplayCollectionButton'))
        );
        
        return Response::json($response);
    } //end addCart
    
    /** 
     * Function to display deliver detail page and save detail
     *
     * @param null
     * 
     * @return view page
     */
    public function deliverDetail()
    {
        
        ### add new address ###
//        if (Request::ajax()) {
        if(request()->ajax()){
            $allData = Input::all();
            if (!empty($allData)) {
                $validator = Validator::make(Input::all(), array(
                    'deliver_address' => 'required'
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
                    
                    $address     = Input::get('deliver_address');
                    $deliverDate = Input::get('deliver_date');
                    $deliverTime = Input::get('deliver_time');
                    $instruction = Input::get('instruction');
                    $deliverType = Input::get('deliver_type');
                    
                    $deliverDetail = array(
                        'address' => $address,
                        'deliverDate' => $deliverDate,
                        'deliverTime' => $deliverTime,
                        'instruction' => $instruction,
                        'deliver_type' => $deliverType
                    );
                    
                    Session::put('deliver_detail', $deliverDetail);
                    
                    $response = array(
                        'success' => true
                    );
                    return Response::json($response);
                }
                
            }
        } else {
            $cart          = Session::get('cart_item');
            $countAddress  = UserAddress::where('user_id', Auth::user()->id)->count();
            $userAddresses = UserAddress::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
            if (empty($cart)) {
                Session::flash('error', trans("messages.cart_empty_error"));
                return Redirect::to('/');
            }
            View::share('pageTitle', 'Delivery Details');
            return View::make('deliver_detail', compact('cart', 'countAddress', 'userAddresses'));
        }
    } //end deliverDetail()
    
    /** 
     * Function  for add address
     *
     * @param null
     * 
     * @return json response
     */
    public function addAddress()
    {
        $allData = Input::all();
        
//        if (Request::ajax()) {
        if(request()->ajax()){
            if (!empty($allData)) {
                $validator = Validator::make(Input::all(), array(
                    'full_name' => 'required',
                    'phone_number' => 'required|regex:/(^([0-9]+[-]*[0-9])*$)/',
                    'address' => 'required'
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
                    die;
                } else {
                    UserAddress::insert(array(
                        'user_id' => Auth::user()->id,
                        'full_name' => Input::get('full_name'),
                        'pin_code' => Input::get('pin_code'),
                        'phone_number' => Input::get('phone_number'),
                        'address' => Input::get('address'),
                        'created_at' => date('Y-m-d H:i:s',time()),
                        'updated_at' => date('Y-m-d H:i:s',time())
                    ));
                    $response = array(
                        'success' => true
                    );
                }
                return Response::json($response);
            }
        }
    } //end addAddress()
    
    /** 
     * Function  for view payment page
     *
     * @param null
     * 
     * @return view page
     */
    public function payment()
    {
        $cart = Session::get('cart_item');
        if (empty($cart)) {
            Session::flash('error', trans("messages.cart_empty_error"));
            return Redirect::to('/');
        }
        
        $blocks = Block::getResult('payment', array(
            'id',
            'description',
            'block'
        ));
        View::share('pageTitle', 'Payment');
        return View::make('payment', compact('cart', 'blocks'));
    } //end payment()
    
    /** 
     * Function  for add order detail
     *
     * @param null
     * 
     * @return view page
     */
    public function checkout()
    {
        $validator = Validator::make(Input::all(), array(
            'payment_phone_number' => 'required|regex:/(^([0-9]+[-]*[0-9])*$)/'
        ), array(
            'payment_phone_number.regex' => 'Please enter valid phone number.'
        ));
        
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
            ;
        } else {
            $paymentType        = Input::get('payment_type');
            $paymentPhoneNumber = Input::get('payment_phone_number');
            $carts              = Session::get('cart_item');
            $deliverDetail      = Session::get('deliver_detail');
            if (empty($carts['items'])) {
                Session::flash('error', trans("messages.wrong_link"));
                return Redirect::to('/');
            }
            
            $keys         = array_keys($carts['items']);
            $firstKey     = $keys[0];
            $restaurantId = $carts['items'][$firstKey]['restaurant_id'];
            
            $deliveryType = Session::get('cart_item.deliver_type');
			
			$addressId     		= Session::get('deliver_detail.address');
			if($deliveryType == ''){
				if($addressId !=''){
					$deliveryType = config("constants.DELIVERY");
				}else{
					$deliveryType = config("constants.COLLECTION");
				}
			}
			
            if ($deliveryType == config("constants.DELIVERY")) {
                $addressId     = Session::get('deliver_detail.address');
                $addressDetail = UserAddress::where('id', $addressId)->first();
                $fullName      = $addressDetail->full_name;
                $pinCode       = $addressDetail->pin_code;
                $address       = $addressDetail->address;
                $phoneNumber   = $addressDetail->phone_number;
                
                $orderId = Order::insertGetId(array(
                    'user_id' => Auth::user()->id,
                    'full_name' => $fullName,
                    'address' => $address,
                    'phone_number' => $phoneNumber,
                    'payment_phone_number' => $paymentPhoneNumber,
                    'payment_type' => $paymentType,
                    'pin_code' => $pinCode,
                    'restaurant_id' => $restaurantId,
                    'total_amount' => Session::get('total_amount'),
                    'delivery_charges' => Session::get('delivery_charges'),
                    'shipping_type' => $deliveryType,
                    'specific_instruction' => Session::get('deliver_detail.instruction'),
                    'deliver_date' => Session::get('deliver_detail.deliverDate'),
                    'deliver_time' => Session::get('deliver_detail.deliverTime'),
                    'created_at' => date('Y-m-d H:i:s',time()), //date('Y-m-d H:i:s',time())2016-01-25 12:05:52
                    'updated_at' => date('Y-m-d H:i:s',time())
                ));
            } else {
			
				/* if($addressId){
					$deliveryType = DELIVERY;
				}else{
					$deliveryType = COLLECTION;
				} */
                $orderId = Order::insertGetId(array(
                    'user_id' => Auth::user()->id,
                    'restaurant_id' => $restaurantId,
                    'full_name' => Auth::user()->full_name,
                    'total_amount' => Session::get('total_amount'),
                    'delivery_charges' => Session::get('delivery_charges'),
                    'shipping_type' => $deliveryType,
                    'payment_phone_number' => $paymentPhoneNumber,
                    'payment_type' => $paymentType,
                    'created_at' => date('Y-m-d H:i:s',time()),
                    'updated_at' => date('Y-m-d H:i:s',time())
                ));
            }
            
            foreach ($carts['items'] as $key => $cart) {
                $restaurantId = $cart['restaurant_id'];
                OrderItem::insert(array(
                    'user_id' => Auth::user()->id,
                    'restaurant_id' => $cart['restaurant_id'],
                    'order_id' => $orderId,
                    'meal_id' => $cart['meal_id'],
                    'quantity' => $cart['quantity'],
                    'meal_name' => $cart['name'],
                    'meal_category' => $cart['food_category'],
                    'price' => $cart['price'],
                    'specific_instruction' => $cart['specific_instruction'],
                    'created_at' => date('Y-m-d H:i:s',time()),
                    'updated_at' => date('Y-m-d H:i:s',time())
                ));
            }
            
            $orderDetail    = Order::with('orderItem')->where('id', $orderId)->first();
//            $foodCategory   = OrderItem::where('order_id', $orderId)->distinct('meal_category')->lists('meal_category');
            $foodCategory   = OrderItem::where('order_id', $orderId)->distinct('meal_category')->pluck('meal_category');
//            $foodCategories = AdminDropDown::where('dropdown_type', 'food-category')->lists('name', 'id');
            $foodCategories = AdminDropDown::where('dropdown_type', 'food-category')->pluck('name', 'id');
//            $restaurantName = Config::get("Site.title");
            $restaurantName = config("constants.Site_title");
//            $messageBody    = (String) View::make('emails.order_template', compact('orderDetail', 'foodCategory', 'foodCategories', 'restaurantName'));
            $messageBody    = View::make('emails.order_template', compact('orderDetail', 'foodCategory', 'foodCategories', 'restaurantName'))->render();
            $subject        = trans("messages.received_order");
//            $this->sendMail(Config::get("Site.order_email_address"), Config::get("Site.title"), $subject, $messageBody, Config::get("Site.email"));
            $this->sendMail(config('constants.Site_order_email_address'), config('constants.Site_title'), $subject, $messageBody, config('constants.Site_email'));


            $this->sendMail('abdi_rahim1@yahoo.co.uk', config('constants.Site_title'), $subject, $messageBody, config('constants.Site_email'));

            
            Session::forget('cart_item');
            Session::forget('deliver_detail');
            Session::flash('success', trans("messages.order_success"));
            return Redirect::to('/');
        }
        
    } //end checkout()
    
    /** 
     * Function  for user address
     *
     * @param null
     * 
     * @return view page
     */
    public function userAddresses()
    {
        $userAddresses = UserAddress::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        return View::make('elements.user_addresses', compact('userAddresses'));
    } //end userAddresses()
    
    /** 
     * Function  for update delivery type
     *
     * @param null
     * 
     * @return json response
     */
    
    public function updateDeliveryType()
    {
        $userCart                    = array();
        $userCart['items']           = array();
        $sessionCart                 = (Session::has('cart_item')) ? Session::get('cart_item') : $userCart;
        $sessionCart['deliver_type'] = Input::get('deliver_type');
//        Session::set('cart_item', $sessionCart);
        Session::put('cart_item', $sessionCart);
        return Response::json(array(
            'success' => true
        ));
    } //end updateDeliveryType()
    
} // end CartController class

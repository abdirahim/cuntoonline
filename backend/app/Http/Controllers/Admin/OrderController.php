<?php

/**
 * OrderController
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/order
 */
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\BaseController;
use mjanssen\BreadcrumbsBundle\Breadcrumbs;
use URL;
use App\Order;
use Input;
use App\Restaurant;
use View;



class OrderController extends BaseController
{
    
    /**
     * Function for display list of all orders
     *
     * @param null
     *
     * @return view page. 
     */
    
    public function listOrder()
    {
        
        ### breadcrumbs Start ###
        Breadcrumbs::addBreadcrumb('Dashboard', URL::to('admin/dashboard'));
        Breadcrumbs::addBreadcrumb(trans("messages.order.order_text"), URL::to('admin/dashboard') );
        $breadcrumbs = Breadcrumbs::generate();
        ### breadcrumbs End ###
        
        $DB = Order::with('restaurantDetail');
        
        $searchVariable = array();
        $inputGet       = Input::get();
        $from           = Input::get('from');
        $to             = Input::get('to');
        
        if ((Input::get() && isset($inputGet['display'])) || isset($inputGet['page'])) {
            
            $searchData = Input::get();
            unset($searchData['display']);

            unset($searchData['_token']);
            
            if (isset($searchData['page'])) {
                unset($searchData['page']);
            }
            
            ## Validation on delivery date while searching on 'to' and 'from' field ##
            
            if ($from != '' || $to != '') {
                $validator = Validator::make(array(
                    'from' => $from,
                    'to' => $to
                ), array(
                    'from' => 'required',
                    'to' => 'required'
                ));
                
                if ($validator->fails()) {
                    return Redirect::to('admin/order-manager')->withErrors($validator)->withInput();
                }
            }
            
            if ($from != '' and $to != '') {
                
                unset($searchData['from']);
                unset($searchData['to']);
                
                $from1 = str_replace('-', '/', date('Y-m-d ', strtotime($from)));
                $to1   = str_replace('-', '/', date('Y-m-d ', strtotime($to)));
                
                $DB->whereBetween('deliver_date', array(
                    $from1,
                    $to1
                ));
                $searchVariable = array_merge($searchVariable, array(
                    'from' => $from,
                    'to' => $to
                ));
            }
            
            foreach ($searchData as $fieldName => $fieldValue) {
                
                if ($fieldName == 'order_status' && $fieldValue == 0) {
                    $DB->where("order_status", 'like', '%' . $fieldValue . '%');
                }
                if ($fieldName == 'shipping_type' && $fieldValue == 0) {
                    $DB->where("shipping_type", 'like', '%' . $fieldValue . '%');
                } else {
                    if (!empty($fieldValue)) {
                        $DB->where("$fieldName", 'like', '%' . $fieldValue . '%');
                    }
                }
                $searchVariable = array_merge($searchVariable, array(
                    $fieldName => $fieldValue
                ));
            }
        }
        
        $sortBy = (Input::get('sortBy')) ? Input::get('sortBy') : 'updated_at';
        $order  = (Input::get('order')) ? Input::get('order') : 'DESC';
        
//        $result = $DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
        $result = $DB->orderBy($sortBy, $order)->paginate(config("constants.Reading_records_per_page"));


        return View::make('admin.order.index', compact('breadcrumbs', 'result', 'searchVariable', 'sortBy', 'order'));
    } // end listOrder()
    
    /**
     * Function for view order
     *
     * @param $orderId
     *
     * @return view page. 
     */
    public function viewOrderDetail($orderId)
    {
        
        ### breadcrumbs Start ###
        Breadcrumb::addBreadcrumb(trans('messages.dashboard.dashboard_text'), URL::to('admin/dashboard'));
        Breadcrumb::addBreadcrumb(trans("messages.order.order_text"), URL::to('admin/order-manager'));
        Breadcrumb::addBreadcrumb(trans("messages.order.view_order"));
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        $orderDetail  = Order::with('orderItem')->where('id', $orderId)->first();
        $foodCategory = OrderItem::where('order_id', $orderId)->distinct('meal_category')->lists('meal_category');
        
        $restaurantId = Order::where('id', $orderId)->pluck('restaurant_id');
        
        $restaurantDetail = Restaurant::where('id', $restaurantId)->select('id', 'name', 'cuisine', 'description')->first();
		
		$foodCategories = 	AdminDropDown::where('dropdown_type', 'food-category')->lists('name', 'id');
		
        return View::make('admin.order.view', compact('breadcrumbs', 'orderDetail', 'foodCategory', 'restaurantDetail','foodCategories'));
        
    } //end viewOrderDetail()
    
    /**
     * Function for  update Restaurant Status
     *
     * @param $orderId as id of order
     * @param $status as status of order
     *
     * @return redirect page. 
     */
    public function updateOrderStatus($orderId = 0, $status = 0)
    {
        Order::where('id', '=', $orderId)->update(array(
            'order_status' => $status
        ));
        
        $userId				=	Order::where('id',$orderId)->pluck('user_id');
		$restaurantId		=	Order::where('id',$orderId)->pluck('restaurant_id');
        $userEmail			=	User::where('id',$userId)->pluck('email');
        $userName			=	User::where('id',$userId)->pluck('full_name');
        
		$orderDetail 		= 	Order::with('orderItem')->where('id', $orderId)->first();
		$foodCategory 		= 	OrderItem::where('order_id', $orderId)->distinct('meal_category')->lists('meal_category');
		$foodCategories 	= 	AdminDropDown::where('dropdown_type', 'food-category')->lists('name', 'id');
		
		
		if($status==1){
			$restaurantEmail		=	Restaurant::where('id',$restaurantId)->pluck('email');
			$restaurantName			=	Restaurant::where('id',$restaurantId)->pluck('name');
			$messageBody 			=	(String) View::make('emails.order_template', compact( 'orderDetail', 'foodCategory','foodCategories','restaurantName'));
			$subject				=	trans("messages.received_order");
			$this->sendMail($restaurantEmail,$restaurantName,$subject,$messageBody,Config::get("Site.email")); 
			$subject				=	'Order Accepeted';
		}elseif($status==1){
			$subject		=	'Order Rejected';
		}else{
			$subject		=	'Order Completed';
		}
		
		$messageBody 		=	(String) View::make('emails.order_confirm_template', compact('orderDetail', 'foodCategory','foodCategories','status','userName'));
		$this->sendMail($userEmail,$userName,$subject,$messageBody,Config::get("Site.email")); 
		
        Session::flash('flash_notice', trans("Order status updated successfully"));
        return Redirect::to('admin/order-manager');
    } // end updateOrderStatus()
    
     /**
     * Function for update payment status as paid
     *
     * @param $orderId as id of order
     *
     * @return redirect page. 
     */
     
    public function markAsPaid($orderId){
		Order::where('id', '=', $orderId)->update(array(
            'is_paid' => PAID
        ));
        Session::flash('flash_notice', trans("Order marked as paid successfully"));
        return Redirect::to('admin/order-manager');
	}//end markAsPaid()
    
} //end OrderController

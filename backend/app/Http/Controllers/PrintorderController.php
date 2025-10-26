<?php

/**
 * Printorder Controller
 *
 * Add your methods in the class below
 */
 
class PrintorderController extends \BaseController {

	/**
	 * Function for display order
	 *
	 * @param null
	 *
	 * @return order. 
	 */
	public function getIndex()
	{
		// $this->writeFile();
		$restaurantId			=	Input::get('a');
		$restaurantUsername		=	Input::get('u');
		$restaurantPassword		=	Input::get('p');
		
		if($restaurantId==''||$restaurantUsername==''|| $restaurantPassword==''){
			return 'Please input all require field.'; 
		}
		
		$restaurantDetail 		=	Restaurant::where('username',$restaurantUsername)->where('id',$restaurantId)->first();
		if(!empty($restaurantDetail)){
			if (!Hash::check($restaurantPassword,$restaurantDetail->password)) {
				return 'Authentication fail.'; 
			}
		}else{
			return 'username or restaurant id incorrect.'; 
		}
		$orders 		=	Order::with('orderItem')->where('restaurant_id',$restaurantId)->where('order_status',ACCEPTED)->where('is_read',0)->orderBy('created_at','asc')->get();
		
		$orderKey=	'';
	
		if(!empty($orders)){
			foreach($orders as $order){
				 $orderType					=	($order->shipping_type==DELIVERY) ? $order->shipping_type:PCOLLECTION ; //Order Type: The value is 1 or 2. Order Type=1, Delivery; Order Type=2,Collection.
				 $orderId					=	$order->id;
				 $orders 					=	Order::where('id',$orderId)->update(array('is_read'=>1));
				 $deliveryCharge			=	$order->delivery_charges;	
				 $ccHandlingFee				=	0.00;
				 $totalAmount				=	$order->total_amount+$ccHandlingFee;
				 
				 $customerType				=	VERIFIED_CUSTOMER;//Customer Type: The value is 4 or 5. Customer Type=4, Verified; Customer Type=5,Not Verified.
				 $Name 						=	$order->full_name;
				 $address 					=	($order->address!='') ? $order->address :'NA';
				 
				 if($order->deliver_date!=''){
					$time					=	str_replace('/','-',$order->deliver_date.$order->deliver_time);
					$requestFor				=	date(Config::get("Reading.date_format") , strtotime($time));
				 }else{
					$requestFor				=	'NA';
				 }
				 
				 $PreviousNumberOfOrders	=	0;
				 $paymentStatus				=	$order->is_paid;//Payment Status: The value is 6 or 7. Payment Status =6, Order paid; PaymentStus=7, Order not paid.
				 $phoneNumber				=	$order->payment_phone_number;
				 $paymentCardNumber			=	'NA';
				 $comment					=	($order->specific_instruction!='') ? $order->specific_instruction :'NA';
				 
				$foodList					=	''; 
				if(!empty($order->orderItem)){
					foreach($order->orderItem as $key => $item){
						$quantity 					=	$item->quantity;
						$foodName 					=	$item->meal_name;
						$foodAmount 				=	$item->price;
						$foodList	.="$quantity;$foodName;$foodAmount;";
					}
				}
				$orderKey .="#$restaurantId*$orderType*$orderId*$foodList*$deliveryCharge*$ccHandlingFee;$totalAmount;$customerType;$Name;$address;$requestFor;$PreviousNumberOfOrders;$paymentStatus;$paymentCardNumber;$phoneNumber;*$comment#";
			}
			header("HTTP/1.1 200 OK");
			header("Content-Type: text/plain");
			return $orderKey;
		}else{
			return 'No order found';
		}
	}// end getIndex()	// test a=16&u=pizzeriaoregano2&p=123456
	
	/**
	 * Function for accept or reject order by printer response
	 *
	 * @param null
	 *
	 * @return null. 
	 */
	public function getReply(){
		$restaurantId				=	Input::get('a');
		$orderId 					=	Input::get('o');
		$restaurantUsername			=	Input::get('u');
		$restaurantPassword			=	Input::get('p');
		$acceptOrRejectOrderTime	=	Input::get('dt');
		$descriptionAcceptOrReject	=	Input::get('m');
		$orderStatus 				=	Input::get('ak');// accept reject

		// check all require field to update status
		if($orderId==''||$restaurantUsername==''|| $restaurantPassword==''|| $restaurantId==''|| $acceptOrRejectOrderTime==''||$descriptionAcceptOrReject==''||$orderStatus==''){
			return 'Please input all require field.'; 
		}
		
		//validate order id
		$order	=	Order::where('id',$orderId)->first();
		if(empty($order)){
			return 'Order id not exist.'; 
		}
		
		// validate restaurant id and credential
		$restaurantDetail 		=	Restaurant::where('username',$restaurantUsername)->where('id',$restaurantId)->first();
		if(empty($restaurantDetail)){
			return 'username or restaurant id incorrect.';
		}else{
			if (!Hash::check($restaurantPassword,$restaurantDetail->password)) {
				return 'Authentication fail.'; 
			}
		}
		
		// update order status
		if($orderStatus=='Accepted'|| $orderStatus=='Rejected'){
			$updateStatus 				=	($orderStatus=='Accepted')? ACCEPTED: REJECTED;
			Order::where('id',$orderId)->update(array('order_status'=>$updateStatus));
		}else{
			return 'fail';
		}
	}// end getReply()
	///orders_reply?a=16&o=68&ak=Accepted&m=10&dt=08:10&u=pizzeriaoregano2&p=123456
	
	function writeFile(){
		// $contents	=	Input::all();
		$contents	=	$_SERVER['REQUEST_URI'];
		$filename	=	'reply_sample.txt';
		$oldContent	=	File::get($filename);
		$data	=	'';
		$data		.=	$oldContent.'----'.$contents;
		$newdata	=	'';
		// foreach($contents as $key => $value){
			// $newdata .= $key.'='.$value.';';
		// }
		
		$data	.=	$newdata;
		
		$bytes_written = File::put($filename, $data);
		if ($bytes_written === false)
		{
			die("Error writing to file");
		}
		
		// $filename	=	'sample.txt';
		// $size = filesize($filename);

		// header("Content-length: $size");
	}//end writeFile()
	
}// end PrintorderController class

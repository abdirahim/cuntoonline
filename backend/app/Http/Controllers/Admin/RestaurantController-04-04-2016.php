<?php

/**
 * RestaurantController
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/restaurant
 */

class RestaurantController extends BaseController
{
    
    /**
     * Function for display list of all Restaurant
     *
     * @param null
     *
     * @return view page. 
     */
    
    public function listRestaurant(){
        ### breadcrumbs Start ###
        Breadcrumb::addBreadcrumb('Dashboard', URL::to('admin/dashboard'));
        Breadcrumb::addBreadcrumb(trans("messages.restaurant.restaurant_text"));
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        $DB = Restaurant::query();
        
        $searchVariable = array();
        $inputGet       = Input::get();
        
        /* seacrching on the basis of name */
        
        if ((Input::get() && isset($inputGet['display'])) || isset($inputGet['page'])) {
            $searchData = Input::get();
            unset($searchData['display']);
            unset($searchData['_token']);
            
            if (isset($searchData['page'])) {
                unset($searchData['page']);
            }
            
            foreach ($searchData as $fieldName => $fieldValue) {
                if (!empty($fieldValue)) {
                    if ($fieldName == 'cuisine') {
                        foreach ($fieldValue as $term) {
                            $DB->orWhereRaw(DB::raw('FIND_IN_SET("' . $term . '",cuisine)'));
                        }
                    } else {
                        $DB->where("$fieldName", 'like', '%' . $fieldValue . '%');
                    }
                    $searchVariable = array_merge($searchVariable, array(
                        $fieldName => $fieldValue
                    ));
                }
            }
        }
        
        $sortBy = (Input::get('sortBy')) ? Input::get('sortBy') : 'updated_at';
        $order  = (Input::get('order')) ? Input::get('order') : 'DESC';
        
        $cuisineList = AdminDropDown::where('dropdown_type', 'cuisine')->lists('name', 'id');
        
        $result = $DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
        
        return View::make('admin.restaurant.index', compact('breadcrumbs', 'result', 'searchVariable', 'sortBy', 'order', 'cuisineList'));
    } // end listRestaurant()
    
    /**
     * Function for  add Restaurant
     *
     * @param null
     *
     * @return view page. 
     */
    public function addRestaurant()
    {
        
        ### breadcrumbs Start ###
        Breadcrumb::addBreadcrumb(trans('messages.dashboard.dashboard_text'), URL::to('admin/dashboard'));
        Breadcrumb::addBreadcrumb(trans("messages.restaurant.restaurant_text"), URL::to('admin/restaurant-manager'));
        Breadcrumb::addBreadcrumb(trans("messages.restaurant.add_restaurant"));
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        $cuisineList 	= AdminDropDown::where('dropdown_type', 'cuisine')->lists('name', 'id');
		$foodCategories = 	AdminDropDown::where('dropdown_type', 'food-category')->lists('name', 'id');
        return View::make('admin.restaurant.add', compact('breadcrumbs', 'cuisineList','foodCategories'));
        
    } //end addRestaurant()
    
    /**
     * Function for save restaurant
     *
     * @param null
     *
     * @return view page. 
     */
    public function saveRestaurant()
    {
       
        $formData = Input::all();
        
        if (!empty($formData)) {
            $validator = Validator::make(Input::all(), array(
				'username' => 'required|unique:restaurants',
				'password' => 'required|min:6',
				'confirm_password' => 'required|min:6|same:password',
				'email' => 'required|email|unique:restaurants',
                'cuisine' => 'required',
				'food_menu' => 'required',
                'name' => 'required',
                'description' => 'required',
                'long' => 'required',
                'lat' => 'required',
                'image' => 'required|mimes:' . IMAGE_EXTENSION
            ), array(
                'long.required' => 'Sorry we are unable to fetch your location.'
            ));
            
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            } else {
                
                $obj = new Restaurant;
                if (Input::hasFile('image')) {
                    $extension = Input::file('image')->getClientOriginalExtension();
                    $fileName  = time() . '-image.' . $extension;
                    if (Input::file('image')->move(RESTAURANT_IMAGE_ROOT_PATH, $fileName)) {
                        $obj->image = $fileName;
                    }
                }
                
                $obj->slug         = $this->getSlug(Input::get('name'), 'Restaurant');
				$obj->username     = Input::get('username');
				$obj->password     = Hash::make(Input::get('password'));
                $obj->name         = Input::get('name');
				$obj->email        = Input::get('email');
                $obj->lat          = Input::get('lat');
                $obj->lang         = Input::get('long');
                $obj->cuisine      = implode(',', Input::get('cuisine'));
				$obj->food_menu      = implode(',', Input::get('food_menu'));
                $obj->description  = Input::get('description');
                $obj->delivery     = Input::get('delivery') ? Input::get('delivery') : 0;
                $obj->collection   = Input::get('collection') ? Input::get('collection') : 0;
                
                $obj->is_active = 1;
                $obj->save();
                
                Session::flash('success', trans("messages.restaurant.add_msg"));
                return Redirect::to('admin/restaurant-manager');
            }
        }
    } // saveRestaurant()
    
    /**
     * Function for display Restaurant detail
     *
     * @param $restroId     as id of Restaurant
     *
     * @return view page. 
     */
    public function viewRestaurant($restroId = 0)
    {
        
        Breadcrumb::addBreadcrumb(trans('messages.dashboard.dashboard_text'), URL::to('admin/dashboard'));
        Breadcrumb::addBreadcrumb(trans("messages.restaurant.restaurant_text"), URL::to('admin/restaurant-manager'));
        Breadcrumb::addBreadcrumb(trans("messages.restaurant.view_restaurant"));
        $breadcrumbs = Breadcrumb::generate();
        
        if ($restroId) {
            $restroDetails = Restaurant::find($restroId);
            
            $cuisineList = DB::table('dropdown_managers')->where('dropdown_type', 'cuisine')->whereIn('id', (explode(',', $restroDetails->cuisine)))->lists('name');
            $cuisineName = implode(', ', $cuisineList);
			
			$openTimeDetail 	=	DB::table('restaurant_time')->where('restaurant_id',$restroId)->get();
			$editDetailArray	=	array();
			
			if(!empty($openTimeDetail )){
				foreach($openTimeDetail as  $value2){
					$editDetailArray["$value2->week_day"]	= $value2;
				}
			}
            return View::make('admin.restaurant.view', compact('restroDetails', 'breadcrumbs', 'cuisineName','editDetailArray'));
        }
        
    } // end viewRestaurant()
    
    /**
     * Function for display page for edit Restaurant
     *
     * @param $restroId as id of restro
     *
     * @return view page. 
     */
    
    public function editRestaurant($restroId = 0)
    {
        
        ### breadcrumbs Start ###
        Breadcrumb::addBreadcrumb(trans('messages.dashboard.dashboard_text'), URL::to('admin/dashboard'));
        Breadcrumb::addBreadcrumb(trans("messages.restaurant.restaurant_text"), URL::to('admin/restaurant-manager'));
        Breadcrumb::addBreadcrumb(trans("messages.restaurant.edit_restaurant"));
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        if ($restroId) {
            $restroDetails = Restaurant::find($restroId);
            $cuisineList   = AdminDropDown::where('dropdown_type', 'cuisine')->lists('name', 'id');
			$foodCategories = 	AdminDropDown::where('dropdown_type', 'food-category')->lists('name', 'id');
            return View::make('admin.restaurant.edit', compact('restroDetails', 'breadcrumbs', 'cuisineList','foodCategories'));
        }
    } // end editRestaurant()
    
    /**
     * Function for update restaurant detail
     *
     * @param $restroId as id of restaurant
     *
     * @return redirect page. 
     */
    public function updateRestaurant($restroId = 0)
    {
     
        $thisData = Input::all();
        $validationRules = array(
			'password' 		=> 'min:6',
			'username' 		=> 'required|unique:restaurants,username'.($restroId ? ",$restroId" : ''),
			'email' 		=> 'required',
            'cuisine' 		=> 'required',
			'food_menu' 	=> 'required',
            'name' 			=> 'required',
            'description' 	=> 'required',
            'image' 		=> 'mimes:' . IMAGE_EXTENSION,
            'long' 			=> 'required',
            'lat' 			=> 'required'
        );
        
        $validator = Validator::make($thisData, $validationRules, array(
            'long.required' => trans("messages.restaurant.location.required")
        ));
        
        if ($validator->fails()) {
            return Redirect::to('/admin/restaurant-manager/edit-restaurant/' . $restroId)->withErrors($validator)->withInput();
        } else {
				
            ## Update restaurant's information in restaurants table ##
            
            $obj = Restaurant::find($restroId);
            
            if (Input::file('image')) {
                $extension = Input::file('image')->getClientOriginalExtension();
                $fileName  = time() . '-image.' . $extension;
                Input::file('image')->move(RESTAURANT_IMAGE_ROOT_PATH, $fileName);
                @unlink(RESTAURANT_IMAGE_ROOT_PATH . $obj->image);
            } else {
                $fileName = $obj->image;
            }
            $obj->lat         	 	= Input::get('lat');
            $obj->lang         		= Input::get('long');
            $obj->name        	 	= Input::get('name');
			$obj->email        		= Input::get('email');
			$obj->username          = Input::get('username');
            $obj->image        = $fileName;
            $obj->cuisine      = implode(',', Input::get('cuisine'));
			$obj->food_menu    = implode(',', Input::get('food_menu'));
            $obj->description  = Input::get('description');
            $obj->delivery     = Input::get('delivery') ? Input::get('delivery') : 0;
            $obj->collection   = Input::get('collection') ? Input::get('collection') : 0;
			
			if(Input::has('password') && Input::has('password')!=''){
				$obj->password     = Hash::make(Input::get('password'));
			}
            $obj->save();
            
            return Redirect::to('/admin/restaurant-manager')->with('success', trans("messages.restaurant.updated_msg"));
        }
    } // end updateRestaurant()
    
    /**
     * Function for delete Restaurant
     *
     * @param $restroId as id of restro
     *
     * @return redirect page. 
     */
    public function deleteRestaurant($restroId = 0)
    {
        
        if ($restroId) {
            $restaurantModel = Restaurant::find($restroId);
            @unlink(RESTAURANT_IMAGE_ROOT_PATH.$restaurantModel->image);
            $restaurantModel = $restaurantModel->delete();
            Session::flash('flash_notice', trans("messages.restaurant.deleted_msg"));
        }
        return Redirect::to('admin/restaurant-manager');
    } // end deleteRestaurant()
    
    /**
     * Function for  update Restaurant Status
     *
     * @param $restroId as id of restaurant
     * @param $status as status of restaurant
     *
     * @return redirect page. 
     */
    public function updateRestaurantStatus($restroId = 0, $status = 0)
    {
        Restaurant::where('id', '=', $restroId)->update(array(
            'is_active' => $status
        ));
        Session::flash('flash_notice', trans("messages.restaurant.status_msg"));
        return Redirect::to('admin/restaurant-manager');
    } // end updateRestaurantStatus()
    
    /**
     * Function for  add meal
     *
     * @param $restroId as id of restro
    
     * @return redirect page. 
     */
    
    public function addMeal($restroId)
    {
        
        ### breadcrumbs Start ###
        Breadcrumb::addBreadcrumb('Dashboard', URL::to('admin/dashboard'));
        Breadcrumb::addBreadcrumb('Restaurants', URL::to('admin/restaurant-manager'));
        Breadcrumb::addBreadcrumb(trans("messages.restaurant.add_meal"));
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        $formData = Input::all();
        
        if (Request::isMethod('post') && isset($formData['add-meal'])) {
            
            if (!empty($formData)) {
                $validator = Validator::make(Input::all(), array(
                    'category' => 'required',
                    'name' => 'required',
                    'description' => 'required',
                    'price' => 'required|numeric'
                ));
                
                if ($validator->fails()) {
                    return Redirect::back()->withErrors($validator)->withInput();
                } else {
                    
                    $obj = new RestaurantMeal;
                    
                    $obj->slug          = $this->getSlug(Input::get('name'), 'Restaurant');
                    $obj->name          = Input::get('name');
                    $obj->food_category = Input::get('category');
                    $obj->restaurant_id = $restroId;
                    $obj->description   = Input::get('description');
                    $obj->price         = Input::get('price');
                    $obj->is_active     = 1;
                    
                    $obj->save();
                    
                    Session::flash('success', trans("messages.restaurant.meal_added_msg"));
                    return Redirect::back();
                }
            }
        }
        $foodMenuString		=	Restaurant::where('id',$restroId)->pluck('food_menu');
		$foodMenu 			=	explode(',',$foodMenuString);
        $DB = RestaurantMeal::where('restaurant_id', $restroId)->whereIn('food_category',$foodMenu);


        $searchVariable = array();
        $inputGet       = Input::get();
        
        /* seacrching on the basis of name */
        
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
        
        $result 			= 	$DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		$foodMenuString		=	Restaurant::where('id',$restroId)->pluck('food_menu');
		$foodMenu 			=	explode(',',$foodMenuString); 
		$foodCategoryList 	= 	AdminDropDown::where('dropdown_type', 'food-category')->whereIn('id',$foodMenu)->lists('name', 'id');
        return View::make('admin.restaurant.add_meal', compact('restroDetails', 'result', 'searchVariable', 'sortBy', 'order', 'restroId', 'breadcrumbs','foodCategoryList'));
        
    } //end addMeal()
    
    
    /**
     * Function for edit meal
     *
     * @param $restroId as id of restro
     * @param $mealId as status of meal
     *
     * @return redirect page. 
     */
    
    public function editMeal($restroId, $mealId)
    {
        
        ### breadcrumbs Start ###
        Breadcrumb::addBreadcrumb('Dashboard', URL::to('admin/dashboard'));
        Breadcrumb::addBreadcrumb('Restaurants', URL::to('admin/restaurant-manager'));
        Breadcrumb::addBreadcrumb(trans("messages.restaurant.edit_meal"));
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
       
        $formData = Input::all();
        
        if (Request::isMethod('post')) {
            
            if (!empty($formData)) {
                
                $validator = Validator::make(Input::all(), array(
                    'category' => 'required',
                    'name' => 'required',
                    'description' => 'required',
                    'price' => 'required|numeric'
                ));
                
                if ($validator->fails()) {
                    return Redirect::back()->withErrors($validator)->withInput();
                } else {
                    
                    $obj = RestaurantMeal::find($mealId);
                    
                    $obj->food_category = Input::get('category');
                    $obj->name          = Input::get('name');
                    $obj->description   = Input::get('description');
                    $obj->price         = Input::get('price');
                    
                    $obj->save();
                    
                    Session::flash('success', trans("messages.restaurant.meal_updated_msg"));
                    return Redirect::to('admin/restaurant-manager/add-meal/' . $restroId);
                }
            }
        }
        
        $mealDetail = RestaurantMeal::where('restaurant_id', $restroId)->where('id', $mealId)->first();
        $foodCategoryList = AdminDropDown::where('dropdown_type', 'food-category')->lists('name', 'id');
		
		$foodMenuString		=	Restaurant::where('id',$restroId)->pluck('food_menu');
		$foodMenu 			=	explode(',',$foodMenuString); 
		$foodCategoryList 	= 	AdminDropDown::where('dropdown_type', 'food-category')->whereIn('id',$foodMenu)->lists('name', 'id');
        return View::make('admin.restaurant.edit_meal', compact('mealDetail', 'restroId', 'breadcrumbs','foodCategoryList'));
        
    } //end editMeal()
    
    /**
     * Function for delete Restaurant
     *
     * @param $mealId as id of meal
     *
     * @return redirect page. 
     */
    public function deleteMeal($mealId = 0)
    {
        
        if ($mealId) {
            $restaurantMealModel = RestaurantMeal::find($mealId);
            $restaurantMealModel->delete();
            Session::flash('flash_notice', trans("messages.restaurant.meal_deleted_msg"));
        }
        return Redirect::back();
    } // end deleteMeal()
    
    
    /**
     * Function for  update Meal Status
     *
     * @param $mealId as id of meal
     * @param $status as status of meal
     *
     * @return redirect page. 
     */
    public function updateMealStatus($mealId = 0, $status = 0)
    {
        RestaurantMeal::where('id', '=', $mealId)->update(array(
            'is_active' => $status
        ));
        Session::flash('flash_notice', trans("messages.restaurant.meal_status_msg"));
        return Redirect::back();
    } // end updateMealStatus()
    
    
    /**
     * Function for  update Recommended Status
     *
     * @param $restroId as id of restro
     * @param $status as status of restro
     *
     * @return redirect page. 
     */
    public function recommendedStatus($restroId = 0, $status = 0)
    {
        Restaurant::where('id', '=', $restroId)->update(array(
            'is_recommended' => $status
        ));
        Session::flash('flash_notice', trans("messages.restaurant.recommended_msg"));
        return Redirect::back();
    } // end recommendedStatus()
    
	/**
	 * Function for  import the csv file
	 *
	 * @param $restroId as id of restro
	 *
	 * @return redirect page. 
	 */
	 public function importMeal($restroId =''){
		$validator = Validator::make(
				Input::all(),
				array(
					'file'			=> 'required|mimes:csv'
				)
			);
			
			if ($validator->fails()){
				 return Redirect::back()->withErrors($validator)->withInput();
			}else{
				$file =	Input::file('file');
				Excel::load($file, function($reader) use($restroId) {
					$results = $reader->get();
					if(!empty($results)){
						if(!isset($results['0']->name) || !isset($results['0']->description) || !isset($results['0']->price) || !isset($results['0']->category)){
							Session::flash('error', trans("file must contain name,description and price colum.Please download sample file and check.")); 
						}else{
							foreach($results as $result){
								$slug	=	$this->getSlug($result->name,'Restaurant');
								if($result->name!='' && $result->price!='' && $result->category!='' && $result->description!=''  ){
									RestaurantMeal::insert(array(
										'restaurant_id'=>$restroId,
										'slug'=>$slug,
										'food_category'=>$result->category,
										'name'=>$result->name,
										'description'=>$result->description,
										'price'=>$result->price,
										'is_active'=>1,
										'created_at'=>DB::raw('NOW()'),
										'updated_at'=>DB::raw('NOW()')
									));
								}
							}
							Session::flash('flash_notice', trans("Meal imported successfully")); 
						}
					}
				});	
			
		}
		
		return Redirect::back();
	}//end importMeal()	
	
	/**
	 * Function for display list  reviews  on offer
	 *
	 * @param $offerId as id of offer
	 *
	 * @return view page. 
	 */
	public  function listReview($restaurantId=''){
		
		if($restaurantId==''){
			Redirect::to('admin/restaurant-manager');
		}
		
		Breadcrumb::addBreadcrumb('Dashboard',URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb('Restaurant ',URL::to('admin/restaurant-manager'));
		Breadcrumb::addBreadcrumb('list Review');
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		$DB = DB::table('restaurant_reviews');
		
		//  if click  on search
		$searchVariable	=	array(); 
		$inputGet		=	Input::get();
		if (Input::get() && isset($inputGet['display'])) {
			$search = true;
			$searchData	=	Input::get();
			unset($searchData['display']);
			unset($searchData['_token']);
			foreach($searchData as $fieldName => $fieldValue){
				if(!empty($fieldValue)){
					$DB->where("$fieldName",'like','%'.$fieldValue.'%');
					$searchVariable	=	array_merge($searchVariable,array($fieldName => $fieldValue));
				}
			}
		}
		
		$sortBy 		= 		(Input::get('sortBy')) ? Input::get('sortBy') : 'created_at';
	    $order  		= 		(Input::get('order')) ? Input::get('order')   : 'DESC';
		
		$result 		= 	$DB->where('restaurant_id',$restaurantId)->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		
		// offers table  join   with merchant for merchant  name get 
		$restaurantDetail 	= 	DB::table('restaurants')->where('id',$restaurantId)->first();
		
		// this  is use  for show  avg rating
		$avgRating 		= 	ceil(number_format(DB::table('restaurant_reviews')->where('restaurant_id',$restaurantId)->where('rating','<>','0')->avg('rating'),1));
		
		return  View::make('admin.restaurant.indexreview',compact('breadcrumbs','result','searchVariable','restaurantDetail','avgRating')); 
	}// end listReview()

	 /**
	 * Function for  to show or  hide particular  review from  front 
	 *
	 * @param $reviewId as id of review
	 * @param $offerReviewStatus as status of review of offer
	 *
	 * @return redirect page.
	 */

	public function reviewDislayStatus($reviewId = 0, $offerReviewStatus = 0){
		DB::table('restaurant_reviews')->where('id', '=', $reviewId)->update(array('is_display' => $offerReviewStatus));
		Session::flash('flash_notice', ' Offer Review Status updated successfully.'); 
		return Redirect::back(); 
	}// end reviewDislayStatus()

	 /**
	 * Function for delete review
	 *
	 * @param $reviewId as id of review
	 *
	 * @return redirect page. 
	 */

	public function deleteReview($reviewId = 0){
		if($reviewId){
			DB::table('restaurant_reviews')->delete($reviewId);
			Session::flash('flash_notice', 'Review deleted successfully.'); 
		}
		return Redirect::back(); 
	}// end deleteReview()
	
	/**
	 * Function for manage time
	 *
	 * @param $restaurantId as id of restaurant
	 *
	 * @return redirect page. 
	 */
	public function manageTime($restaurantId){
		Breadcrumb::addBreadcrumb('Dashboard',URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb('Restaurant ',URL::to('admin/restaurant-manager'));
		Breadcrumb::addBreadcrumb('Delivery Time');
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		 $thisData	=	 Input::all();
		 $openTimeDetail 	=	DB::table('restaurant_time')->where('restaurant_id',$restaurantId)->get();
		 $editDetailArray	=	array();
		
		if(!empty($openTimeDetail )){
			foreach($openTimeDetail as  $value2){
				$editDetailArray["$value2->week_day"]	= $value2;
			}
		 }
		 
		 if (Request::isMethod('post')){
			if(!empty($thisData['day'])){
				foreach ($thisData['day'] as $key=> $value){
					if($thisData['open_time'][$key]==''|| $thisData['close_time'][$key]==''){
					 Session::flash('error', 'Please enter all open time and close time for all open day.'); 
					 return Redirect::back()->withInput();
					}
					if(strtotime($thisData['open_time'][$key]) > strtotime($thisData['close_time'][$key])){
					 Session::flash('error', 'Opening time should be less than to closing time.'); 
					 return Redirect::back()->withInput();
					}
				}
				if(empty($openTimeDetail)){
					foreach ($thisData['day'] as $key=> $value){
						DB::table('restaurant_time')->insert(array(
							'restaurant_id'		=>	$restaurantId,
							'week_day'			=>	$value,
							'open_time'			=>	$thisData['open_time'][$key],
							'close_time'		=>	$thisData['close_time'][$key],
							'created_at'		=>	DB::raw('NOW()'),
							'updated_at'		=>	DB::raw('NOW()')
						));
					}
					Session::flash('success', 'Restaurant time has been added successfully.'); 
					return Redirect::back();
				}else{
					$selectDay	=	 array_values($thisData['day']);
					DB::table('restaurant_time')->whereNotIn('week_day',$selectDay)->delete();	
					foreach ($thisData['day'] as $key=> $value){
						RestaurantTime::updateOrCreate(array('restaurant_id'=>$restaurantId,'week_day'=>$value),array(
							'restaurant_id'		=>	$restaurantId,
							'week_day'			=>	$value,
							'open_time'			=>	$thisData['open_time'][$key],
							'close_time'		=>	$thisData['close_time'][$key],
						));
					}
					Session::flash('success', 'Restaurant time has been updated successfully.'); 
					return Redirect::back();
				}
			}else{
				 Session::flash('error', 'Please select at least one day for open'); 
			     return Redirect::back()->withInput();
			}
		 }
		 return View::make('admin.restaurant.manage_time',compact('restaurantId','openTimeDetail','editDetailArray','breadcrumbs'));
	}// end manageTime()
} //end RestaurantController

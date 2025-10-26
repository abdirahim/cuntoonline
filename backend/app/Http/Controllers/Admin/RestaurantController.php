<?php

/**
 * RestaurantController
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/restaurant
 */

use App\Http\Controllers\BaseController;
use App\Restaurant;
use Input;
use App\AdminDropDown;
use View;
use Validator;
use Redirect;
use Hash;
use Session;
use Request;
use App\RestaurantMeal;
use mjanssen\BreadcrumbsBundle\Breadcrumbs;
use URL;
use DB;
use App\RestaurantTime;

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
        Breadcrumbs::addBreadcrumb('Dashboard', URL::to('admin/dashboard'));
        //$transArray=['admin/dashboard'];
        Breadcrumbs::addBreadcrumb(trans("messages.restaurant.restaurant_text"), URL::to('admin/dashboard'));
        $breadcrumbs = Breadcrumbs::generate();
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
        
//        $cuisineList = AdminDropDown::where('dropdown_type', 'cuisine')->lists('name', 'id');
        $cuisineList = AdminDropDown::where('dropdown_type', 'cuisine')->pluck('name', 'id');
        
//        $result = $DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
        $result = $DB->orderBy($sortBy, $order)->paginate(config("constants.Reading_records_per_page"));

        
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
        Breadcrumbs::addBreadcrumb(trans('messages.dashboard.dashboard_text'), URL::to('admin/dashboard'));
        Breadcrumbs::addBreadcrumb(trans("messages.restaurant.restaurant_text"), URL::to('admin/restaurant-manager'));
        Breadcrumbs::addBreadcrumb(trans("messages.restaurant.add_restaurant"),  URL::to('restaurant-manager/add-restaurant'));
        $breadcrumbs = Breadcrumbs::generate();
        ### breadcrumbs End ###
        
//        $cuisineList 	= AdminDropDown::where('dropdown_type', 'cuisine')->lists('name', 'id');
        $cuisineList 	= AdminDropDown::where('dropdown_type', 'cuisine')->pluck('name', 'id');

//        $foodCategories = 	AdminDropDown::where('dropdown_type', 'food-category')->lists('name', 'id');
        $foodCategories = 	AdminDropDown::where('dropdown_type', 'food-category')->pluck('name', 'id');

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

       //    dd($formData);
        
        if (!empty($formData)) {
           // dd('aaaa');
            $validator = Validator::make(Input::all(), array(
				'username' => 'required|unique:restaurants',
				'password' => 'required|min:6',
				'confirm_password' => 'required|min:6|same:password',
				'email' => 'required|email|unique:restaurants',
                'cuisine' => 'required',
				'food_menu' => 'required',
                'name' => 'required',
                'description' => 'required',
              //  'long' => 'required',
              //  'lat' => 'required',
              //  'image' => 'required|mimes:' . config("constants.IMAGE_EXTENSION")
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
//                    if (Input::file('image')->move(RESTAURANT_IMAGE_ROOT_PATH, $fileName)) {
//                    $path = $request->file('avatar')->storeAs(
//                        'avatars', $request->user()->id
//                    );
                    try {
                        if (Input::file('image')->move(public_path().'/uploads/restaurant_img/', $fileName)) {

                            $obj->image = $fileName;
                        }

                    } catch (Throwable $e) {
                        report($e);

                        return false;
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
        
//        Breadcrumb::addBreadcrumb(trans('messages.dashboard.dashboard_text'), URL::to('admin/dashboard'));
//        Breadcrumb::addBreadcrumb(trans("messages.restaurant.restaurant_text"), URL::to('admin/restaurant-manager'));
//        Breadcrumb::addBreadcrumb(trans("messages.restaurant.view_restaurant"));
//        $breadcrumbs = Breadcrumb::generate();
        
        if ($restroId) {
            $restroDetails = Restaurant::find($restroId);
            
//            $cuisineList = DB::table('dropdown_managers')->where('dropdown_type', 'cuisine')->whereIn('id', (explode(',', $restroDetails->cuisine)))->lists('name');
            $cuisineList = DB::table('dropdown_managers')->where('dropdown_type', 'cuisine')->whereIn('id', (explode(',', $restroDetails->cuisine)))->pluck('name');

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
        Breadcrumbs::addBreadcrumb(trans('messages.dashboard.dashboard_text'), URL::to('admin/dashboard'));
        Breadcrumbs::addBreadcrumb(trans("messages.restaurant.restaurant_text"), URL::to('admin/restaurant-manager'));
        Breadcrumbs::addBreadcrumb(trans("messages.restaurant.edit_restaurant"), URL::to('admin/restaurant-manager'));
        $breadcrumbs = Breadcrumbs::generate();
        ### breadcrumbs End ###
        
        if ($restroId) {
            $restroDetails = Restaurant::find($restroId);
//            $cuisineList   = AdminDropDown::where('dropdown_type', 'cuisine')->lists('name', 'id');
            $cuisineList   = AdminDropDown::where('dropdown_type', 'cuisine')->pluck('name', 'id');

//            $foodCategories = 	AdminDropDown::where('dropdown_type', 'food-category')->lists('name', 'id');
            $foodCategories = 	AdminDropDown::where('dropdown_type', 'food-category')->pluck('name', 'id');

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
         //   'image' 		=> 'mimes:' . config("constants.IMAGE_EXTENSION"),
//            'long' 			=> 'required',
//            'lat' 			=> 'required'
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
                Input::file('image')->move(config("constants.RESTAURANT_IMAGE_ROOT_PATH"), $fileName);
                @unlink(config("constants.RESTAURANT_IMAGE_ROOT_PATH") . $obj->image);
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
        Breadcrumbs::addBreadcrumb('Dashboard', URL::to('admin/dashboard'));
        Breadcrumbs::addBreadcrumb('Restaurants', URL::to('admin/restaurant-manager'));
        Breadcrumbs::addBreadcrumb(trans("messages.restaurant.add_meal"), URL::to('admin/restaurant-manager'));
//        $breadcrumbs = Breadcrumbs::generate();
        Breadcrumbs::generate();
        ### breadcrumbs End ###
        
        $formData = Input::all();


        
//        if (Request::isMethod('post') && isset($formData['add-meal'])) {
        if (Request::isMethod('post')) {


            if (!empty($formData)) {
                $validator = Validator::make(Input::all(), array(
                    'category' => 'required',
                    'name' => 'required',
                    'description' => 'required',
                    'price' => 'required|numeric'
                ));
                
                if ($validator->fails()) {
                   // dd('bbb');
                    return Redirect::back()->withErrors($validator)->withInput();
                } else {
                   // dd('ccccc');
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
        
        $result 			= 	$DB->orderBy($sortBy, $order)->paginate(config("constants.Reading_records_per_page"));
		$foodMenuString		=	Restaurant::where('id',$restroId)->pluck('food_menu');
		$foodMenu			=	explode(',',$foodMenuString);
//        $foodCategoryList 	= 	AdminDropDown::where('dropdown_type', 'food-category')->whereIn('id',$foodMenu)->pluck('name', 'id');
        $string = str_replace(array('["','"]'),'',$foodMenu);

         $foodCategoryList 	= 	AdminDropDown::whereIn('id', $string)
            ->where('dropdown_type', 'food-category')
            ->pluck('name', 'id');

//        return View::make('admin.restaurant.add_meal', compact('restroDetails', 'result', 'searchVariable', 'sortBy', 'order', 'restroId', 'breadcrumbs','foodCategoryList'));
        return View::make('admin.restaurant.add_meal', compact( 'result', 'searchVariable', 'sortBy', 'order', 'restroId','foodCategoryList'));

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
        Breadcrumbs::addBreadcrumb('Dashboard', URL::to('admin/dashboard'));
        Breadcrumbs::addBreadcrumb('Restaurants', URL::to('admin/restaurant-manager'));
        Breadcrumbs::addBreadcrumb(trans("messages.restaurant.edit_meal"), URL::to('admin/restaurant-manager'));
        $breadcrumbs = Breadcrumbs::generate();
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
//        $foodCategoryList = AdminDropDown::where('dropdown_type', 'food-category')->lists('name', 'id');
       // $foodCategoryList = AdminDropDown::where('dropdown_type', 'food-category')->pluck('name', 'id');
		
		$foodMenuString		=	Restaurant::where('id',$restroId)->pluck('food_menu');
		$foodMenu 			=	explode(',',$foodMenuString); 
		$foodCategoryList 	= 	AdminDropDown::where('dropdown_type', 'food-category')->whereIn('id',$foodMenu)->lists('name', 'id');
        $foodCategoryList 	= 	AdminDropDown::where('dropdown_type', 'food-category')->whereIn('id',$foodMenu)->pluck('name', 'id');

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
		
//		Breadcrumb::addBreadcrumb('Dashboard',URL::to('admin/dashboard'));
//		Breadcrumb::addBreadcrumb('Restaurant ',URL::to('admin/restaurant-manager'));
//		Breadcrumb::addBreadcrumb('list Review');
//		$breadcrumbs 	= 	Breadcrumb::generate();
		
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
		Breadcrumbs::addBreadcrumb('Dashboard',URL::to('admin/dashboard'));
		Breadcrumbs::addBreadcrumb('Restaurant ',URL::to('admin/restaurant-manager'));
		Breadcrumbs::addBreadcrumb('Delivery Time', URL::to('admin/restaurant-manager'));
		$breadcrumbs 	= 	Breadcrumbs::generate();
		
		 $thisData			=	 Input::all();
		 $openTimeDetail 	=	 DB::table('restaurant_time')->where('restaurant_id',$restaurantId)->get();
		 $editDetailArray	=	 array();
		 $openTime 			=	 DB::table('restaurant_time')->where('restaurant_id',$restaurantId)->pluck('open_time');
		 $closeTime 		=	 DB::table('restaurant_time')->where('restaurant_id',$restaurantId)->pluck('close_time');

		     dd($thisData);
		
		if(!empty($openTimeDetail )){
			foreach($openTimeDetail as  $value2){
				$editDetailArray["$value2->week_day"]	= $value2;
			}
		 }
		 
		 if (Request::isMethod('post')){
                $validator = Validator::make(Input::all(), array(
                    'open_time' => 'required',
                    'close_time' => 'required',
                ));
                
			if ($validator->fails()) {
				return Redirect::back()->withErrors($validator)->withInput();
			}
			if(!empty($thisData['day'])){
				if(empty($openTimeDetail)){
				  // dd('insert');
					foreach ($thisData['day'] as $key=> $value){
						DB::table('restaurant_time')->insert(array(
							'restaurant_id'		=>	$restaurantId,
							'week_day'			=>	$value,
							'open_time'			=>	$thisData['open_time'],
							'close_time'		=>	$thisData['close_time'],
							'created_at'		=>	DB::raw('NOW()'),
							'updated_at'		=>	DB::raw('NOW()')
						));
					}
					Session::flash('success', 'Restaurant time has been added successfully.'); 
					return Redirect::back();
				}else{
				     // dd('update');
//                  dd( $thisData['day']);
				   // dd($thisData['open_time'].' '.$thisData['close_time']);
					$selectDay	=	 array_values($thisData['day']);
					DB::table('restaurant_time')->whereNotIn('week_day',$selectDay)->delete();	
					foreach ($thisData['day'] as $key=> $value){

//						RestaurantTime::updateOrCreate(array('restaurant_id'=>$restaurantId,'week_day'=>$value),array(
//							'restaurant_id'		=>	$restaurantId,
//							'week_day'			=>	$value,
//							'open_time'			=>	$thisData['open_time'],
//							'close_time'		=>	$thisData['close_time'],
//						));
                        RestaurantTime::updateOrCreate(
                            [
                                'restaurant_id' => $restaurantId,
                                'week_day'=>$value
                            ],
                            [
                                'restaurant_id'		=>	$restaurantId,
                                'week_day' => $value,
                                'open_time'			=>	$thisData['open_time'],
							    'close_time'		=>	$thisData['close_time'],
                            ]
                        );
					}
					Session::flash('success', 'Restaurant time has been updated successfully.'); 
					return Redirect::back();
				}
			}else{
				 Session::flash('error', 'Please select at least one day for open'); 
			     return Redirect::back()->withInput();
			}
		 }
		 return View::make('admin.restaurant.manage_time',compact('restaurantId','openTimeDetail','editDetailArray','breadcrumbs','openTime','closeTime'));
	}// end manageTime()
} //end RestaurantController

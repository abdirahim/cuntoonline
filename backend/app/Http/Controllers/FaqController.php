<?php

/**
 * Faq Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/faq
 */

class FaqController extends \BaseController
{
    
    /** 
     * Function to display faq  page
     *
     * @param null
     * 
     * @return view page
     */
    
    public function index()
    {
        $faq = Faq::getResult();
		View::share('pageTitle', 'Faq');
        return View::make('faq.index', compact('faq'));
    } //end index()
    
} // end FaqController class

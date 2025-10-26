<?php
/**
 * AdminUser Model
 */
//use Illuminate\Database\Eloquent\SoftDeletingTrait;
namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
//class AdminUser extends Eloquent {
class AdminUser extends Model {
	
/**
 * The database table used by the model.
 *
 * @var string
	 */
	//use SoftDeletingTrait;
    use SoftDeletes;
	protected $table = 'users';
	protected $dates = ['deleted_at'];
	
/**
 * Function for  bind UserProfile model   
 *
 * @param null 
 *
 * return query
 */	
	public function userProfile(){
		return $this->hasMany('UserProfile','user_id');
	}//end userProfile()
	
/**
 * Function for  bind Userlogin model   
 *
 * @param null 
 *
 * return query
 */			
	public function userLastLogin(){
        return $this->hasOne('Userlogin','user_id')->orderBy('created_at','desc');
    }//end userLastLogin()
	
}// end AdminUser class

<?php 
use Illuminate\Database\Eloquent\Model;

/**
 * AdminBlock Model
 */
 
class AdminBlock extends Eloquent  {
	
/**
 * The database table used by the model.
 *
 * @var string
 */
 
	protected $table = 'blocks';
	
/**
 * hasMany bind function for  AdminBlockDescription model 
 *
 * @param null
 * 
 * @return query
 */	
	public function description() {
        return $this->hasMany('AdminBlockDescription','parent_id');
    }// end description()
	
}// end AdminBlock class

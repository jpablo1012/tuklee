<?php

use Jenssegers\Mongodb\Model as Eloquent;

class User extends Eloquent {
    
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $collection = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'updated_at', 'created_at');

}

<?php

use Jenssegers\Mongodb\Model as Eloquent;

class ShareCard extends Eloquent {
    
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $collection = 'shareCards';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('updated_at', 'created_at');

}

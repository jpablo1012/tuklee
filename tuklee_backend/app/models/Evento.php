<?php

use Jenssegers\Mongodb\Model as Eloquent;

class Evento extends Eloquent {
    
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $collection = 'eventos';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('updated_at', 'created_at');

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{	
	protected $table = 'settings';

    protected $casts = [
	    'ice_servers' => 'array',
	];

}

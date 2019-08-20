<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSip extends Model
{	
	protected $table = 'user_sip';
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];

    /**
     * Get the user that has the sip details.
     */
    public function user(){
    	$this->belongsTo('App\User');
    }
}

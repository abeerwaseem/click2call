<?php

namespace App\Http\Requests\Setting;

use App\Setting;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'web_socket_url'        => 'required',
            'outbound_url'          => 'required',
            'turn_server_url'       => 'required',
            'turn_server_username'  => 'required',
            'turn_server_password'  => 'required',
            'stun_server'           => 'required',
        ];
    }

    public function process()
    {   
        $settings = new Setting;

        if(Setting::count() == 1)
            $settings = Setting::latest()->first();    
        
        $settings->web_socket_url = $this->web_socket_url;
        $settings->outbound_url = $this->outbound_url;
        
        $turn_servers = [
            'urls' => $this->turn_server_url,
            'credential' => $this->turn_server_username,
            'username' => $this->turn_server_password,
        ];
        $stun_servers = ['urls' => $this->stun_server];
        $ice_servers = ['turn' => $turn_servers, 'stun' => $stun_servers];

        $settings->ice_servers = $ice_servers;
        $settings->save();

        return $settings;
    }
}

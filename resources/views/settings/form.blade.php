@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">Settings </div>
        <div class="card-body">
           <form method="POST" action="{{ route('add-setting-post') }}">
                @csrf
                <div class="form-group row">
                    <label for="name" class="col-md-3 col-form-label text-md-left">{{ __('Websocket URL') }}</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control @error('web_socket_url') is-invalid @enderror" name="web_socket_url" value="{{ (old('web_socket_url')) ? old('web_socket_url') : $settings->web_socket_url  }}" required autofocus>

                        @error('web_socket_url')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-md-3 col-form-label text-md-left">{{ __('Outbound URL') }}</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control @error('outbound_url') is-invalid @enderror" name="outbound_url" value="{{ (old('outbound_url')) ? old('outbound_url') : $settings->outbound_url }}" required >

                        @error('outbound_url')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-md-left">{{ __('Turn Server URL') }}</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control @error('turn_server_url') is-invalid @enderror" name="turn_server_url" value="{{ (old('turn_server_url')) ? old('turn_server_url') : $settings->ice_servers['turn']['urls'] }}" required>

                        @error('turn_server_url')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-md-left">{{ __('Turn Server Username') }}</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control @error('turn_server_username') is-invalid @enderror" name="turn_server_username" value="{{ (old('turn_server_username')) ? old('turn_server_username') : $settings->ice_servers['turn']['credential'] }}" required>

                        @error('turn_server_username')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-md-left">{{ __('Turn Server Password') }}</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control @error('turn_server_password') is-invalid @enderror" name="turn_server_password" value="{{ (old('turn_server_password')) ? old('turn_server_password') : $settings->ice_servers['turn']['username'] }}" required>

                        @error('turn_server_password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-md-3 col-form-label text-md-left">{{ __('STUN Server') }}</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control @error('stun_server') is-invalid @enderror" name="stun_server" value="{{ (old('stun_server')) ? old('stun_server') : $settings->ice_servers['stun']['urls'] }}" required>

                        @error('stun_server')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-3">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Save') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

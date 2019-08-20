@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">Add User </div>
        <div class="card-body">
           <form method="POST" action="{{ route('add-user-post') }}">
                @csrf
                <div class="form-group row">
                    <label for="name" class="col-md-2 col-form-label text-md-left">{{ __('Name') }}</label>

                    <div class="col-md-6">
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-md-2 col-form-label text-md-left">{{ __('E-Mail Address') }}</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-md-left">{{ __('Sip Username') }}</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control @error('sip_username') is-invalid @enderror" name="sip_username" value="{{ old('sip_username') }}" required>

                        @error('sip_username')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-md-2 col-form-label text-md-left">{{ __('Sip Password') }}</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control @error('sip_password') is-invalid @enderror" name="sip_password" value="{{ old('sip_password') }}" required>

                        @error('sip_password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-md-2 col-form-label text-md-left">{{ __('Sip Address') }}</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control @error('sip_address') is-invalid @enderror" name="sip_address" value="{{ old('sip_address') }}" required>

                        @error('sip_address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-2">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Add') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

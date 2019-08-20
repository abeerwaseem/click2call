@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">Users <a style="float: right" href="{{ route('add-user') }}">Add User</a></div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Sip Username</th>
                            <th>Sip Password</th>
                            <th>Sip Address</th>
                            <th>Auth</th>
                            <th>Created Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->sip->sip_username }}</td>
                                <td>{{ $user->sip->sip_password }}</td>
                                <td>{{ $user->sip->sip_address }}</td>
                                <td>{{ $user->sip->sip_auth }}</td>
                                <td>{{ \Carbon\Carbon::parse($user->created_at)->format('m/d/Y') }}</td>
                                <td>
                                    
                                    <button type="button" class="btn btn-primary" onClick="window.location = '{{ route('edit-user', ['user' => $user->id]) }}'">Edit</button> 
                                    <form action="{{route('users.destroy',[$user->id])}}" method="POST">
                                     @method('DELETE')
                                     @csrf
                                     <button class="btn btn-primary"  type="submit">Delete</button>               
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>  
            </div>  
        </div>
    </div>
@endsection

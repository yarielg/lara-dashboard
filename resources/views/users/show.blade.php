@extends('layouts.app')

@section('title','All Users')

@section('sidebar')
    <div class="col-ms-3">
        <a href="{{route('users.index')}}" class="btn btn-primary btn-sm">Go Back</a>
    </div>
@endsection

@section('content')
    <div class="col-md-9">
        <h3>User # {{$user->id}}</h3>
        <hr>
        <p><b>Name: </b>{{$user->name}}</p>
        <p><b>Email: </b>{{$user->email}}</p>

        <a class="btn btn-primary" href="{{route('users.edit',$user)}}" role="button"><i class="fas fa-pencil-alt"></i> Edit User</a>
    </div>

@endsection

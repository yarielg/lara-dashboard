@extends('layouts.app')

@section('title','Edit User')

@section('sidebar')
    <div class="col-md-3">
        <a href="{{route('users.index')}}" class="btn btn-primary btn-sm">Go Back</a>
    </div>
@endsection

@section('content')
    <div class="col-md-9">

        @include('errors.form_error')

        @cardygl

            @slot('header','Edit User')

            @slot('body')
                    <form method="POST" action="{{route('users.update',$user)}}">

                        @method('PUT')

                        @renderygl('UserFields' , ['user' => $user])

                        <button type="submit" class="btn btn-primary">Update User</button>
                    </form>
            @endslot

        @endcardygl

    </div>
@endsection


@extends('layouts.app')

@section('title','Edit User')

@section('sidebar')
    <div class="col-md-3">
        <a href="{{route('professions.index')}}" class="btn btn-primary btn-sm">Go Back</a>
    </div>
@endsection

@section('content')
    <div class="col-md-9">

        @include('errors.form_error')

        @cardygl

        @slot('header','Edit Profession')

        @slot('body')
            <form method="POST" action="{{route('professions.update',$profession)}}">

                @method('PUT')

                @csrf
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label col-form-label-sm" for="name">Profession Name:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control form-control-sm" id="name" placeholder="Enter Profession Name" name="title" value="{{old('title',$profession->title)}}">
                    </div>

                </div>

                <button type="submit" class="btn btn-primary">Update Profession</button>
            </form>
        @endslot

        @endcardygl

    </div>
@endsection


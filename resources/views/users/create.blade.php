@extends('layouts.app')

@section('title','Create User')

@section('sidebar')
    <div class="col-md-3">
        <a href="{{route('users.index')}}" class="btn btn-primary btn-sm"><i class="fas fa-arrow-left"></i> Go Back</a>
    </div>
@endsection

@section('content')

    <div class="col-md-9">

        @include('errors.form_error')

        @component('components._card')

            @slot('header','Create User')

            @slot('body')
                <form method="post" action="{{route('users.store')}}">

                  {{-- 1-  @include('common._fields')  Using View Composer (App\Http\ViewComposer\UserFieldsComposer) to pass the data to the _fields view (Los metodos descritos aqui son para tener una mejor legibilidad en el codigo pero siempre podemos pasar los datos a la vista a travez del controlador) --}}
                  {{-- 2-  {!! new App\Http\ViewComponents\UserFields($user) !!}  Using View Component (App\Http\ViewComponents\UserFields ) method __to string, but of this way the template becomes more unreadeable--}}
                  {{-- 3- {{ new App\Http\ViewComponents\UserFields(($user)) }} Using again ViewComponent UserFields but with the method toHtml() of this way the tags {{}} not going to scape the html --}}
                    @renderygl('UserFields' , ['user' => $user]) {{--  using directive (code in App\Providers\AppServideProvider)  --}}
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-sm mt-3">Create User</button>
                    </div>
                </form>
            @endslot

        @endcomponent

    </div>


@endsection


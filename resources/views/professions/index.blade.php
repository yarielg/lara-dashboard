@extends('layouts.app')

@section('title','All Professions')

@section('content')
    <div class="col-md-9">

        <h3 class="mb-3">List of Professions</h3>

        <table class="table">
            <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col">Users Related</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($professions as $profession)

                <tr>
                    <th scope="row">{{$profession->id}}</th>
                    <td>{{$profession->title}}</td>
                    <td>{{$profession->users_count}}</td>
                    <td>
                        <form action="{{route('professions.destroy',$profession)}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <a class="btn btn-link px-1" href="{{route('professions.edit',$profession)}}" role="button"><i class="fas fa-pencil-alt"></i></a>
                            <button type="submit" class="btn btn-link px-1" role="button" {{$profession->users_count>0?'disabled':''}}><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>

            @empty
                <tr>
                    <li>There are not professions</li>
                </tr>
            @endforelse

            </tbody>
        </table>

        {{$professions->render()}}

    </div>

@endsection

@section('sidebar')

    <div class="col-md-3">
        <h3>Sidebar</h3>
    </div>

@endsection

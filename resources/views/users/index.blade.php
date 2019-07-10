@extends('layouts.app')

@section('title','All Users')

@section('content')
    <div class="col-md-9">

        <div class="row">
            <div class="col-md-10">
                <h3 class="mb-3">{{$title}}</h3>
            </div>
            <div class="col-md-2">

                <a href="{{route('users.trashed')}}" class="btn btn-primary btn-sm float-right {{isset($states)? '' : 'invisible'}}"><i class="fas fa-trash"></i> Trash</a>
                <a href="{{route('users.index')}}" class="btn btn-primary btn-sm float-right {{!isset($states)? '' : 'invisible'}}"><i class="fas fa-trash"></i> Go back</a>
            </div>
        </div>

        @includeWhen(isset($states),'users._filters')

        <table class="table">
            <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Role</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($users as $user)

                <tr>
                    <th scope="row">{{$user->id}}</th>
                    <td>
                        <p class="mb-0"><b><a href="{{route('users.show',$user)}}">{{$user->name}} </a></b>
                        @if($user->active)
                                <span class="active-state"></span>
                        @else
                                <span class="inactive-state"></span>
                        @endif
                        </p>
                        <p class="mb-0 text-muted text-small">{{$user->team->name?: 'Not Assigned'}}</p>
                        <p class="mb-0"><span class="badge badge-info">{{isset($user->profession)?$user->profession->title:'Empty'}}</span></p>
                    </td>
                    <td><p><span class="text-muted">{{$user->email}}</span></p>
                        <p class="mb-0">{{ $user->skills->implode('description',', ')?: 'Not Skills'}}</p>
                    </td>
                    <td>{{$user->role}}</td>
                    @if($user->trashed())

                        <td>
                            <form action="{{route('users.destroy',$user)}}" method="POST">
                                @csrf
                                @method('DELETE')
                                <a class="btn  btn-success px-1" href="{{route('users.restore',$user)}}" role="button">Restore</a>
                                <button type="submit" class="btn btn-danger px-1" role="button">Delete</button>
                            </form>
                        </td>

                    @else

                        <td>
                            <form action="{{route('users.trash',$user)}}" method="POST">
                                @csrf
                                @method('PATCH')
                                <a class="btn btn-link px-1" href="{{route('users.show',$user)}}" role="button"><i class="fas fa-eye"></i></a>
                                <a class="btn btn-link px-1" href="{{route('users.edit',$user)}}" role="button"><i class="fas fa-pencil-alt"></i></a>
                                <button type="submit" class="btn btn-link px-1" role="button"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>

                    @endif
                </tr>

            @empty
                <tr>
                    <td></td>
                    <td><h5>There are not users to show</h5></td>
                </tr>
            @endforelse

            </tbody>
        </table>

        {{$users->appends(request(['search','team']))->render()}}

    </div>

@endsection

@section('sidebar')

    <div class="col-md-3">
        <h3>Sidebar</h3>
    </div>

@endsection

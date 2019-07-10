


<form method="get" class="form-inline" action="{{url('users')}}">

    <div class="row mb-3">
        <div class="col-12">
            @foreach(['all' => 'ALL', 'with_team' => 'With Team', 'without_team' => 'Without Team'] as $key=>$value)
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="{{$key}}"
                       name="team"
                       class="custom-control-input"
                       value="{{$key}}"
                        {{request('team') == $key ? 'checked':'' }}
                        {{empty(request('team')) && $key == 'all'? 'checked':''}}>
                <label class="custom-control-label" for="{{$key}}" >{{$value}}</label>
            </div>
            @endforeach

        </div>
    </div>

    <div class="row col-12">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Search..." name="search" value="{{request('search')}}">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit"><li class="fas fa-search"></li></button>
            </div>
        </div>
    </div>
</form>
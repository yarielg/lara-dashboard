


<form method="get" class="form-inline" action="{{url('users')}}">

    <div class="row mb-3 container-fluid">
        <div class="col-7">
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

        <div class="col-5">
            @foreach($states as $key=>$value)
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="{{$key}}"
                           name="state"
                           class="custom-control-input"
                           value="{{$key}}"
                            {{request('state') == $key ? 'checked':'' }}
                            {{empty(request('state')) && $key == 'all_states'? 'checked':''}}>
                    <label class="custom-control-label" for="{{$key}}" >{{$value}}</label>
                </div>
            @endforeach

        </div>
    </div>

    <div class="row col-12">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Search..." name="search" value="{{request('search')}}">

        </div>
        <div class="input-group mb-3 ml-3">
            <select type="text" class="form-control" placeholder="Role" name="role">
                @foreach($roles as $key=>$value)
                    <option value="{{$key}}" {{request('role') == $key ? 'selected' : ''}}>{{$value}}</option>
                @endforeach
            </select>
        </div>
        <div class="input-group mb-3 ml-auto float-right">
            <button class="btn btn-outline-secondary " type="submit"><li class="fas fa-search"></li>Search</button>
        </div>
    </div>
</form>
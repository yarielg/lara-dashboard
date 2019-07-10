@csrf
<div class="form-group row">
    <label class="col-sm-2 col-form-label col-form-label-sm" for="name">User Name:</label>
    <div class="col-sm-10">
        <input type="text" class="form-control form-control-sm" id="name" placeholder="Enter User Name" name="name" value="{{old('name',$user->name)}}">
    </div>

</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label col-form-label-sm" for="name">Email:</label>
    <div class="col-sm-10">
        <input type="text" class="form-control form-control-sm" id="email" placeholder="Enter User Email" name="email" value="{{old('email',$user->email)}}">
    </div>
</div>

<div class="form-group row">
    <label for="" class="col-sm-2 col-form-label col-form-label-sm">Profession:</label>
    <div class="col-sm-10">
        <select name="profession_id" class="form-control form-control-sm">
            <option value="" selected>Choose...</option>
            @foreach($professions as $profession)
                <option value="{{$profession->id}}" {{ old('profession_id',$user->profession_id) == $profession->id? 'selected':'' }}>{{$profession->title}}</option>
            @endforeach

        </select>
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-2 col-form-label col-form-label-sm" for="bio">Bio:</label>
    <div class="col-sm-10">
        <textarea type="text" class="form-control form-control-sm" id="bio" placeholder="Enter a Bio" name="bio" >{{old('bio',$user->profile->bio)}}</textarea>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label col-form-label-sm" for="name">Twitter:</label>
    <div class="col-sm-10">
        <input type="text" class="form-control form-control-sm" id="twitter" placeholder="Your Twitter" name="twitter" value="{{old('twitter',$user->profile->twitter)}}">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label col-form-label-sm" for="password">Password:</label>
    <div class="col-sm-10">
        <input type="password" class="form-control form-control-sm" id="password" name="password" value="{{old('password',$user->password)}}">
    </div>
</div>
<p><b>Skills:</b></p>
<div class="form-group form-check form-check-inline ">
    @foreach(App\Skill::all() as $skill)

        <input name="skills[{{$skill->id}}]"
               type="checkbox"
               class="form-check-input"
               id="{{$skill->id}}"
               value="{{$skill->id}}"
                {{in_array($skill->id,old('skills',array_column($user->skills->toArray(),'id')))?'checked':''}}>
        <label class="form-check-label mr-3"
               for="{{$skill->id}}">{{$skill->description}}
        </label>

    @endforeach
</div>
<p><b>Role:</b></p>
<div class="form-group form-check form-check-inline">
        @foreach(trans('users.roles') as $keyRole => $valueRole)

        <input class="form-check-input"
               type="radio" name="role"
               id="{{$keyRole}}"
               value="{{$keyRole}}"
                {{old('role',$user->role) == $keyRole?'checked':''}}
                {{(old('role')=='' && $keyRole == 'admin')?'checked':''}}>
        <label class="form-check-label mr-3" for="{{$keyRole}}">
            {{$valueRole}}
        </label>

    @endforeach

</div>



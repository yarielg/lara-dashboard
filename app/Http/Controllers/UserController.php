<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\User;
use App\Profession;
use App\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\DocBlock\Tags\Formatter\AlignFormatter;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $searchText = request('team');
        $stateText = request('state');
        $users = User::query()->with('team','skills','profession') // problema n+1
            ->search($searchText)     //Using scope in User Model
            ->byState($stateText)
            ->orderBy('created_at','DESC')
            ->paginate(10);
        return view('users.index',[
            'users' => $users,
            'title' => 'List of Users',
            'roles' => trans('users.filters.roles'),
            'states' => trans('users.filters.states')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = new User;
        return view('users.create',compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {

        $request->createUserYeah();

        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        if($user == null){
            return response()->view('errors.404',[],404);
        }
        return view('users.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {

      //  $user=null;
        if($user == null){
            return response()->view('errors.404',[],404);
        }
        return view('users.edit',['user'=>$user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {



        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'required|min:6',
            'bio' => 'required',
            'twitter' => 'nullable|url',
            'profession_id' => 'nullable|exists:professions,id',
            'skills' => 'array|exists:skills,id',
            'role' => ''
        ],[
            'name.required' => 'The name field is required',
            'email.required' => 'The email field is required',
            'email.email' => 'The email provided is not a valid email',
            'email.unique' => 'The email provided already exists',
            'password.required' => 'The password field is required',
            'password.min' => 'The password should have at least 6 characters',
            'twitter.url' => 'The field twitter must be an url'
        ]);
        DB::transaction(function() use ($request,$user){

            $data = $request->all();


            $user->profile()->update([
                'bio' => $data['bio'],
                'twitter' => isset($data['twitter'])? $data['twitter'] : null
            ]);
            $data['password'] = bcrypt($data['password']);
            $user->skills()->sync(isset($data['skills'])?$data['skills']:[]);
            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'profession_id' => $data['profession_id'],
                'role' => $data['role']
            ]);


        });



        return redirect()->route('users.show',compact('user'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) //aqui se pasa el id y no el usuario porque para laravel el usuario esta borrado aunque aun permanezca en la base de datos(soft delete) o sea no se puede usar Route model binding con soft deletes(ver codiogo en AppServiceProvider.php)
    {

        $user = User::withTrashed()->where('id',$id)->firstOrFail();
        $user->forceDelete(); //para borra permanentemente

        return redirect()->route('users.trashed');
    }

    public function trash(User $user){

        $user->delete();
        return redirect()->route('users.index');
    }

    public function trashed(){
        $users = User::onlyTrashed()->paginate(10);
        $title = 'Users Deleted Temporary';
        return view('users.index',compact('users','title'));
    }


    public function restore($id){
        $user = User::onlyTrashed()->find($id);
        $user->restore();
        return redirect()->route('users.index');
    }
}

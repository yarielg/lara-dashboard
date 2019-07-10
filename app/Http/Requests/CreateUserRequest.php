<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\User;
use Illuminate\Support\Facades\DB;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'bio' => 'required',
            'twitter' => 'nullable|url',
            'profession_id' => 'nullable|exists:professions,id',
            'skills' => 'array|exists:skills,id',
            'role' => ''
        ];
    }

    public function messages(){
        return [
            'name.required' => 'The name field is required',
            'email.required' => 'The email field is required',
            'email.email' => 'The email provided is not a valid email',
            'email.unique' => 'The email provided already exists',
            'password.required' => 'The password field is required',
            'password.min' => 'The password should have at least 6 characters',
            'profession_id.exists' => 'The profession is not valid',
        ];
    }

    public function createUserYeah(){

        DB::transaction(function(){ //Transaction mantiene la consistencia en la base de dato
            $data = $this->validated(); //Get the validated Data from the request
            $user =  new User();
            $user->forceFill([ //deja incluir propiedades que no se encuentran el arreglo $fillable
                'name' => $data['name'], //$this->name is other way to get the data
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'profession_id' => $data['profession_id'],
                'role' => $this->role
            ]);

          // $user->role = $this->role;
            $user->save();

            $user->profile()->create([
                'bio' => $data['bio'],
                'twitter' => isset($data['twitter'])? $data['twitter'] : null
            ]);

            $user->skills()->attach(isset($data['skills'])?$data['skills']:[]);
        });
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User;
use Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userList = DB::table('users')->orderBy('id','DESC')->paginate(10);
        // $userList = DB::table('users')->orderBy('id','DESC')->get();
        return view('users.index', ['userList' => $userList]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $validate = Validator::make($request->all(),[
            'username' => 'required|unique:users,username|alpha_num|min:5',
            'password' => 'required|min:5',
            'confirm_password' => 'required|same:password|min:5',
            'name' => 'required|max:100',
            'email' => 'required|unique:users,email|max:100'
        ]);
        if ($validate->fails()) {
            return redirect('users/create')->withInput()->withErrors($validate);
        }
        else {
            User::create($request->all());
            return redirect('users')->with(['add' => 'Add New User Success !!!!!!!']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $userShow = User::findOrFail($id);
        return view('users.show', ['user' => $userShow]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::where('id', $id)->first();
        return view('users.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $user = User::findOrFail($id);
        $validate = $request->validate([
            'username' => 'required',
            'password' => 'required',
            'name' => 'required',
            'email' => 'required', 
            'role' => 'required',
        ]);
        $user->Update($request->all());
        return redirect('users')->with(['edit' => 'Update Success !!!']);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy($id);
        return redirect('/users')->with(['delete' => 'Delete User Success !!!']);
    }

    public function search($name){
        $name = "$name%";
        $rs = User::where('name', 'like', $name)->get();
        return $rs;
    }
}

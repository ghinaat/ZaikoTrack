<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $user = User::all();

        return view('users.index', [
            'user' => $user,
        ]);
    }

    public function store(Request $request)
    {
        //Menyimpan Data pegawai
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'level' => 'required',
        ]);

        $array = $request->only([
            'name',
            'email',
            'password',
            'level',
        ]);

        $array['_password_'] = $request->password;

        $user = User::create($array);

        return redirect()->back()->with([
            'success_message' => 'Data telah tersimpan',
        ]);
    }

    
    public function update(Request $request, $id_users)
    {

        $rules = [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'level' => 'required',
        ];

        if (isset($request->password)) {
            $rules['password'] = 'required|confirmed';
        }

        $request->validate($rules);

        $user = User::find($id_users);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->level = $request->level;
        $user->save();

        return redirect()->route('user.index')->with([
            'success_message' => 'Data telah tersimpan',
        ]);
    }

    public function destroy($id_users)
    {
        $user = User::find($id_users);
        if ($user) {
            $user->delete();
        }

        return redirect()->back()->with('success_message', 'Data telah terhapus.');
    }

    public function changePassword(Request $request)
    {
        return view('users.change-pass');
    }

    public function saveChangePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|string|confirmed',
        ]);

        // Get the authenticated user
        $user = auth()->user();

        // Check if the current password matches the user's password in the database
        if (! Hash::check($request->input('old_password'), $user->password)) {
            return back()->withErrors(['old_password' => 'The current password is incorrect.']);
        }

        // Update the user's password
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return redirect()->route('user.changePassword')->with('success', 'Password changed successfully.');
    }

}
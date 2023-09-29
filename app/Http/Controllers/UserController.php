<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

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
        ]);

        $array = $request->only([
            'name',
            'email',
            'password',
        ]);

        $array['_password_'] = $request->password;

        $user = User::create($array);

        return redirect()->back()->with([
            'success_message' => 'Data telah tersimpan',
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
        }

        return redirect()->back()->with('success_message', 'Data telah tersimpan.');
    }
}
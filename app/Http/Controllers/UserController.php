<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index()
    {
        $user = User::all()->except('1');

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
            'nis' => 'required',
        ]);

        $array = $request->only([
            'name',
            'email',
            'password',
            'level',
            'nis',
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
            'nis' => 'required',
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

    public function import(Request $request)
    {
        
        Excel::import(new SiswaImport, $request->file('file')->store('user'));

        return redirect()->back()->with([
            'success_message' => 'Data telah Tersimpan',
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
        // Get the authenticated user
        $user = User::where('id_users', auth()->user()->id_users)->first();

        // Pass the user's email to the view
        return view('users.change-pass', [  'user' => $user,]);
    }


    public function saveChangePassword(Request $request, $id_users)
    {
        $rules = [
            'name' => 'required',
            'nis' => 'nullable',
            'email' => 'required',
            'password' => 'nullable',
            'old_password' => 'nullable|required_with:password|string',
            // 'level' => 'required',
        ];

       

        $request->validate($rules);

        $user = User::find($id_users);
        if ($request->filled('old_password')) {
            if (!Hash::check($request->old_password, $user->password)) {
                return back()->withErrors(['old_password' => 'The current password is incorrect.']);
            }
    
            $user->password = Hash::make($request->password);
        }       
        $user->name = $request->name;
        $user->nis = $request->nis;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
      
        $user->save();

       

        return redirect()->route('user.changePassword')->with('success', 'Profile Berhasil Disimpan.');
    }

}
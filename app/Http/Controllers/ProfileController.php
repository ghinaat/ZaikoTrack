<?php

namespace App\Http\Controllers;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function index()
    {
        $user = User::where('id_users', auth()->user()->id_users)->first();
        $profile = Profile::where('id_users', auth()->user()->id_users)->first();
        return view('users.profile', [
            'user' => $user,
            'profile' => $profile,
           
        ]);
    }

    public function showAdmin(Request $request, $id_users)
    {
        $user = User::where('id_users', $id_users)->first();
        $profile = Profile::where('id_users', $id_users)->first();
        
        return view('users.profile', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    public function update(Request $request, $id_users)
    {
        $rules = [
            'name' =>  'required',
            'email' =>  'required',
            'nis' =>  'nullable',
            'jurusan' =>  'nullable',
            'kelas' =>  'nullable',
        ];
    
        if ($request->file('image')) {
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg';
        }
    
        $user = User::find($id_users);
    
        // Update user's name and email
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ]);
    
        // Update or create the user's profile
        $profile = Profile::updateOrCreate(
            ['id_users' => $id_users],
            [
                'nis' => $request->input('nis'),
                'kelas' => $request->input('kelas'),
                'jurusan' => $request->input('jurusan'),
            ]
        );
    
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old profile image if exists
            if ($profile->image) {
                Storage::disk('public')->delete('profile/'.$profile->image);
            }
    
            // Store new image
            $file = $request->file('image');
            $fileName = Str::random(10).'.'.$file->getClientOriginalExtension();
            $file->storeAs('profile', $fileName, 'public');
    
            // Update profile with new image
            $profile->image = $fileName;
        }
    
        $profile->save();
    
        return redirect()->back()->with('success_message', 'Profile berhasil diubah!');
    }
    

}
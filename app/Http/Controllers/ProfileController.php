<?php
	
	namespace App\Http\Controllers;
	
	use App\Http\Services\UploadService;
	use App\Models\Video;
	use App\Models\WatchHistory;
	use App\Rules\NotExecutable;
	use Illuminate\Http\Request;
	use App\Http\Controllers\Controller;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Hash;
	use Illuminate\Support\Facades\Storage;
	
	class ProfileController extends Controller
		{
			public function show()
				{
					$user         = Auth::user();
					$watchHistory = WatchHistory::where('user_id', $user->id)
												->with('video')
												->latest('watched_at')
												->paginate(10); 
					return view('Frontend.profile.show', compact('user', 'watchHistory'));
				}
			
			public function edit()
				{
					return view('Frontend.profile.edit', ['user' => Auth::user()]);
				}
			
			public function update(Request $request)
				{
					$user = Auth::user();
					
					$request->validate([
						                   'name'  => 'required|string|max:255',
						                   'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
						                   'phone' => 'nullable|string|max:255',
						                   'image' => ['required','image','mimes:jpeg,png,jpg,gif,svg,webp','max:2048',new NotExecutable],
					                   ]);
					
					if ($request->hasFile('image'))
						{
							$image       = new UploadService();
							$upload      = $image->file_upload($request, 'image',
							                                   'image', 'public_2');
							$user->image = $upload['path'];
							
						}
					
					$user->update($request->only('name', 'email', 'phone'));
					$user->save();
					
					return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
				}
			
			public function passwordEdit()
				{
					return view('profile.password');
				}
			
			
			public function passwordUpdate(Request $request)
				{
					$request->validate([
						                   'current_password' => 'required',
						                   'password'         => 'required|confirmed|min:8',
					                   ]);
					
					$user = Auth::user();
					
					if (!Hash::check($request->input('current_password'), $user->password))
						{
							return back()->withErrors(['current_password' => 'Current password is incorrect']);
						}
					
					$user->password = Hash::make($request->input('password'));
					$user->save();
					
					return redirect()->route('profile.show')->with('success', 'Password updated successfully.');
				}
		}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use App\Models\Cart;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $cartItems = Auth::user()->cartItems()->with('product.game')->get();

        $user->load('favorites.game',
                    'library.product.game',
                    'library.product.platform'                
    );

        return view('profile.edit', [
            'user' => $user,
            'cartItems' => $cartItems
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $validatedData = $request->validated();

        if ($request->hasFile('photo')){

            if($user->profile_photo_path){
                Storage::disk('public')->delete($user->profile_photo_path);
            }
        $path = $request->file('photo')->store('profile-photos', 'public');

        $validatedData['profile_photo_path'] = $path;

        }

        $user->fill($validatedData);



        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return back()->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

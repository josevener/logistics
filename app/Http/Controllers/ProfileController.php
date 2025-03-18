<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */

    public function create()
    {
        return view('profile.create');
    }
    public function show(): View
    {
        return view('profile.show');
    }

    /**
     * Show the profile edit form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Fill the validated data, including the new fields
        $user->fill($request->validated());

        // Handle the vendor profile (assuming $user has a related 'vendor' model)
        $vendor = $user->vendor;

        // Update vendor-specific fields
        $vendor->fname = $request->input('fname');
        $vendor->mname = $request->input('mname');
        $vendor->lname = $request->input('lname');
        $vendor->contact_info = $request->input('contact_info');
        $vendor->address = $request->input('address');

        // Concatenate the name (First Name, Middle Name, Last Name)
        $vendor->name = $this->concatenateName($vendor->fname, $vendor->mname, $vendor->lname);

        // Save the vendor profile
        $vendor->save();

        // Reset email verification if email is changed
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Save the user profile
        $user->save();

        return back()->with('status', 'Profile updated successfully.');
    }

    /**
     * Concatenate first name, middle name, and last name into a full name.
     *
     * @param  string $fname
     * @param  string $mname
     * @param  string $lname
     * @return string
     */
    protected function concatenateName(string $fname, ?string $mname, string $lname): string
    {
        $fullName = $fname;

        if (!empty($mname)) {
            $fullName .= ' ' . strtoupper(substr($mname, 0, 1)) . '.'; // Middle name initial
        }

        $fullName .= ' ' . $lname;

        return $fullName;
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Log::info('Delete account attempt by user: ' . $request->user()->id);

        $validated = $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        // Delete the user
        $user->delete();

        // Invalidate and regenerate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('User account deleted: ' . $user->id);

        return Redirect::to('/')->with('status', 'Your account has been deleted.');
    }
}

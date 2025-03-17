<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Name Field -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- First Name Field -->
        <div>
            <x-input-label for="fname" :value="__('First Name')" />
            <x-text-input id="fname" name="fname" type="text" class="mt-1 block w-full" :value="old('fname', $user->vendor->fname)"
                required autocomplete="given-name" />
            <x-input-error class="mt-2" :messages="$errors->get('fname')" />
        </div>

        <!-- Middle Name Field -->
        <div>
            <x-input-label for="mname" :value="__('Middle Name')" />
            <x-text-input id="mname" name="mname" type="text" class="mt-1 block w-full" :value="old('mname', $user->vendor->mname)"
                autocomplete="additional-name" />
            <x-input-error class="mt-2" :messages="$errors->get('mname')" />
        </div>

        <!-- Last Name Field -->
        <div>
            <x-input-label for="lname" :value="__('Last Name')" />
            <x-text-input id="lname" name="lname" type="text" class="mt-1 block w-full" :value="old('lname', $user->vendor->lname)"
                required autocomplete="family-name" />
            <x-input-error class="mt-2" :messages="$errors->get('lname')" />
        </div>

        <!-- Email Field -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Contact Info Field -->
        <div>
            <x-input-label for="contact_info" :value="__('Contact Information')" />
            <x-text-input id="contact_info" name="contact_info" type="text" class="mt-1 block w-full"
                :value="old('contact_info', $user->vendor->contact_info)" autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('contact_info')" />
        </div>

        <!-- Address Field (using regular <textarea> instead of x-textarea) -->
        <div>
            <x-input-label for="address" :value="__('Address')" />
            <textarea id="address" name="address" class="mt-1 block w-full" rows="4" placeholder="{{ __('Enter your address...') }}">{{ old('address', $user->vendor->address) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
<p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
@endif
        </div>
    </form>
</section>

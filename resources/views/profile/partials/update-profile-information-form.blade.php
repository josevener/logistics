<section class="p-4">
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-3 space-y-3">
        @csrf
        @method('patch')

        <!-- Name Field -->
        <div class="text-sm hidden ">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-3/4 md:w-full text-sm p-2"
                :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('name')" />
        </div>

        <!-- Name Fields (Stacked in Mobile, Row in Desktop) -->
        <div class="flex flex-col md:flex-row gap-4 w-full mt-0">
            <!-- First Name -->
            <div class="text-sm w-full">
                <x-input-label for="fname" :value="__('First Name')" />
                <x-text-input id="fname" name="fname" type="text" class="mt-1 block w-full text-sm p-2"
                    :value="old('fname', $user->vendor->fname)" required autocomplete="given-name" />
                <x-input-error class="mt-1 text-xs" :messages="$errors->get('fname')" />
            </div>

            <!-- Middle Name -->
            <div class="text-sm w-full">
                <x-input-label for="mname" :value="__('Middle Name')" />
                <x-text-input id="mname" name="mname" type="text" class="mt-1 block w-full text-sm p-2"
                    :value="old('mname', $user->vendor->mname)" autocomplete="additional-name" />
                <x-input-error class="mt-1 text-xs" :messages="$errors->get('mname')" />
            </div>

            <!-- Last Name -->
            <div class="text-sm w-full">
                <x-input-label for="lname" :value="__('Last Name')" />
                <x-text-input id="lname" name="lname" type="text" class="mt-1 block w-full text-sm p-2"
                    :value="old('lname', $user->vendor->lname)" required autocomplete="family-name" />
                <x-input-error class="mt-1 text-xs" :messages="$errors->get('lname')" />
            </div>
        </div>

        <!-- Email & Contact Info Row -->
        <div class="flex flex-col md:flex-row gap-4 w-full">
            <!-- Email -->
            <div class="text-sm w-full md:w-1/2">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full text-sm p-2"
                    :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-1 text-xs" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                    <div class="mt-1 text-xs text-gray-800">
                        <p>{{ __('Your email address is unverified.') }}</p>
                        <button form="send-verification"
                            class="underline text-gray-600 hover:text-gray-900 focus:ring-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send verification email.') }}
                        </button>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-1 text-green-600">{{ __('A new verification link has been sent.') }}</p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Contact Info -->
            <div class="text-sm w-full md:w-1/2">
                <x-input-label for="contact_info" :value="__('Contact Information')" />
                <x-text-input id="contact_info" name="contact_info" type="text" class="mt-1 block w-full text-sm p-2"
                    :value="old('contact_info', $user->vendor->contact_info)" autocomplete="tel" />
                <x-input-error class="mt-1 text-xs" :messages="$errors->get('contact_info')" />
            </div>
        </div>

        <!-- Address (Full Width) -->
        <div class="text-sm w-full">
            <x-input-label for="address" :value="__('Address')" />
            <textarea id="address" name="address" class="mt-1 block w-full text-sm p-2" rows="3"
                placeholder="{{ __('Enter your address...') }}">{{ old('address', $user->vendor->address) }}</textarea>
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('address')" />
        </div>

        <div class="flex items-center gap-2">
            <x-primary-button
                class="w-full flex justify-center text-sm px-3 py-1">{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs text-gray-600">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>

<x-app-layout>
    <div class="container">
        <h2>Profile</h2>

        <!-- User Info -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">{{ auth()->user()->name }}</h5>
                <p class="card-text"><strong>Email:</strong> {{ auth()->user()->email }}</p>
                <p class="card-text"><strong>Joined:</strong> {{ auth()->user()->created_at->format('F d, Y') }}</p>
            </div>
        </div>

        <!-- Update Profile Form -->
        <div class="card mb-4">
            <div class="card-header">Update Profile Information</div>
            <div class="card-body">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- Update Password Form -->
        <div class="card mb-4">
            <div class="card-header">Change Password</div>
            <div class="card-body">
                @include('profile.partials.update-password-form')
            </div>
        </div>
    </div>
</x-app-layout>

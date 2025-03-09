<style>
    .bg-gradient {
        background: linear-gradient(135deg, #6a85e3 0%, #4466f2 100%);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .tab-button {
        transition: all 0.3s ease;
        padding-bottom: 8px;
    }

    .tab-button:hover {
        color: #4466f2;
    }

    .tab-button.active {
        color: #4466f2;
        border-bottom: 2px solid #4466f2;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .file-input {
        background-color: #f8f9fc;
        border: 1px solid #d1d5db;
        transition: all 0.3s ease;
    }

    .file-input:hover {
        background-color: #edf2f7;
    }

    .profile-card {
        background: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        padding: 1.5rem;
        border-radius: 12px;
        text-align: center;
    }
</style>

<x-app-layout>
    <div class="flex-1 min-h-screen bg-gray-100">
        <!-- Header -->
        <div class="bg-gradient h-20 sm:h-28 md:h-52 relative"></div>

        <!-- Main Card -->
        <div class="max-w-6xl mx-auto -mt-16 sm:-mt-20 md:-mt-24 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-lg flex flex-col md:flex-row gap-6 sm:gap-8 p-6 sm:p-8">
                <!-- Left Content: Account Settings Form -->
                <div class="flex-1 w-full">
                    <!-- Tabs -->
                    <div class="border-b flex flex-wrap gap-4 sm:gap-6 lg:gap-8 mb-4 sm:mb-6">
                        <button class="tab-button active px-3 py-2 text-sm sm:text-base font-medium focus:outline-none"
                            data-tab="account">
                            Account Settings
                        </button>
                        <button
                            class="tab-button px-3 py-2 text-sm sm:text-base font-medium text-gray-500 focus:outline-none"
                            data-tab="company">
                            Company Settings
                        </button>
                        <button
                            class="tab-button px-3 py-2 text-sm sm:text-base font-medium text-gray-500 focus:outline-none"
                            data-tab="documents">
                            Documents
                        </button>
                        <button
                            class="tab-button px-3 py-2 text-sm sm:text-base font-medium text-gray-500 focus:outline-none"
                            data-tab="billing">
                            Billing
                        </button>
                        <button
                            class="tab-button px-3 py-2 text-sm sm:text-base font-medium text-gray-500 focus:outline-none"
                            data-tab="notifications">
                            Notifications
                        </button>
                    </div>

                    <!-- Tab Contents -->
                    <div class="tab-content active" id="account">
                        <h2 class="text-2xl sm:text-3xl font-semibold mb-6 text-gray-800">Register Your Company</h2>
                        <form class="space-y-6" action="" method="post">
                            <div>
                                <label for="company_info" class="block text-base font-medium text-gray-700 mb-2">Company
                                    Information</label>
                                <input
                                    class="block w-full bg-gray-100 text-gray-700 border border-gray-300 rounded-lg py-3 px-4 text-base focus:outline-none focus:bg-white focus:border-blue-500 transition-colors"
                                    id="company_info" type="text" placeholder="Company Name" required />
                            </div>
                            <div>
                                <label for="contact_details"
                                    class="block text-base font-medium text-gray-700 mb-2">Contact Details</label>
                                <input
                                    class="block w-full bg-gray-100 text-gray-700 border border-gray-300 rounded-lg py-3 px-4 text-base focus:outline-none focus:bg-white focus:border-blue-500 transition-colors"
                                    id="contact_details" type="text" placeholder="e.g., email@example.com"
                                    required />
                            </div>
                            <div>
                                <label for="documentation"
                                    class="block text-base font-medium text-gray-700 mb-2">Documentation</label>
                                <input type="file" id="documentation" name="documentation"
                                    class="file-input block w-full text-gray-500 py-2 px-3 text-base rounded-lg cursor-pointer"
                                    required />
                            </div>
                            <button type="submit"
                                class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg text-base font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                                Register Company
                            </button>
                        </form>
                    </div>

                    <div class="tab-content" id="company">
                        <h2 class="text-2xl font-semibold mb-6 text-gray-800">Company Settings</h2>
                        <p>Settings related to your company details will be here.</p>
                    </div>

                    <div class="tab-content" id="documents">
                        <h2 class="text-2xl font-semibold mb-6 text-gray-800">Documents</h2>
                        <p>Manage company documents here.</p>
                    </div>

                    <div class="tab-content" id="billing">
                        <h2 class="text-2xl font-semibold mb-6 text-gray-800">Billing</h2>
                        <p>View and manage billing details here.</p>
                    </div>

                    <div class="tab-content" id="notifications">
                        <h2 class="text-2xl font-semibold mb-6 text-gray-800">Notifications</h2>
                        <p>Manage notifications and alerts.</p>
                    </div>
                </div>

                <!-- Right Content: Profile Card -->
                <div class="w-full md:w-64 lg:w-72 flex flex-col items-center md:border-l md:pl-6 lg:pl-8">
                    <div class="profile-card">
                        <div class="relative">
                            <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-DdqTN0X4rJ55yMBHnX70tuun4EN5TY.png"
                                alt="Profile" class="w-24 h-24 rounded-full object-cover shadow-md" />
                        </div>
                        <h2 class="mt-4 text-xl font-semibold text-gray-800">Sample User</h2>
                        <p class="text-sm text-gray-500">Bus Terminal Owner</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.querySelectorAll('.tab-button').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('.tab-button').forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                const tabId = tab.getAttribute('data-tab');
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                });
                document.getElementById(tabId).classList.add('active');
            });
        });
    </script>
</x-app-layout>

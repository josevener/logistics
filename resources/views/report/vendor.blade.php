<x-app-layout>
    <div
        class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
        <div class="w-full max-w-4xl mx-auto">
            <!-- Header -->
            @include('navigation.header')

            <!-- Feedback Form -->
            <div
                class="bg-white rounded-lg w-full max-w-md mx-auto p-4 sm:p-6 shadow-lg hover:shadow-xl transition-transform hover:-translate-y-0.5">
                <!-- Form Header -->
                <div class="flex justify-between items-center mb-4 sm:mb-6">
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Give Feedback</h2>
                </div>

                <!-- Rating Question -->
                <div class="mb-6">
                    <p class="text-sm sm:text-base text-gray-700 mb-3 sm:mb-4">What do you think of the editing tool?
                    </p>
                    <div class="flex justify-between gap-2 sm:gap-4">
                        <button class="rating-button p-2 rounded-full hover:bg-gray-100 transition-all hover:scale-110"
                            data-rating="1" aria-label="Very Sad">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.59-13L12 10.59 8.41 7 7 8.41 10.59 12 7 15.59 8.41 17 12 13.41 15.59 17 17 15.59 13.41 12 17 8.41z" />
                            </svg>
                        </button>
                        <button class="rating-button p-2 rounded-full hover:bg-gray-100 transition-all hover:scale-110"
                            data-rating="2" aria-label="Sad">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-5-9c.83 0 1.5-.67 1.5-1.5S7.83 8 7 8s-1.5.67-1.5 1.5S6.17 11 7 11zm5 0c.83 0 1.5-.67 1.5-1.5S12.83 8 12 8s-1.5.67-1.5 1.5.67 1.5 1.5 1.5zm5 0c.83 0 1.5-.67 1.5-1.5S17.83 8 17 8s-1.5.67-1.5 1.5.67 1.5 1.5 1.5zM12 14c-2.33 0-4.31 1.46-5.11 3.5h10.22c-.8-2.04-2.78-3.5-5.11-3.5z" />
                            </svg>
                        </button>
                        <button class="rating-button p-2 rounded-full hover:bg-gray-100 transition-all hover:scale-110"
                            data-rating="3" aria-label="Neutral">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M9 14h6v1.5H9z" />
                                <circle cx="15.5" cy="9.5" r="1.5" />
                                <circle cx="8.5" cy="9.5" r="1.5" />
                                <path
                                    d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z" />
                            </svg>
                        </button>
                        <button
                            class="rating-button p-2 rounded-full bg-blue-50 hover:bg-gray-100 transition-all hover:scale-110 active"
                            data-rating="4" aria-label="Happy">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-blue-500" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm-5-7h10c-.35-2.82-2.45-5-5-5s-4.65 2.18-5 5z" />
                                <circle cx="8.5" cy="9.5" r="1.5" />
                                <circle cx="15.5" cy="9.5" r="1.5" />
                            </svg>
                        </button>
                        <button class="rating-button p-2 rounded-full hover:bg-gray-100 transition-all hover:scale-110"
                            data-rating="5" aria-label="Very Happy">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Feedback Text -->
                <div class="mb-6">
                    <label for="feedback-text" class="text-sm sm:text-base text-gray-700 mb-2 block">Do you have any
                        thoughts you'd like to share?</label>
                    <textarea id="feedback-text"
                        class="w-full border border-gray-300 rounded-lg p-3 text-sm sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-y"
                        rows="4" placeholder="Share your thoughts..." aria-label="Feedback"></textarea>
                </div>

                <!-- Follow-up Permission -->
                <div class="mb-6">
                    <p class="text-sm sm:text-base text-gray-700 mb-2 sm:mb-3">May we follow up on your feedback?</p>
                    <div class="flex gap-4 sm:gap-6">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="follow-up" value="yes"
                                class="w-4 h-4 text-blue-500 focus:ring-blue-500 border-gray-300 cursor-pointer"
                                checked>
                            <span class="ml-2 text-sm sm:text-base text-gray-700">Yes</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="follow-up" value="no"
                                class="w-4 h-4 text-blue-500 focus:ring-blue-500 border-gray-300 cursor-pointer">
                            <span class="ml-2 text-sm sm:text-base text-gray-700">No</span>
                        </label>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:outline-none transition-colors">
                        Send
                    </button>
                    <button type="button"
                        class="flex-1 border border-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-100 focus:ring-2 focus:ring-gray-300 focus:outline-none transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ratingButtons = document.querySelectorAll('.rating-button');

            ratingButtons.forEach(button => {
                button.addEventListener('click', () => {
                    ratingButtons.forEach(btn => {
                        btn.classList.remove('active', 'bg-blue-50');
                        btn.querySelector('svg').classList.remove('text-blue-500');
                    });
                    button.classList.add('active', 'bg-blue-50');
                    button.querySelector('svg').classList.add('text-blue-500');
                });
            });
        });
    </script>
</x-app-layout>

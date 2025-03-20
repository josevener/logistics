<x-app-layout>
    <div class="flex-1 p-4 sm:p-6 md:p-8 lg:p-10 bg-gray-100 min-h-screen dark:bg-gray-900">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Marketplace Store</h1>
                <div class="mt-4 sm:mt-0 flex items-center gap-4">
                    <p class="text-gray-700 dark:text-gray-300">Items in Cart: <span
                            class="font-semibold">{{ count(session('cart', [])) }}</span></p>
                    <a href="{{ route('marketplace.admin.cart') }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600">
                        View Cart
                    </a>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="mb-6 bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" id="search" placeholder="Search products, and services..."
                            class="w-full p-2 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex gap-2">
                        <select id="type-filter"
                            class="p-2 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">All Types</option>
                            <option value="service">Services</option>
                            <option value="items">Items/Products</option>
                        </select>
                        <select id="price-filter"
                            class="p-2 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">Sort by Price</option>
                            <option value="low-high">Low to High</option>
                            <option value="high-low">High to Low</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Product Listings -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="product-list">
                @forelse ($products as $product)
                    <div class="bg-white rounded-lg shadow-md p-4 dark:bg-gray-800 transition-transform transform hover:scale-105 duration-200 product-card"
                        data-type="{{ $product->type }}" data-price="{{ $product->price }}">
                        <div class="flex flex-col h-full">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">
                                {{ $product->name }}</h3>
                            <p class="text-gray-600 dark:text-gray-300 mt-1 flex-1 line-clamp-2">
                                {{ $product->description }}</p>
                            <p class="text-gray-800 dark:text-gray-100 mt-2 font-medium">
                                ₱{{ number_format($product->price, 2) }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Vendor:
                                {{ $product->vendor->user->name }}</p>
                            @if ($product->type === 'items')
                                <p
                                    class="text-sm mt-1 {{ $product->stock <= 0 ? 'text-red-500' : 'text-gray-600 dark:text-gray-300' }}">
                                    Stock: {{ $product->stock > 0 ? $product->stock : 'Out of Stock' }}
                                </p>
                            @else
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Service Available</p>
                            @endif
                            <div class="mt-4">
                                @if ($product->type === 'items' && $product->stock <= 0)
                                    <span
                                        class="inline-block w-full text-center py-2 bg-gray-300 text-gray-600 rounded-md cursor-not-allowed">Out
                                        of Stock</span>
                                @else
                                    <button type="button" id="open-add-to-cart-{{ $product->id }}"
                                        class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600">
                                        Add to Cart
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-10">
                        <p class="text-gray-700 dark:text-gray-300 text-lg">No products available.</p>
                    </div>
                @endforelse
            </div>

            <!-- Add to Cart Modals -->
            @foreach ($products as $product)
                @if (!($product->type === 'items' && $product->stock <= 0))
                    <x-modal name="add-to-cart-modal-{{ $product->id }}" :show="false" focusable maxWidth="md"
                        class="flex items-center justify-center">
                        <div class="p-6 bg-white rounded-lg dark:bg-gray-800 w-full max-w-md">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Add
                                {{ $product->name }} to Cart</h2>
                            <div class="space-y-4">
                                <p class="text-gray-600 dark:text-gray-300">{{ $product->description }}</p>
                                <p class="text-gray-800 dark:text-gray-100 font-medium">Price:
                                    ₱{{ number_format($product->price, 2) }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Vendor:
                                    {{ $product->vendor->user->name }}</p>
                                @if ($product->type === 'items')
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Available Stock:
                                        {{ $product->stock }}</p>
                                @else
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Service Available</p>
                                @endif
                                <form action="{{ route('marketplace.admin.cart.add') }}" method="POST"
                                    class="space-y-4">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <div>
                                        <label for="quantity-{{ $product->id }}"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Quantity
                                        </label>
                                        <input type="number" name="quantity" id="quantity-{{ $product->id }}"
                                            min="1"
                                            @if ($product->type === 'items') max="{{ $product->stock }}" @endif
                                            value="1" required
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                        @error('quantity')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="flex justify-end gap-3">
                                        <button type="button" id="close-add-to-cart-{{ $product->id }}"
                                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">
                                            Cancel
                                        </button>
                                        <button type="submit"
                                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600">
                                            Add to Cart
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </x-modal>
                @endif
            @endforeach
        </div>
    </div>

    <!-- Client-Side Filtering, Sorting, and Modal Handling -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter and Sort Logic
            const searchInput = document.getElementById('search');
            const typeFilter = document.getElementById('type-filter');
            const priceFilter = document.getElementById('price-filter');
            const productCards = document.querySelectorAll('.product-card');

            function filterAndSortProducts() {
                const searchTerm = searchInput.value.toLowerCase();
                const typeValue = typeFilter.value;
                const priceValue = priceFilter.value;

                let products = Array.from(productCards);

                // Filter by search term and type
                products = products.filter(card => {
                    const name = card.querySelector('h3').textContent.toLowerCase();
                    const type = card.dataset.type;
                    return name.includes(searchTerm) && (!typeValue || type === typeValue);
                });

                // Sort by price
                if (priceValue) {
                    products.sort((a, b) => {
                        const priceA = parseFloat(a.dataset.price);
                        const priceB = parseFloat(b.dataset.price);
                        return priceValue === 'low-high' ? priceA - priceB : priceB - priceA;
                    });
                }

                // Update display
                productCards.forEach(card => card.style.display = 'none');
                products.forEach(card => card.style.display = 'block');
            }

            searchInput.addEventListener('input', filterAndSortProducts);
            typeFilter.addEventListener('change', filterAndSortProducts);
            priceFilter.addEventListener('change', filterAndSortProducts);

            // Modal Handling
            @foreach ($products as $product)
                @if (!($product->type === 'items' && $product->stock <= 0))
                    const openAddToCart{{ $product->id }} = document.getElementById(
                        'open-add-to-cart-{{ $product->id }}');
                    const closeAddToCart{{ $product->id }} = document.getElementById(
                        'close-add-to-cart-{{ $product->id }}');
                    if (openAddToCart{{ $product->id }}) {
                        openAddToCart{{ $product->id }}.addEventListener('click', () => {
                            window.dispatchEvent(new CustomEvent('open-modal', {
                                detail: 'add-to-cart-modal-{{ $product->id }}'
                            }));
                        });
                    }
                    if (closeAddToCart{{ $product->id }}) {
                        closeAddToCart{{ $product->id }}.addEventListener('click', () => {
                            window.dispatchEvent(new CustomEvent('close-modal', {
                                detail: 'add-to-cart-modal-{{ $product->id }}'
                            }));
                        });
                    }
                @endif
            @endforeach
        });
    </script>
</x-app-layout>

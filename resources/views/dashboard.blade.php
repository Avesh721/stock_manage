<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Stock Management Dashboard') }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- Stats Section -->
            <div class="flex justify-between mb-4 space-x-2">
                <div class="w-1/4 p-3 text-center bg-white rounded shadow-md">
                    <h6 class="text-xs font-semibold">Total Products</h6>
                    <h3 class="text-lg font-bold">{{ $product_d->count() }}</h3>
                </div>
                <div class="w-1/4 p-3 text-center bg-white rounded shadow-md">
                    <h6 class="text-xs font-semibold">Low Stock Products</h6>
                    {{ $product_d->where('quantity', '<', 5)->count() }}
                </div>
                <div class="w-1/4 p-3 text-center bg-white rounded shadow-md">
                    <a href="{{ route('stock_movement') }}"
                        class="block text-2xl font-semibold text-blue-600 hover:underline">
                        <h6 class="text-xs font-semibold">Recent Movements</h6>
                        {{ $recentMovementCount }}
                    </a>

                </div>
                <div class="flex justify-end w-1/4">
                    <button onclick="showAddModal()"
                        class="px-4 py-2 text-white bg-black rounded-md shadow hover:bg-gray-900">
                        ➕ Add Product
                    </button>
                </div>
            </div>

            <!-- Product Table -->
            <div class="p-4 overflow-hidden bg-white shadow-md sm:rounded-lg">
                <table class="w-full border border-collapse border-gray-300">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-3 py-2 text-sm border border-gray-300">#</th>
                            <th class="px-3 py-2 text-sm border border-gray-300">Product Name</th>
                            <th class="px-3 py-2 text-sm border border-gray-300">SKU</th>
                            <th class="px-3 py-2 text-sm border border-gray-300">Quantity</th>
                            <th class="px-3 py-2 text-sm border border-gray-300">Price</th>
                            <th class="px-3 py-2 text-sm border border-gray-300">Low Stock?</th>
                            <th class="px-3 py-2 text-sm border border-gray-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($product_d as $index => $product)
                            <tr class="text-center">
                                <td class="px-3 py-2 text-sm border border-gray-300">{{ $index + 1 }}</td>
                                <td class="px-3 py-2 text-sm border border-gray-300">{{ $product->name }}</td>
                                <td class="px-3 py-2 text-sm border border-gray-300">{{ $product->sku }}</td>
                                <td class="px-3 py-2 text-sm border border-gray-300">{{ $product->quantity }}</td>
                                <td class="px-3 py-2 text-sm border border-gray-300">{{ $product->price }}</td>
                                <td class="px-3 py-2 text-sm border border-gray-300">
                                    @if ($product->quantity < 5)
                                        <span class="text-yellow-500">⚠️ Yes (Low)</span>
                                    @else
                                        <span class="text-green-500">✔️ No</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2 text-sm border border-gray-300">
                                    <button class="text-green-600 hover:text-green-800"
                                        onclick="showEditModal({{ $product->id }}, '{{ $product->name }}', '{{ $product->sku }}', {{ $product->quantity }}, {{ $product->price }})">
                                        ✏️ Edit
                                    </button>

                                    <button class="ml-2 text-red-600 hover:text-red-800"
                                        onclick="deleteProduct({{ $product->id }})">
                                        🗑️ Delete
                                    </button>

                                    <button class="ml-2 text-blue-600 hover:text-blue-800"
                                        onclick="viewHistory({{ $product->id }})">
                                        📜 History
                                    </button>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- add product  Modal -->

    <div id="addProductModal"
        class="fixed inset-0 z-50 flex items-center justify-center hidden bg-gray-900 bg-opacity-50">
        <div class="w-1/3 p-6 bg-white rounded shadow-md">
            <div class="flex justify-between mb-4">
                <h3 class="text-lg font-semibold">Add New Product</h3>
                <button onclick="closeModal()" class="text-gray-600 hover:text-gray-900">✖</button>
            </div>
            <form action="" id="product_data">
                @csrf

                <div class="mb-4">
                    <label class="block font-medium">Product Name:</label>
                    <input type="text" class="w-full p-2 border rounded" id="product_name" name="product_name"
                        placeholder="Enter product name">
                    <div id="product_name_error" class="field_error"></div>
                </div>

                <div class="mb-4">
                    <label class="block font-medium">SKU:</label>
                    <input type="text" class="w-full p-2 border rounded" id="sku" name="sku"
                        placeholder="Enter product SKU">
                    <div id="sku_error" class="field_error"></div>
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Quantity:</label>
                    <input type="number" class="w-full p-2 border rounded" id="quantity" name="quantity"
                        placeholder="Enter quantity" min="1">
                    <div id="quantity_error" class="text-red-500 field_error"></div>
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Price:</label>
                    <input type="number" class="w-full p-2 border rounded" name="price" id="price"
                        placeholder="Enter price" min="1">
                    <div id="price_error" class="text-red-500 field_error"></div>
                </div>


                <button class="px-4 py-2 mt-3 text-white bg-black rounded-md shadow hover:bg-gray-900">
                    Submit
                </button>
            </form>
        </div>
    </div>


    <!-- Edit Modal -->
    <div id="editProductModal"
        class="fixed inset-0 z-50 flex items-center justify-center hidden bg-gray-900 bg-opacity-50">
        <div class="w-1/3 p-6 bg-white rounded shadow-md">
            <div class="flex justify-between mb-4">
                <h3 class="text-lg font-semibold">Edit Product</h3>
                <button onclick="closeEditModal()" class="text-gray-600 hover:text-gray-900">✖</button>
            </div>
            <form action="" id="edit_product_data">
                @csrf
                <input type="hidden" id="edit_product_id">


                <input type="hidden" id="product_id" name="product_id">

                <div class="mb-4">
                    <label class="block font-medium">Product Name:</label>
                    <input type="text" class="w-full p-2 border rounded" name="pro_name" id="pro_name">
                </div>

                <div id="pro_name_error" class="field_error"></div>


                <div class="mb-4">
                    <label class="block font-medium">SKU:</label>
                    <input type="text" class="w-full p-2 border rounded" name="edit_sku" id="edit_sku" readonly>
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Quantity:</label>
                    <input type="number" class="w-full p-2 border rounded" name="edit_quantity" id="edit_quantity"
                        min="0">
                </div>

                <div id="edit_quantity_error" class="field_error"></div>


                <div class="mb-4">
                    <label class="block font-medium">Price:</label>
                    <input type="number" class="w-full p-2 border rounded" name="edit_price" id="edit_price"
                        min="1">
                </div>

                <div id="edit_price_error" class="field_error"></div>


                <button class="px-4 py-2 mt-3 text-white bg-black rounded-md shadow hover:bg-gray-900">
                    Update
                </button>
            </form>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        function showAddModal() {

            //alert("hello");
            $('#addProductModal').removeClass('hidden');
        }

        function closeModal() {
            $('#addProductModal').addClass('hidden');
            $('#product_data')[0].reset();
            $('.field_error').html(''); // Clear validation errors
        }

        function showEditModal(id, name, sku, quantity, price) {

            $('#edit_product_id').val(id);
            $('#product_id').val(id);

            $('#pro_name').val(name);
            $('#edit_sku').val(sku);
            $('#edit_quantity').val(quantity);
            $('#edit_price').val(price);
            $('#editProductModal').removeClass('hidden');
        }

        function closeEditModal() {
            $('#editProductModal').addClass('hidden');
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('js/custom.js') }}"></script>

</x-app-layout>

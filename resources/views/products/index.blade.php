<!DOCTYPE html>
<html>
<head>
    <title>Route Discovery Products</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-950 text-white min-h-screen p-10">

    <div class="max-w-6xl mx-auto">

        <div class="bg-slate-900 p-8 rounded-3xl shadow-2xl border border-slate-800">

            <h1 class="text-4xl font-bold mb-8 text-center">
                🚀 Route Discovery Product Dashboard
            </h1>

            @if(session('success'))
                <div class="bg-green-500/20 border border-green-500 text-green-300 p-4 rounded-xl mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- ADD PRODUCT -->

            <form action="{{ route('products.store') }}" method="POST" class="grid md:grid-cols-3 gap-4 mb-8">
                @csrf

                <input
                    type="text"
                    name="name"
                    placeholder="Product Name"
                    class="bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 outline-none"
                >

                <input
                    type="number"
                    name="price"
                    placeholder="Price"
                    class="bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 outline-none"
                >

                <input
                    type="text"
                    name="description"
                    placeholder="Description"
                    class="bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 outline-none"
                >

                <button class="bg-indigo-600 hover:bg-indigo-700 rounded-xl py-3 font-bold col-span-3">
                    ➕ Add Product
                </button>
            </form>

            <!-- SEARCH -->

            <form method="GET" action="{{ route('products.index') }}" class="mb-6">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="🔍 Search product, price or description..."
                    class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 outline-none"
                >
            </form>

            <!-- TABLE -->

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-slate-800">
                        <tr>
                            <th class="p-4 text-left">ID</th>
                            <th class="p-4 text-left">Product</th>
                            <th class="p-4 text-left">Price</th>
                            <th class="p-4 text-left">Description</th>
                            <th class="p-4 text-left">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($products as $product)

                            <tr class="border-b border-slate-800">

                                <td class="p-4">
                                    {{ $product->id }}
                                </td>

                                <td class="p-4">
                                    {{ $product->name }}
                                </td>

                                <td class="p-4">
                                    ₹{{ number_format($product->price) }}
                                </td>

                                <td class="p-4">
                                    {{ $product->description }}
                                </td>

                                <td class="p-4">

                                    <form action="{{ route('products.delete', $product->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg">
                                            Delete
                                        </button>
                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="text-center p-8 text-slate-400">
                                    No Products Found
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <!-- PAGINATION -->

            <div class="mt-6">
                {{ $products->links() }}
            </div>

        </div>

    </div>

</body>
</html>
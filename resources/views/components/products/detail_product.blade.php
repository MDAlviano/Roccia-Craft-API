@extends('main.main')

@section('content')
<script>
function openEditStockPopup(id, stock, imageUrl) {
    document.getElementById("edit_variant_id").value = id;
    document.getElementById("edit_stock").value = stock;
    document.getElementById("edit_image_url").value = imageUrl;
    document.getElementById("edit_preview_image").innerHTML = `<img src="${imageUrl}" class="h-20 rounded">`;
    document.getElementById("editVariantModal").classList.remove("hidden");
}

function closeEditModal() {
    document.getElementById("editVariantModal").classList.add("hidden");
}

// Upload Image to Cloudinary
document.getElementById('edit_image_upload').addEventListener('change', async function () {
    const file = this.files[0];
    const formData = new FormData();
    formData.append('image', file);

    const res = await fetch("{{ route('image.upload') }}", {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: formData
    });

    const data = await res.json();
    document.getElementById("edit_image_url").value = data.url;
    document.getElementById("edit_preview_image").innerHTML = `<img src="${data.url}" class="h-20 rounded mt-2">`;
});

// Submit update form
document.getElementById("editVariantForm").addEventListener("submit", async function (e) {
    e.preventDefault();

    const id = document.getElementById("edit_variant_id").value;
    const stock = document.getElementById("edit_stock").value;
    const imageUrl = document.getElementById("edit_image_url").value;

    const res = await fetch(`/products/${productId}/variants/update`, {
        method: "PATCH",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "X-HTTP-Method-Override": "PATCH",
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ stock, image_url: imageUrl })
    });

    const data = await res.json();

    if (res.ok) {
        alert(data.message);
        window.location.reload();
    } else {
        alert(data.error || 'Gagal memperbarui variant.');
    }
});
</script>

<div class="container mx-auto px-6 py-10">
    <h1 class="text-center text-4xl font-bold mb-11 text-gray-800">Detail Product</h1>

    <!-- Section: Product Detail -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start mb-10">
        <!-- Product Image -->
        <div class="w-full">
            <img src="{{ $product->productUsageImages->first()->image_url }}" alt="Product Image"
                class="rounded-xl w-full object-cover shadow-md">
        </div>

        <!-- Product Info -->
        <div class="flex flex-col gap-4">
            <h2 class="text-2xl font-bold text-gray-800">Product Name</h2>

            <div class="flex gap-2 flex-wrap">
                <span class="inline-block bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full border border-blue-400">
                    {{ $product->collections->name }}
                </span>
                <span class="inline-block bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full border border-blue-400">
                    {{ $product->types->name }}
                </span>
            </div>

            <p class="text-gray-600 leading-relaxed">
                {!! $product->description !!}
            </p>
        </div>
    </div>

    <!-- Section: Product Variants -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Product Variants</h2>
        <a href="{{ route('create-variant') }}"
           class="inline-block bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-md shadow-md transition-all duration-200">
            + Create New Variant
        </a>
    </div>

    <!-- Variant Card -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($variants as $variant)
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition duration-300 flex flex-col overflow-hidden">
                <div class="relative h-48 bg-gray-100">
                    <img src="{{ $variant->image }}" alt="Product Image"
                        class="absolute inset-0 w-full h-full object-cover">
                </div>

                <div class="p-4 flex-grow">
                    <h3 class="text-xl font-semibold text-gray-800 mb-1">
                        {{ $product->name }} {{ $variant->fabric ? '- ' . $variant->fabric : '' }}
                    </h3>
                    <p class="text-sm text-gray-500">Stock: {{ $variant->stock }}</p>
                </div>

                <div class="p-4 pt-0 flex flex-col gap-2">
                    <!-- Edit Stock -->
                    <form method="GET" onsubmit="openEditStockPopup('{{ $variant->id }}', '{{ $variant->stock }}', '{{ $variant->image }}'); return false;">
                        <button type="submit"
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded-md transition duration-200">
                            Edit Stock
                        </button>
                    </form>
                    <!-- Delete -->
                    <form action="{{ route('variant.delete', $variant->id) }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus variant ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-md transition duration-200">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal Edit Variant -->
    <div id="editVariantModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
        <div class="bg-white p-6 rounded-lg w-96 relative">
            <h2 class="text-xl font-semibold mb-4 text-center">Edit Variant</h2>

            <form id="editVariantForm" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <input type="hidden" id="edit_variant_id">
                <input type="hidden" name="image_url" id="edit_image_url">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Upload Gambar</label>
                    <input type="file" id="edit_image_upload" accept="image/*" class="w-full border p-2 rounded">
                    <div id="edit_preview_image" class="mt-2"></div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Stok</label>
                    <input type="number" name="stock" id="edit_stock" class="w-full border p-2 rounded" required>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeEditModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
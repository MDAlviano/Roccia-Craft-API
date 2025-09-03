@extends('main.main')

@section('content')
<div class="mx-auto mt-10">
    <div class="grid grid-cols-2 gap-2">
        <div id="add-type" class="border-2 border-blue-500 p-8 rounded-lg bg-blue-200 mb-7">
            <h4 class="font-bold text-2xl mb-2">Add New Type</h4>
            <hr>
            <div class="flex justify-between mt-6">
                <h6>Total Current Type: <span class="bg-blue-600 rounded-md px-2 font-semibold text-white">{{ $typeCount }}</span></h6>
                <form action="{{ route('create-type') }}">
                    <button href="" class="bg-blue-600 hover:bg-blue-700 transition duration-300 rounded-md px-7 py-1 text-white font-semibold">Add</button>
                </form>
            </div>
        </div>
        <div id="add-status" class="border-2 border-blue-500 p-8 rounded-lg bg-blue-200 mb-7">
            <h4 class="font-bold text-2xl mb-2">Add New Status</h4>
            <hr>
            <div class="flex justify-between mt-6">
                <h6>Total Current Status: <span class="bg-blue-600 rounded-md px-2 font-semibold text-white">{{ $statusCount }}</span></h6>
                <form action="{{ route('create-status') }}">
                    <button href="" class="bg-blue-600 hover:bg-blue-700 transition duration-300 rounded-md px-7 py-1 text-white font-semibold">Add</button>
                </form>
            </div>
        </div>
    </div>
    
    <div id="add-color" class="w-1/2 border-2 border-blue-500 p-8 rounded-lg bg-blue-200 mb-7">
        <h4 class="font-bold text-2xl mb-2">Add New Color</h4>
        <hr>
        <div class="flex justify-between mt-6">
            <h6>Total Current Color: <span class="bg-blue-600 rounded-md px-2 font-semibold text-white">{{ $colorCount }}</span></h6>
            <form action="{{ route('create-color') }}">
                <button href="" class="bg-blue-600 hover:bg-blue-700 transition duration-300 rounded-md px-7 py-1 text-white font-semibold">Add</button>
            </form>
        </div>
    </div>
</div>
@endsection
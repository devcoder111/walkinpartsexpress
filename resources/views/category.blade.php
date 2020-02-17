@extends('layouts.app')

@section('content')
    <div class="text-2xl md:text-3xl lg:text-4xl text-center mt-12 font-bold">
        <span class="border-b-4 border-blue-dark pb-2">{{ $category->name }}</span>
    </div>
    <!-- Products Row -->
    <div class="flex items-center justify-center text-center mt-0 pb-16 font-extrabold">
        <div class="mx-6 mt-2">
            <div class="w-full ml-auto mr-auto text-center justify-center items-center mt-6 text-lg font-normal h-auto">
                <div class="flex items-stretch flex-wrap mt-4 sm:mx-16 md:mx-8 lg:mx-12 items-center h-auto">
                    @foreach($products as $product)
                        <div class="w-full md:w-1/2 lg:w-1/3 xl:w-1/4 p-8 h-auto">
                            <a href="/product/{{ $product->id }}" class="no-underline text-black hover:text-blue">
                                <div class="rounded overflow-hidden bg-white shadow-md hover:shadow-lg min-h-full">
                                    @if($product->images->count() > 0)
                                        <img class="w-full"
                                             src="{{ env('AWS_S3_BASEPATH')."/" }}{{ $product->images->first()->image_thumbnail->file_path }}">
                                    @else
                                        <img class="w-full"
                                             src="{{ env('AWS_S3_BASEPATH').'/images/no-product-image.jpg' }}">
                                    @endif
                                    <div class="px-3 py-4 h-auto">
                                        <div class="font-bold text-sm mb-2">{{ $product->description }}</div>
                                        <div class="font-bold text-red text-sm mb-2">@convertToCurrency($product->price)</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
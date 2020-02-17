@extends('layouts.app')

@section('content')
    <!-- SLIDER IMAGE ROW -->
    <div class="invisible hidden md:visible md:inline">
        <div class="slider-row flex items-center text-white justify-center">
            <div class="text-center uppercase font-bold">
                <div class="text-5xl mx-4">Walk-In Parts Express</div>
                <div class="text-xl mx-4">Freezers and More</div>
            </div>
        </div>
    </div>

    <!-- ABOUT SECTION ROW -->
    <div class="flex items-center text-blue-dark justify-center text-center mt-10 font-extrabold">
        <div class="mx-6">
            <div class="uppercase text-2xl">About</div>
            <div class="text-5xl">Walk-In Parts Express</div>
            <div class="w-full ml-auto mr-auto text-center justify-center mt-6 text-lg text-blue-darkest font-normal">
                Search and order parts for any brand of walk-in cooler/freezer across many different categories.
            </div>
        </div>
    </div>


    <!-- LATEST PARTS ROW -->
    <div class="bg-grey-lighter flex items-center text-blue-darkest justify-center text-center mt-16 pb-16 font-extrabold">
        <div class="mx-6 mt-10">
            <div class="sm:text-4xl md:text-5xl"><span
                        class="border-b-4 border-blue-dark pb-2">Product Categories</span></div>
            <div class="w-full ml-auto mr-auto text-center justify-center items-center mt-6 text-lg text-blue-darkest font-normal">
                <div class="flex flex-wrap mt-12 sm:mx-16 md:mx-8 lg:mx-12 items-center">
                    @foreach($categories as $category)
                        <div class="w-full md:w-1/2 lg:w-1/3 xl:w-1/4 p-8">
                            <a href="/category/{{ $category->id }}"
                               class="no-underline text-blue hover:text-blue-darker">
                                <div class="rounded overflow-hidden bg-white shadow-md hover:shadow-lg">
                                    @if(!$category->image_id)
                                    <img class="w-full"
                                         src="{{ env('AWS_S3_BASEPATH').'/images/no-product-image.jpg' }}"
                                         alt="{{ $category->name }}">
                                    @else
                                        <img class="w-full"
                                             src="{{ env('AWS_S3_BASEPATH').'/' }}{{ \App\Image::where('id', $category->image_id)->first()->image_thumbnail->file_path }}"
                                             alt="{{ $category->name }}">
                                    @endif
                                    <div class="px-6 py-4">
                                        <div class="font-bold text-xl mb-2">{{ $category->name }}</div>
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
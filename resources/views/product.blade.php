@extends('layouts.app')

@section('content')
    <div class="flex flex-wrap m-12">
        <div class="w-full block md:hidden mb-12 px-8">
            <div class="text-2xl md:text-3xl lg:text-4xl text-blue-darkest font-bold">
                <span class="border-b-4 border-blue-dark pb-2 leading-loose">
                    {{ $product->description }}
                </span>
            </div>
        </div>
        <div class="w-full md:w-1/3 lg:w-2/5">
            @if($product->images->count() > 0)
                <div class="w-full px-2">
                    <img id="main-img" class="w-full border-2 border-grey"
                         src="{{ env('AWS_S3_BASEPATH')."/" }}{{ $product->images->first()->file_path }}">
                </div>
                <div class="flex flex-wrap mt-2">
                    <?php $index = 0; ?>
                    @foreach($product->images as $image)
                        <div class="w-1/3 px-2 images">
                            <img id="thum-img-{{$index}}" onmouseout="changeHoverOutImg();" onmouseover="changeHoverImg('{{env('AWS_S3_BASEPATH')}}{{'/'.$image->file_path}}')" onClick="changeImgFunc('{{env('AWS_S3_BASEPATH')}}{{'/'.$image->file_path}}', {{$index++}})" class="img-hover cursor-pointer mt-6
                                    @if($image->hasHighestWeight())
                                        border-2 border-orange
                                    @else
                                        border-2 border-grey
                                    @endif
                                "
                                 src="{{ env('AWS_S3_BASEPATH')."/" }}{{ $image->image_thumbnail->file_path }}">
                        </div>
                    @endforeach
                </div>
            @else
                <img class="w-full border-2 border-grey"
                     src="{{ env('AWS_S3_BASEPATH').'/images/no-product-image.jpg' }}">
            @endif
        </div>
        <div class="w-full md:w-2/3 lg:w-3/5 px-8 mb-8 md:mb-0">
            <div class="text-2xl md:text-3xl lg:text-4xl text-blue-darkest font-bold">
                <span class="
                border-b-4 border-blue-dark pb-2
                leading-loose hidden md:inline">
                    {{ $product->description }}
                </span>
            </div>
            <div class="text-blue-dark uppercase mt-3 text-xl font-extrabold">
                <span class="leading-loose font-bold">Part Number</span>: <span
                        class="text-blue-darkest">{{ $product->master_part_number }}</span>
            </div>
            <div class="text-blue-dark uppercase mt-3 text-xl font-normal">
                <span class="leading-loose font-bold text-2xl text-red-dark">@convertToCurrency($product->price)</span>
            </div>
            <div class="mt-6">
                <add-to-cart :product-id="{{ $product->id }}" :quantity="1"></add-to-cart>
            </div>
            @if(!empty($product->prop_65_warning))
                <div class="flex items-center bg-blue-light text-white text-xs font-normal px-4 py-3 rounded mt-8"
                     role="alert">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="exclamation-triangle"
                         class="h-8" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                        <path fill="currentColor"
                              d="M569.517 440.013C587.975 472.007 564.806 512 527.94 512H48.054c-36.937 0-59.999-40.055-41.577-71.987L246.423 23.985c18.467-32.009 64.72-31.951 83.154 0l239.94 416.028zM288 354c-25.405 0-46 20.595-46 46s20.595 46 46 46 46-20.595 46-46-20.595-46-46-46zm-43.673-165.346l7.418 136c.347 6.364 5.609 11.346 11.982 11.346h48.546c6.373 0 11.635-4.982 11.982-11.346l7.418-136c.375-6.874-5.098-12.654-11.982-12.654h-63.383c-6.884 0-12.356 5.78-11.981 12.654z"></path>
                    </svg>

                    <p class="ml-4">{{ $product->prop_65_warning }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection
<script type="module">
    import ProductImages from "../js/components/product/ProductImages";

    export default {
        components: {ProductImages}
    }
</script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script type="text/javascript" src="/assets/js/plugin.js"></script>
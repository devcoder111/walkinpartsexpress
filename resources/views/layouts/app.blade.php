<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>window.Laravel = {csrfToken: '{{ csrf_token() }}'};</script>


    <title>Walk-In Parts Express</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{!! asset('css/app.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/tailwind.css') !!}" rel="stylesheet">

</head>
<body>
<!-- Opening div tag for main Vue app div -->
<div id="app">
    <!-- TOP GREY ROW WITH LOGIN/REGISTER LINKS -->
    <div class="flex-row-reverse flex bg-grey-light sm:pr-12 md:pr-32 py-3">
        @if (Route::has('login'))

            <ul class="list-reset flex list-height">
                @auth

                    <li class="mr-4"><a class="text-blue hover:text-blue-darker no-underline uppercase font-semibold text-xs" href="{{ url('/account') }}">My Account</a></li> <li class="mr-4"><a class="text-blue hover:text-blue-darker no-underline font-semibold text-xs uppercase" href="{{ url('/logout') }}">Logout</a>
                </li>
                @else
                    <li class="mr-4"><a class="text-blue hover:text-blue-darker no-underline uppercase font-semibold text-xs" href="{{ route('login') }}">Login</a></li>

                    @if (Route::has('register'))
                            <li class="mr-4"> <a class="text-blue hover:text-blue-darker no-underline uppercase font-semibold text-xs" href="{{ route('register') }}">Register</a>
                        @endif
                </li>
                @endauth
            </ul>
        @endif
        <ul class="social-icons icon-circle icon-rotate list-unstyled list-inline social-header-icons ">
            @foreach($socialMedia as $oneSocialMedia)
                <li><a href="{{$oneSocialMedia->target_url}}" target="_blank"><i class="fa fa-{{$oneSocialMedia->social_name}}"></i></a></li>
            @endforeach
        </ul>
    </div>

    <!-- ROW WITH LOGO, SEARCH BAR, AND CART ICON -->
    <div class="flex-row flex px-10 py-4 my-4 sm:flex-wrap">
        <div class="text-center md:w-2/5 w-full">
            <div class="flex flex-row">
                <div class="w-1/2 md:w-full">
                    <a href="/">
                        <img class="h-auto md:h-auto md:mx-6 md:pr-16 l:h-auto xl:h-auto"
                             src="{{ Storage::disk('s3')->url('images/walk-in-logo.png') }}"/>
                    </a>
                </div>
                <div class="inline md:hidden w-1/2 sm:mr-6">
                    <cart-preview class="w-full sm:justify-end"></cart-preview>
                </div>
            </div>
        </div>
        <div class="w-full md:w-3/5 sm:mt-6 md:mt-0">
            <div class="flex flex-row">
                <div class="w-full flex justify-center md:justify-end">
                    <form class="w-full max-w-sm mt-n2">
                        <div class="flex flex-row rounded sm:justify-start">
                            <input class="bg-grey-lightest border-2 appearance-none rounded-l w-full text-grey-darker leading-tight focus:bg-white focus:shadow-outline h-12 md:text-left"
                                   type="text" placeholder="  SEARCH" aria-label="SEARCH" id="search">
                            <label for="search">
                                <button class="flex-no-shrink bg-blue-dark hover:bg-blue-darker border-blue-dark hover:border-blue-darker text-sm border-4 text-white py-1 mx-0 px-2 h-12 rounded"
                                        type="button">
                                    <svg class="svg-icon" viewBox="0 0 20 20">
                                        <path d="M18.125,15.804l-4.038-4.037c0.675-1.079,1.012-2.308,1.01-3.534C15.089,4.62,12.199,1.75,8.584,1.75C4.815,1.75,1.982,4.726,2,8.286c0.021,3.577,2.908,6.549,6.578,6.549c1.241,0,2.417-0.347,3.44-0.985l4.032,4.026c0.167,0.166,0.43,0.166,0.596,0l1.479-1.478C18.292,16.234,18.292,15.968,18.125,15.804 M8.578,13.99c-3.198,0-5.716-2.593-5.733-5.71c-0.017-3.084,2.438-5.686,5.74-5.686c3.197,0,5.625,2.493,5.64,5.624C14.242,11.548,11.621,13.99,8.578,13.99 M16.349,16.981l-3.637-3.635c0.131-0.11,0.721-0.695,0.876-0.884l3.642,3.639L16.349,16.981z"></path>
                                    </svg>
                                </button>
                            </label>
                        </div>
                    </form>
                </div>
                <div class="hidden md:block md:mx-6 lg:mx-12">
                    <cart-preview class="w-full"></cart-preview>
                </div>
            </div>
        </div>
    </div>


    <!-- MAIN NAV ROW -->
    <div class="flex-row flex sm:flex-wrap bg-blue-dark text-center shadow-lg">
        <div class="text-center w-full px-10 py-4 leading-normal">
            <a href="/" title="Home"
               class="uppercase px-3 no-underline font-extrabold
            {{ Request::path() == '/'
            ? 'text-blue-light'
            : 'text-white' }}">
                Home
            </a>
            @foreach($categories as $category)
                <a href="{{ '/category/'.$category->id }}"
                   class="uppercase px-3 no-underline font-extrabold
                {{ Request::path() == 'category/'.$category->id
                    ? 'text-blue-light'
                    : 'text-white' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </div>

    @yield('content')

    <div class="flex-wrap flex md:flex-row bg-blue-dark shadow-lg border-blue-lighter opacity-100">
        <div class="bg-blue-darkest opacity-50 w-full h-full ">
            <div class="sm:w-full md:w-2/3 mx-auto">
                <div class="flex flex-wrap md:flex-row h-14 mb-10 text-blue-subfooter">
                    <div class="w-full md:w-1/2 text-left md:pl-2 md:pr-16 sm:pr-6 sm:pl-6">
                        <div class="mt-12">
                            <a href="/" title="Home">
                                <img src="{{ Storage::disk('s3')->url('images/walk-in-footer-logo.png') }}" class=""/>
                            </a>
                        </div>
                        <div class="text-3xl font-extrabold md:mr-32 md:pr-5 my-4">
                            About Us
                        </div>
                        <div class="">
                            Search and order parts for any brand of walk-in cooler/freezer across many different
                            categories.
                        </div>
                        <div class="visible md:invisible ">
                            <hr class="border border-white-50% mt-12 mr-6"/>
                        </div>
                    </div>
                    <div class="sm:w-full md:w-1/2 sm:mt-10 text-left font-extrabold pl-6">
                        <div class="text-3xl">
                            Customer Care
                        </div>
                        <div class="text-left mt-2">
                            <ul class="list-reset leading-normal ml-2 md:ml-4">
{{--                                <li class="">--}}
{{--                                    <a href="/contact" class="text-blue-lighter no-underline hover:text-blue-subfooter">--}}
{{--                                        Contact Us--}}
{{--                                    </a>--}}
{{--                                </li>--}}
                                <li>
                                    <a href="/privacy-policy"
                                       class="text-blue-lighter no-underline hover:text-blue-subfooter">
                                        Privacy Policy
                                    </a>
                                </li>
                                <li>
                                    <a href="/returns-refund-cancellation-policy"
                                       class="text-blue-lighter no-underline hover:text-blue-subfooter">
                                        Returns, Refunds, &amp; Cancellations
                                    </a>
                                </li>
                                <li>
                                    <a href="/shipping-policy"
                                       class="text-blue-lighter no-underline hover:text-blue-subfooter">
                                        Shipping Policy
                                    </a>
                                </li>
                                <li>
                                    <a href="/terms-conditions"
                                       class="text-blue-lighter no-underline hover:text-blue-subfooter">
                                        Terms &amp; Conditions
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="footer-social text-center font-extrabold">
                        <h4>Follow us</h4>
                        <div class="wrapper">
                            <ul class="social-icons icon-rounded  list-unstyled list-inline"> 
                                @foreach($socialMedia as $oneSocialMedia)
                                    <li><a href="{{$oneSocialMedia->target_url}}" target="_blank"><i class="fa fa-{{$oneSocialMedia->social_name}}"></i></a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <!-- FINAL FOOTER ROW -->
    <div class="flex-row flex sm:flex-wrap bg-blue-dark opacity-100">
        <div class="text-center mr-auto ml-auto sm:w-full bg-blue-darkest opacity-75">
            <div class="px-10 pt-1 pb-3 my-4 text-white">
                <div class="footer-menu leading-normal">
                    <a href="/" title="Home"
                       class="uppercase px-3 no-underline
                    {{ Request::path() == '/'
                    ? 'text-blue-light font-extrabold'
                    : 'text-white' }}">
                        Home
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ '/category/'.$category->id }}"
                           class="uppercase px-3 no-underline
                        {{ Request::path() == 'category/'.$category->id
                            ? 'text-blue-light font-extrabold'
                            : 'text-white' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
                <div class="copy-txt uppercase mt-6">
                    <p>&copy; Copyright 2018 - {{ now()->year }}, WALK-IN PARTS EXPRESS. All rights reserved.</p>
                    <div class="mt-4">Designed and Developed by:
                        <a href="https://www.millerdavisagency.com/"
                           target="_blank"
                           class="text-blue-light no-underline">
                            Miller Davis Agency
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Closing div tag for main Vue app div -->
</div>

<script type="text/javascript" src="{!! asset('js/app.js') !!}"></script>
</body>
</html>

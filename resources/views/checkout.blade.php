@extends('layouts.app')

@section('content')
    <div>
        <div class="sticky pin-r pin-t pt-4 invisible md:visible" style="margin-left: 66%">
            <order-summary></order-summary>
        </div>
        <div>
            <div class="flex flex-wrap m-12" style="margin-top: -390px;">
                <checkout></checkout>
                <div class="w-full h-100 md:w-1/3 lg:w-2/5">
                    <div class="block md:hidden">
                        <div class="mx-6 border-t border-gray"></div>
                        <order-summary></order-summary>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

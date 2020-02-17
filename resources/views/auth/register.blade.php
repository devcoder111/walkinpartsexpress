@extends('layouts.app')

@section('content')
<div class="mx-auto">
<div class="justify-content-md-center align-items-center pb-16">
        <div class="md:w-1/2 sm:w-full md:mx-auto pl-3 pr-3">
            <div class="w-full">
                
 <div class="text-2xl md:text-3xl lg:text-4xl text-center mt-12 mb-12 font-bold">
            <span class="border-b-4 border-blue-dark pb-2">{{ __('Register') }}</span>
        </div>
                
                    <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block text-grey-darker text-sm font-bold mb-2">{{ __('Name') }}</label>                            
                                <input id="name" type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-grey-darker text-sm font-bold mb-2">{{ __('E-Mail Address') }}</label>

                           
                                <input id="email" type="email" class="shadow appearance-none rounded w-full py-2 px-3 text-grey-darker mb-3 leading-tight focus:outline-none focus:shadow-outline @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-grey-darker text-sm font-bold mb-2">{{ __('Password') }}</label>

                            
                                <input id="password" type="password" class="shadow appearance-none rounded w-full py-2 px-3 text-grey-darker mb-3 leading-tight focus:outline-none focus:shadow-outline  @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            
                        </div>

                        <div class="mb-4">
                            <label for="password-confirm" class="block text-grey-darker text-sm font-bold mb-2">{{ __('Confirm Password') }}</label>

                            
                                <input id="password-confirm" type="password" class="shadow appearance-none rounded w-full py-2 px-3 text-grey-darker mb-3 leading-tight focus:outline-none focus:shadow-outline" name="password_confirmation" required autocomplete="new-password">
                           
                        </div>
                            
                                <button type="submit" class="bg-blue hover:bg-blue-dark text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    {{ __('Register') }}
                                </button>
                            
                      
                    </form>
                
            </div>
        </div>
    </div>
</div>
@endsection

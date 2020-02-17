@extends('layouts.app')

@section('content')
    <div class="mx-auto">
        <div class="justify-content-md-center align-items-center pb-16">
            <div class="md:w-3/5 sm:w-full md:mx-auto pl-3 pr-3">
                <div class="w-full">
                    <div class="text-2xl md:text-3xl lg:text-4xl text-center mt-12 mb-12 font-bold">
                        <span class="border-b-4 border-blue-dark pb-2">{{ __('Login') }}</span>
                    </div>

                    <div class="card-body bg-white shadow-md rounded p-4">
                        <div class="flex flex-wrap md:flex-row items-center">
                            <div class="md:w-1/2 sm:w-full pt-4 pb-4 pl-3 pr-3 border-r">
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <div class="mb-4">
                                        <label for="email" class="block text-grey-darker text-sm font-bold mb-2">{{ __('E-Mail Address') }}</label>
                                        <input id="email" type="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror

                                    </div>

                                    <div class="mb-4">
                                        <label for="password" class="block text-grey-darker text-sm font-bold mb-2">{{ __('Password') }}</label>
                                        <input id="password" type="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror

                                    </div>

                                    <div class="mb-4">

                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>


                                    </div>
                                    <div class="flex items-center justify-between">
                                        <button type="submit" class="bg-blue hover:bg-blue-dark text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                            {{ __('Login') }}
                                        </button>

                                        @if (Route::has('password.request'))
                                            <a class="inline-block align-baseline no-underline font-bold text-sm text-blue hover:text-blue-darker" href="{{ route('password.request') }}">
                                                {{ __('Forgot Your Password?') }}
                                            </a>
                                        @endif
                                    </div>                                   

                                </form>
                            </div>
                            <div class="md:w-1/2 sm:w-full pl-3 pr-3">
                                <div class="rounded border border-grey-light p-4">
                                    <span class="text-base font-bold">Coninue as Guest</span>
                                    <p class="leading-loose mb-2">Please click below to continue as guest.</p>
                                    <a href="/checkout" class="bg-blue no-underline hover:bg-blue-dark text-white font-bold py-2 px-4 rounded">
                                        Check Out As Guest
                                    </a>
                                    <div class="pt-2 pb-3 border-t mt-4">
                                    <p class="pb-2 mb-2">If you don't have account.</p> <a href="/register" class="bg-white no-underline border border-blue-dark hover:bg-blue-dark text-blue hover:text-white font-bold py-2 px-4 rounded">
  Register Now
</a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

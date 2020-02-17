@extends('layouts.app')

@section('content')
    <cart :logged-in="{{ (int)Auth::check() }}"></cart>
@endsection

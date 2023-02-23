@extends('layout')

@section('content')
    <div class="text-black dark:text-white">
        <p>Currency: {{ $currency }}</p>
        <p>Balance: {{ $balance }}</p>
    </div>
@endsection
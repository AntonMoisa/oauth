@extends('layout')

@section('content')
    <div class="text-black dark:text-white">
        <p>Currency: <strong>{{ $currency }}</strong></p>
        <p>Address: <strong>{{ $address }}</strong></p>
        <p>Tag: <strong>{{ $tag ?? 'null' }}</strong></p>
    </div>
@endsection
@extends('layout')

@section('content')
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 dark:text-white d-flex flex-col">
        <h1 class="text-2xl">Oauth demo example</h1>
        <a class="mt-2" href="{{ route('oauth.login') }}">
            <button class="bg-white p-2 rounded-lg text-black w-full mt-2">
                Login via Oauth
            </button>
        </a>
        @if(session()->has('user_id'))
            <a class="mt-2" href="{{ route('user') }}">
                <button class="bg-white p-2 rounded-lg text-black w-full mt-2">
                    User
                </button>
            </a>
            <a class="mt-2" href="{{ route('user.logout') }}">
                <button class="bg-white p-2 rounded-lg text-black w-full mt-2">
                    Logout
                </button>
            </a>
        @endif
        @if(session('message'))
            {{ session('message') }}
        @endif
    </div>
@endsection
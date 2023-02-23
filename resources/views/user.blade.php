@extends('layout')

@section('content')
    <div class="text-black dark:text-white">
        <div>
            <p>ID: <strong>{{ $id }}</strong></p>
            <p>Email: <strong>{{ $email }}</strong></p>
            <p>Hash: <strong>{{ md5($token) }}</strong></p>
        </div>

        <hr>

        <div class="my-2 d-flex">
            <a class="mr-2" href="{{ route('user.address', ['currency' => 'XRP']) }}">
                <button class="p-2 bg-white text-black rounded">
                    Address
                </button>
            </a>
            <a class="mr-2" href="{{ route('user.balance', ['currency' => 'XRP']) }}">
                <button class="p-2 bg-white text-black rounded">
                    Balance
                </button>
            </a>
            <a class="mr-2 place-self-end" href="{{ route('user.logout') }}">
                <button class="p-2 bg-white text-black rounded">
                    Logout
                </button>
            </a>
        </div>

        <hr>

        <div class="mt-4">
            <h2 class="text-2xl mb-4">Transfer <sup>(XRP)</sup></h2>
            <form action="{{ route('user.transfer') }}" method="post">
                @csrf
                <div class="mb-4">
                    <label class="block text-black dark:text-white text-sm font-bold mb-2" for="address">
                        Address
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="address" type="text" name="address"
                        value="{{ old('address', 'r9NEXKYNyfxhzw7VzJp9et8t3onbam98G') }}"
                    >
                    @error('address')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-black dark:text-white text-sm font-bold mb-2" for="tag">
                        Tag
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="tag" type="text" name="tag"
                        value="{{ old('tag', '1652838') }}"
                    >
                    @error('tag')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block dark:text-white text-black text-sm font-bold mb-2" for="amount">
                        Amount
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="amount" type="text" name="amount"
                        value="{{ old('amount', '10') }}"
                    >
                    @error('amount')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="py-2 px-3 w-full bg-white text-black rounded">Submit</button>
            </form>
        </div>
    </div>
@endsection
<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Middleware\UserIdMiddleware;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(UserIdMiddleware::class);
    }

    public function index(Request $request): View
    {
        return view('user', [
            'id'    => $request->session()->get('user_id'),
            'email' => $request->session()->get('user_email'),
            'token' => $request->session()->get('user_token'),
        ]);
    }

    public function address(Request $request, string $currency): View|RedirectResponse
    {
        $response = Http::withToken($request->session()->get('user_token'))
                        ->get(config('services.oauth.api_url') . '/account/addresses/' . $currency);

        if ($response->failed()) {
            $message = 'failed to get user address ' . $currency;

            logger()->info($message, (array)$response->json());

            return redirect()->route('index')->with('message', $message);
        }

        $data = $response->json('data');

        return view('user-address', [
            'currency' => $data['currency'],
            'address'  => $data['address'],
            'tag'      => $data['tag'],
        ]);
    }

    public function balance(Request $request, string $currency): View|RedirectResponse
    {
        $response = Http::withToken($request->session()->get('user_token'))
                        ->get(config('services.oauth.api_url') . '/account/balances/' . $currency);

        if ($response->failed()) {
            $message = 'failed to get user balance ' . $currency;

            logger()->info($message, (array)$response->json());

            return redirect()->route('index')->with('message', $message);
        }

        $data = $response->json('data');

        return view('user-balances', [
            'currency' => $data['currency'],
            'balance'  => $data['balance'],
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function transfer(Request $request): RedirectResponse
    {
        $request->validate([
            'address' => 'required',
            'tag'     => 'required',
            'amount'  => 'required|numeric',
        ]);

        $response = Http::withToken($request->session()->get('user_token'))
                        ->post(config('services.oauth.api_url') . '/account/transfer/internal', [
                            'currency' => 'XRP',
                            'address'  => $request->get('address'),
                            'tag'      => $request->get('tag'),
                            'amount'   => $request->get('amount'),
                        ]);

        if ($response->status() === 422) {
            throw ValidationException::withMessages($response->json('errors'));
        }

        if ($response->failed()) {
            $message = 'failed to create transfer';

            logger()->info($message, (array)$response->json());

            return redirect()->route('index')->with('message', $message);
        }

        $data = $response->json('data');

        return redirect()->route('index')->with('message', $data['id'] . ' payment request created');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('user_id');
        $request->session()->forget('user_email');
        $request->session()->forget('user_token');

        return redirect()->route('index')->with('message', 'Logout');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Rfc4122\UuidV4;

class GatewayController extends Controller
{
    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'country_code' => 'required|string',
            'phone_number' => 'required|string',
        ]);

        $dial = $this->getCountryDial($request->country_code);

        $payload = [
            'amount' => $request->amount,
            'clientPhoneNumber' => $dial . '' . $request->phone_number,
            'callbackUrl' => route('deposit.callback')
        ];

        $headers = [
            'x-merchant-id' => env('SHWARY_MERCHANT_ID'),
            'x-merchant-key' => env('SHWARY_API_KEY'),
        ];

        $endpoint = env('SHWARY_API_URL') . "/merchants/payment/" . $request->country_code;

        try {
            $response = Http::withHeaders($headers)->post($endpoint, $payload);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['status']) && $data['status'] === 'completed') {
                    return redirect()->route('deposit.success', ['id' => $data['id']]);
                }
                if (isset($data['status']) && $data['status'] === 'failed') {
                    return redirect()->route('home')->with('error', 'Le paiement a échoué. Veuillez réessayer.');
                }
                if (isset($data['status']) && $data['status'] === 'pending') {
                    return redirect()->route('deposit.pending', ['id' => $data['id']]);
                }

                return redirect()->back()->with('error', 'Le status de votre paiement est incconu');
            }

            if ($response->status() == 401) {
                Log::info('error 401', [$response]);
                return redirect()->back()->withError('Clé marchande invalide. Veuillez contacter le support.');
            }
            Log::info('error on init', [$response]);

            return redirect()->back()->withError('Erreur lors de la création du paiement. Veuillez réessayer.');
        } catch (\Throwable $th) {
            Log::error('error on init', [$th]);
            return redirect()->back()->withError('Erreur lors de la création du paiement. Veuillez réessayer.');
        }
    }

    public function getCountryDial($country)
    {
        switch ($country) {
            case 'DRC':
                return '+243';
                break;
            case 'KE':
                return '+254';
                break;
            case 'DRC':
                return '+256';
                break;
            default:
                return '+243';
        }
    }

    public function callbackHandler(Request $request)
    {
        Log::info('callback received', [$request->all()]);
        $data = $request->all();

        if (isset($data['status']) && $data['status'] === 'completed') {
            // DB query to update payment status can be placed here
            return redirect()->route('deposit.success', ['id' => $data['id']]);
        }
        if (isset($data['status']) && $data['status'] === 'failed') {
            return redirect()->route('home')->with('error', 'Le paiement a échoué. Veuillez réessayer.');
        }
    }
}

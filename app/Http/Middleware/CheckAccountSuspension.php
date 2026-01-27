<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountSuspension
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $account = $user->account;

            if ($account) {
                // Check if account should be suspended based on last 3 pending deposits
                $account->suspendIfLastThreeDepositsPending();

                // Block access if account is suspended
                if ($account->status === 'suspended') {
                    $suspensionMessage = 'Votre compte a été suspendu car vos 3 dernières transactions sont en attente. Veuillez contacter le support pour être réactivé.';

                    return redirect()->route('dashboard')->with('error', $suspensionMessage);
                }
            }
        }

        return $next($request);
    }
}

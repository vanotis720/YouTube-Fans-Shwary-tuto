@extends('layout')
@section('content')
    <div class="relative min-h-screen overflow-hidden">
        <div class="absolute inset-0 opacity-30">
            <div class="h-full w-full bg-[radial-gradient(circle_at_center,_rgba(255,255,255,0.06),_transparent_60%)]">
            </div>
        </div>
        <main class="relative mx-auto flex min-h-screen max-w-5xl flex-col justify-center px-6 py-16">
            <section id="success-panel"
                class="space-y-10 rounded-[32px] border border-white/10 bg-white/5 p-10 shadow-2xl shadow-indigo-900/40 backdrop-blur">
                <header class="space-y-4 text-center">
                    <div class="flex justify-center mb-6">
                        <div
                            class="inline-flex items-center justify-center h-20 w-20 rounded-full bg-gradient-to-br from-emerald-400 to-amber-300 shadow-xl shadow-emerald-300/40">
                            <svg class="w-10 h-10 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs uppercase tracking-[0.4em] text-amber-200">Merci pour votre soutien</p>
                    <h1 class="title-font text-4xl text-white sm:text-5xl">Votre cadeau a été reçu !</h1>
                    <p class="max-w-3xl text-sm text-slate-200 mx-auto" id="success-status">Votre contribution a été
                        traitée avec succès. Vous allez recevoir un message de confirmation et vos avantages exclusifs
                        seront bientôt débloqués.</p>
                </header>

                <div class="rounded-3xl border border-white/10 bg-white/5 p-6 text-sm text-slate-200">
                    <p class="text-xs uppercase tracking-[0.2em] text-amber-100">Récapitulatif de votre contribution</p>
                    <div class="mt-6 space-y-4">
                        <div class="flex justify-between items-center pb-4 border-b border-white/10">
                            <span class="text-slate-300">Montant du cadeau</span>
                            <span class="text-lg font-semibold text-amber-300" id="success-amount">—</span>
                        </div>
                        <div class="flex justify-between items-center pb-4 border-b border-white/10">
                            <span class="text-slate-300">Votre nom</span>
                            <span class="text-slate-100" id="success-name">—</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-300">Numéro de transaction</span>
                            <span class="text-slate-100 text-xs font-mono" id="success-transaction">—</span>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-amber-200/30 bg-amber-100/5 p-6 text-sm">
                    <p class="text-xs uppercase tracking-[0.2em] text-amber-100 mb-3">Prochaines étapes</p>
                    <ul class="space-y-3 text-slate-200">
                        <li class="flex items-start gap-3">
                            <span
                                class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-amber-400/20 text-xs text-amber-300">✓</span>
                            <span>Un email de confirmation a été envoyé à votre adresse</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span
                                class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-amber-400/20 text-xs text-amber-300">✓</span>
                            <span>Vos avantages exclusifs seront activés dans les 24 heures</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span
                                class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-amber-400/20 text-xs text-amber-300">✓</span>
                            <span>Vous recevrez un message de remerciement personnel du créateur</span>
                        </li>
                    </ul>
                </div>

                <div class="flex flex-col gap-4 sm:flex-row">
                    <a href="/"
                        class="flex-1 rounded-full bg-gradient-to-r from-amber-200 to-rose-200 px-6 py-4 text-sm font-semibold uppercase tracking-wide text-slate-900 shadow-lg shadow-amber-200/40 transition hover:translate-y-0.5 text-center">Retourner
                        au studio</a>
                    <button id="share-button"
                        class="flex-1 rounded-full border border-white/30 px-6 py-4 text-sm font-semibold uppercase tracking-wide text-white transition hover:border-amber-200 hover:text-amber-50">Partager
                        ma contribution</button>
                </div>

                <div class="text-center text-xs text-slate-400">
                    Des questions ? Contactez vanotis720@gmail.com pour plus d'informations sur votre contribution.
                </div>
            </section>
        </main>
    </div>

    <script>
        // Get donation details from URL parameters or localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const params = new URLSearchParams(window.location.search);
            const amount = params.get('amount') || localStorage.getItem('giftAmount') || 'Non spécifié';
            const name = params.get('name') || localStorage.getItem('giftName') || 'Ami du studio';
            const transaction = params.get('transaction') || localStorage.getItem('transactionId') || 'TXN-' +
                Date.now();

            document.getElementById('success-amount').textContent = amount + ' CDF';
            document.getElementById('success-name').textContent = name;
            document.getElementById('success-transaction').textContent = transaction;

            // Clear localStorage
            localStorage.removeItem('giftAmount');
            localStorage.removeItem('giftName');
            localStorage.removeItem('transactionId');
        });

        // Share functionality
        document.getElementById('share-button').addEventListener('click', function() {
            const text =
                `J'ai soutenu le créateur de contenu Vander Otis en envoyant un cadeau ! Rejoignez-moi pour aider à créer du contenu plus audacieux. 🎬✨`;
            const url = window.location.href;

            if (navigator.share) {
                navigator.share({
                    title: 'Offrez un Sourire',
                    text: text,
                    url: url
                }).catch(err => console.log('Share cancelled'));
            } else {
                // Fallback: copy to clipboard
                const shareText = `${text}\n${url}`;
                navigator.clipboard.writeText(shareText).then(() => {
                    alert('Le lien a été copié ! Partagez-le avec vos amis.');
                });
            }
        });
    </script>
@endsection

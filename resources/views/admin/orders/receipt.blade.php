<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu #{{ $order->id }} - {{ setting()->site_name ?? 'Parfum' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { dark: '#0f0f0f', card: '#1a1a1a', border: '#2a2a2a' } } } }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: white; color: #111; font-family: 'Inter', 'Segoe UI', Arial, sans-serif; font-weight: 600; }
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
            .print-page { page-break-after: always; }
        }
    </style>
</head>
<body>
    <div class="max-w-2xl mx-auto p-8 print-page">
        <button onclick="window.print()" class="no-print mb-6 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-lg font-bold flex items-center gap-3 mx-auto">
            <i class="fas fa-print"></i> Imprimer le reçu
        </button>

        <div class="border-2 border-gray-300 p-8 rounded-lg">
            <!-- Header -->
            <div class="text-center border-b-2 border-gray-300 pb-6 mb-6">
                @if(setting()->logo)
                    <img src="{{ asset('storage/' . setting()->logo) }}" class="h-16 mx-auto mb-3">
                @endif
                <h1 class="text-3xl font-bold uppercase tracking-widest">{{ setting()->site_name ?? 'Parfumerie' }}</h1>
                <p class="text-sm text-gray-600 mt-1">{{ setting()->address ?? '' }}</p>
                <p class="text-sm text-gray-600">{{ setting()->email ?? '' }} {{ setting()->phone ? '| ' . setting()->phone : '' }}</p>
            </div>

            <h2 class="text-2xl font-bold text-center mb-6">REÇU DE COMMANDE</h2>

            <!-- Order Info -->
            <div class="grid grid-cols-2 gap-4 text-sm mb-6">
                <div>
                    <p><span class="font-semibold">N° Commande :</span> #{{ $order->id }}</p>
                    <p><span class="font-semibold">Code de suivi :</span> {{ $order->tracking_code }}</p>
                    <p><span class="font-semibold">Date :</span> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p><span class="font-semibold">Statut :</span>
                        @php
                            $statusLabels = ['pending' => 'En attente', 'confirmed' => 'Confirmée', 'shipped' => 'Expédiée', 'delivered' => 'Livrée', 'cancelled' => 'Annulée'];
                        @endphp
                        {{ $statusLabels[$order->status] ?? $order->status }}
                    </p>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="border-t-2 border-gray-300 pt-4 mb-6">
                <h3 class="font-bold text-lg mb-2">Client</h3>
                <p class="text-sm">{{ $order->guest_name }}</p>
                <p class="text-sm">{{ $order->guest_email }}</p>
                <p class="text-sm">{{ $order->guest_phone }}</p>
                <p class="text-sm whitespace-pre-line">{{ $order->guest_address }}</p>
            </div>

            <!-- Items -->
            <table class="w-full text-sm border-t-2 border-gray-300">
                <thead>
                    <tr class="border-b border-gray-300">
                        <th class="py-2 text-left font-bold uppercase">Article</th>
                        <th class="py-2 text-right font-bold uppercase">Prix U.</th>
                        <th class="py-2 text-right font-bold uppercase">Qté</th>
                        <th class="py-2 text-right font-bold uppercase">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr class="border-b border-gray-200">
                        <td class="py-3">{{ $item->product ? $item->product->name : ($item->pack ? $item->pack->name : 'Article') }}</td>
                        <td class="py-3 text-right">{{ number_format($item->price, 2) }} MAD</td>
                        <td class="py-3 text-right">{{ $item->quantity }}</td>
                        <td class="py-3 text-right font-bold">{{ number_format($item->subtotal, 2) }} MAD</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Total -->
            <div class="flex justify-end mt-4 pt-4 border-t-2 border-gray-300">
                <div class="text-right">
                    <p class="text-2xl font-bold">Total : {{ number_format($order->total, 2) }} MAD</p>
                </div>
            </div>

            <!-- Notes -->
            @if($order->notes)
            <div class="mt-6 pt-4 border-t border-gray-200">
                <p class="font-semibold text-sm">Notes :</p>
                <p class="text-sm text-gray-600">{{ $order->notes }}</p>
            </div>
            @endif

            <!-- Footer -->
            <div class="mt-8 pt-6 border-t-2 border-gray-300 text-center text-xs text-gray-500">
                <p>Merci de votre confiance !</p>
                <p class="mt-1">{{ setting()->site_name ?? 'Parfumerie' }} - {{ setting()->email ?? '' }} - {{ setting()->phone ?? '' }}</p>
            </div>
        </div>

        <button onclick="window.print()" class="no-print mt-6 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-lg font-bold flex items-center gap-3 mx-auto">
            <i class="fas fa-print"></i> Imprimer le reçu
        </button>
    </div>
</body>
</html>

@extends('layouts.admin')
@section('header', 'Commande #' . $order->id)
@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-card rounded-lg border border-border shadow-sm p-6">
                <h3 class="text-lg font-bold mb-4">Articles Commandés</h3>
                <table class="min-w-full divide-y divide-border">
                    <thead>
                        <tr>
                            <th class="py-2 text-left text-xs font-medium text-gray-400 uppercase">Article</th>
                            <th class="py-2 text-left text-xs font-medium text-gray-400 uppercase">Prix U.</th>
                            <th class="py-2 text-left text-xs font-medium text-gray-400 uppercase">Qté</th>
                            <th class="py-2 text-left text-xs font-medium text-gray-400 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach($order->items as $item)
                            <tr>
                                <td class="py-3 text-sm text-gray-300">
                                    {{ $item->product ? $item->product->name : ($item->pack ? $item->pack->name : 'Item') }}
                                </td>
                                <td class="py-3 text-sm text-gray-300">{{ $item->price }} MAD</td>
                                <td class="py-3 text-sm text-gray-300">{{ $item->quantity }}</td>
                                <td class="py-3 text-sm font-bold text-white">{{ $item->subtotal }} MAD</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4 pt-4 border-t border-border flex justify-end">
                    <span class="text-xl font-bold text-white">Total: {{ $order->total }} MAD</span>
                </div>
            </div>

            <!-- Manual Return Panel (admin only, shown when no retour exists yet) -->
            @if(!$order->retour)
                <div class="bg-card rounded-lg border border-orange-900 shadow-sm p-6">
                    <h3 class="text-lg font-bold mb-2 text-orange-400 flex items-center gap-2">
                        <i class="fas fa-truck-loading"></i>
                        Retour Manuel (Livreur)
                    </h3>
                    <p class="text-sm text-gray-400 mb-4">
                        Le client a refusé la livraison ou a rendu le colis au livreur sans passer par le site ?<br>
                        Enregistrez le retour ici pour <strong class="text-white">restaurer le stock automatiquement</strong> et
                        annuler la commande.
                    </p>
                    <form action="{{ route('admin.orders.manual-return', $order) }}" method="POST"
                        onsubmit="return confirm('Confirmer le retour manuel ? Le stock sera restauré et la commande annulée.')">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm text-gray-400 mb-1">Raison du retour</label>
                            <input type="text" name="reason"
                                placeholder="Ex: Client a refusé à la porte, colis retourné par livreur..."
                                class="w-full bg-dark border border-border rounded px-4 py-2 text-white focus:outline-none focus:border-orange-600"
                                required>
                        </div>
                        <button type="submit"
                            class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded transition-colors">
                            <i class="fas fa-undo mr-2"></i>Enregistrer le retour & Restaurer le stock
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-card rounded-lg border border-border shadow-sm p-6">
                    <h3 class="text-lg font-bold mb-3 flex items-center gap-2">
                        <i class="fas fa-undo text-gray-400"></i> Retour enregistré
                    </h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500 mb-1">Raison</p>
                            <p class="text-gray-300">{{ $order->retour->reason }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 mb-1">Statut</p>
                            <span
                                class="px-3 py-1 rounded-full text-xs font-bold uppercase
                                {{ $order->retour->status === 'accepted' ? 'bg-green-900/50 text-green-400' :
                ($order->retour->status === 'rejected' ? 'bg-red-900/50 text-red-400' : 'bg-yellow-900/50 text-yellow-400') }}">
                                {{ $order->retour->status }}
                            </span>
                        </div>
                        <div>
                            <p class="text-gray-500 mb-1">Date de la demande</p>
                            <p class="text-gray-300">{{ $order->retour->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right sidebar -->
        <div class="space-y-6">
            <!-- Client Info -->
            <div class="bg-card rounded-lg border border-border shadow-sm p-6">
                <h3 class="text-lg font-bold mb-4">Client</h3>
                <p class="text-sm text-gray-400 mb-1">Nom</p>
                <p class="text-white mb-3">{{ $order->guest_name }}</p>
                <p class="text-sm text-gray-400 mb-1">Email</p>
                <p class="text-white mb-3">{{ $order->guest_email }}</p>
                <p class="text-sm text-gray-400 mb-1">Téléphone</p>
                <p class="text-white mb-3">{{ $order->guest_phone }}</p>
                <p class="text-sm text-gray-400 mb-1">Adresse</p>
                <p class="text-white">{{ $order->guest_address }}</p>
            </div>

            <!-- Receipt -->
            <a href="{{ route('admin.orders.receipt', $order) }}" target="_blank"
               class="block bg-card rounded-lg border border-border shadow-sm p-6 hover:bg-gray-800 transition-colors">
                <div class="flex items-center gap-4">
                    <i class="fas fa-receipt text-2xl text-gray-400"></i>
                    <div>
                        <h3 class="font-bold">Imprimer le reçu</h3>
                        <p class="text-sm text-gray-500">Voir / imprimer le reçu de cette commande</p>
                    </div>
                    <i class="fas fa-external-link-alt ml-auto text-gray-500"></i>
                </div>
            </a>

            <!-- Status Update -->
            <div class="bg-card rounded-lg border border-border shadow-sm p-6">
                <h3 class="text-lg font-bold mb-4">Mettre à jour le statut</h3>
                <div class="mb-3">
                    <span class="text-sm text-gray-400">Statut actuel : </span>
                    @php
                        $statusColors = [
                            'pending' => 'text-yellow-400',
                            'confirmed' => 'text-blue-400',
                            'delivered' => 'text-green-400',
                            'cancelled' => 'text-red-400',
                        ];
                        $statusLabels = ['pending' => 'En attente', 'confirmed' => 'Confirmée', 'delivered' => 'Livrée', 'cancelled' => 'Annulée'];
                    @endphp
                    <span class="font-bold {{ $statusColors[$order->status] ?? 'text-gray-400' }}">
                        {{ $statusLabels[$order->status] ?? $order->status }}
                    </span>
                </div>
                <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                    @csrf
                    <select name="status" class="w-full bg-dark border border-border rounded px-4 py-2 text-white mb-4">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmée</option>
                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Livrée</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                    </select>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded">Mettre à
                        jour</button>
                </form>
            </div>
        </div>
    </div>
@endsection
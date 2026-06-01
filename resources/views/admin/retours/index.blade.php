@extends('layouts.admin')
@section('header', 'Retours')
@section('content')
<div class="bg-card rounded-lg border border-border shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-border">
        <thead class="bg-dark">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Commande</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Client</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Raison</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Statut</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-border">
            @foreach($retours as $retour)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">#{{ $retour->order->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $retour->user->name ?? $retour->order->customer_name }}</td>
                <td class="px-6 py-4 text-sm text-gray-300">{{ Str::limit($retour->reason, 50) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <form action="{{ route('admin.retours.status', $retour) }}" method="POST">
                        @csrf
                        <select name="status" onchange="this.form.submit()" class="bg-dark border border-border rounded px-2 py-1 text-white text-xs">
                            <option value="pending" {{ $retour->status == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="accepted" {{ $retour->status == 'accepted' ? 'selected' : '' }}>Accepté (Restituer Stock)</option>
                            <option value="rejected" {{ $retour->status == 'rejected' ? 'selected' : '' }}>Rejeté</option>
                        </select>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-4">{{ $retours->links() }}</div>
</div>
@endsection

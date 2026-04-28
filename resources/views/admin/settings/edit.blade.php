@extends('layouts.admin')
@section('header', 'Paramètres')
@section('content')
<div class="bg-card rounded-lg border border-border shadow-sm p-6 max-w-2xl">

    @if(session('success'))
        <div class="bg-green-900/50 border border-green-800 text-green-400 p-4 rounded-lg mb-6 flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Logo Section -->
        <div class="pb-6 border-b border-border">
            <label class="block text-sm font-medium text-gray-400 mb-3">Logo du site</label>
            <div class="flex items-start gap-6">
                <!-- Current Logo Preview -->
                <div class="flex-shrink-0">
                    @if($setting->logo)
                        <div class="relative group">
                            <img src="{{ asset('storage/' . $setting->logo) }}"
                                 id="logo-preview"
                                 class="h-20 w-auto max-w-[160px] object-contain rounded-lg border border-border bg-dark p-2">
                        </div>
                        <p class="text-xs text-gray-600 mt-1 text-center">Logo actuel</p>
                    @else
                        <div class="h-20 w-20 rounded-lg border border-border bg-dark flex items-center justify-center" id="logo-placeholder">
                            <i class="fas fa-image text-3xl text-gray-700"></i>
                        </div>
                        <img id="logo-preview" class="h-20 w-auto max-w-[160px] object-contain rounded-lg border border-border bg-dark p-2 hidden">
                    @endif
                </div>

                <!-- Upload Control -->
                <div class="flex-1">
                    <label for="logo-input"
                           class="cursor-pointer flex flex-col items-center justify-center border-2 border-dashed border-border hover:border-gray-500 rounded-xl p-6 transition-colors text-center">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-600 mb-2"></i>
                        <span class="text-sm text-gray-400">Cliquez pour choisir un logo</span>
                        <span class="text-xs text-gray-600 mt-1">PNG, JPG, WEBP · max 2MB · fond transparent recommandé</span>
                    </label>
                    <input type="file" name="logo" id="logo-input" accept="image/*"
                           class="hidden"
                           onchange="previewLogo(this)">
                    <p id="logo-filename" class="text-xs text-gray-500 mt-2 text-center hidden"></p>
                </div>
            </div>

            @if($setting->logo)
            <div class="mt-3 flex items-center gap-2">
                <input type="checkbox" name="remove_logo" id="remove_logo" value="1"
                       class="rounded accent-red-500">
                <label for="remove_logo" class="text-sm text-red-400 cursor-pointer">
                    Supprimer le logo actuel
                </label>
            </div>
            @endif
        </div>

        <!-- Site Name -->
        <div>
            <label class="block text-sm font-medium text-gray-400 mb-1">Nom du site</label>
            <input type="text" name="site_name" value="{{ old('site_name', $setting->site_name ?? 'Parfum Store') }}"
                   class="w-full bg-dark border border-border rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-gray-500" required>
        </div>

        <!-- Email -->
        <div>
            <label class="block text-sm font-medium text-gray-400 mb-1">Email de contact</label>
            <div class="relative">
                <i class="fas fa-envelope absolute left-3 top-3 text-gray-600 text-sm"></i>
                <input type="email" name="email" value="{{ old('email', $setting->email ?? '') }}"
                       class="w-full bg-dark border border-border rounded-lg pl-10 pr-4 py-2.5 text-white focus:outline-none focus:border-gray-500"
                       placeholder="contact@exemple.com">
            </div>
        </div>

        <!-- Phone -->
        <div>
            <label class="block text-sm font-medium text-gray-400 mb-1">Téléphone</label>
            <div class="relative">
                <i class="fas fa-phone absolute left-3 top-3 text-gray-600 text-sm"></i>
                <input type="text" name="phone" value="{{ old('phone', $setting->phone ?? '') }}"
                       class="w-full bg-dark border border-border rounded-lg pl-10 pr-4 py-2.5 text-white focus:outline-none focus:border-gray-500"
                       placeholder="+212 6 00 00 00 00">
            </div>
        </div>

        <!-- Address -->
        <div>
            <label class="block text-sm font-medium text-gray-400 mb-1">Adresse</label>
            <textarea name="address" rows="3"
                      class="w-full bg-dark border border-border rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-gray-500"
                      placeholder="123 Rue Mohammed V, Casablanca">{{ old('address', $setting->address ?? '') }}</textarea>
        </div>

        <div class="pt-2">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-8 rounded-lg transition-colors">
                <i class="fas fa-save mr-2"></i>Enregistrer les paramètres
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function previewLogo(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('logo-preview');
                const placeholder = document.getElementById('logo-placeholder');
                const filename = document.getElementById('logo-filename');

                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (placeholder) placeholder.classList.add('hidden');

                filename.textContent = input.files[0].name;
                filename.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush

<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Chart sederhana --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <h2 class="text-lg font-bold mb-4">Barang per Kategori</h2>
            {{-- Kamu bisa pasang Livewire widget chart di sini --}}
            <livewire:filament.widgets.barang-category-chart />
        </div>
    </div>
</x-filament-panels::page>

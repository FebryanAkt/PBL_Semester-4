<?php

namespace App\Livewire\Filament\Widgets;

use Livewire\Component;

class BarangCategoryChart extends Component
{
    public function render()
    {
        return view('livewire.filament.widgets.barang-category-chart');
    }
}

<div>
    {{-- Contoh chart pakai ApexCharts --}}
    <div id="barangChart"></div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var options = {
                chart: { type: 'bar' },
                series: [{
                    name: 'Jumlah Barang',
                    data: [10, 20, 30] // ganti dengan data dari backend
                }],
                xaxis: {
                    categories: ['Kategori A', 'Kategori B', 'Kategori C']
                }
            };
            var chart = new ApexCharts(document.querySelector("#barangChart"), options);
            chart.render();
        });
    </script>
</div>
</div>

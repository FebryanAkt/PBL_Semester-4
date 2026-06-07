<x-filament-panels::page>
    <style>
        .bekaswit-analytics-grid {
            display: grid;
            grid-template-columns: minmax(0, 0.9fr) minmax(0, 1.45fr);
            gap: 1.5rem;
        }

        .bekaswit-analytics-card {
            overflow: hidden;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            background: #ffffff;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.04);
        }

        .bekaswit-analytics-card__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .bekaswit-analytics-card__title {
            margin: 0;
            color: #0f172a;
            font-size: 1rem;
            font-weight: 700;
        }

        .bekaswit-analytics-card__subtitle {
            margin: 0.25rem 0 0;
            color: #64748b;
            font-size: 0.8rem;
        }

        .bekaswit-analytics-card__body {
            padding: 1.5rem;
        }

        .bekaswit-status-list {
            display: grid;
            gap: 1.35rem;
        }

        .bekaswit-status-row__meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 0.55rem;
            color: #334155;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .bekaswit-status-row__value {
            color: #0f172a;
            font-weight: 800;
        }

        .bekaswit-status-row__track {
            height: 0.55rem;
            overflow: hidden;
            border-radius: 999px;
            background: #eef2f7;
        }

        .bekaswit-status-row__bar {
            height: 100%;
            min-width: 0.35rem;
            border-radius: inherit;
        }

        .bekaswit-table-wrap {
            overflow-x: auto;
        }

        .bekaswit-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .bekaswit-table th {
            padding: 0 1rem 0.75rem;
            color: #94a3b8;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .bekaswit-table td {
            padding: 0.9rem 1rem;
            border-top: 1px solid #f1f5f9;
            color: #475569;
            font-size: 0.85rem;
            vertical-align: middle;
        }

        .bekaswit-table__primary {
            display: block;
            color: #0f172a;
            font-weight: 700;
        }

        .bekaswit-table__secondary {
            display: block;
            margin-top: 0.15rem;
            color: #94a3b8;
            font-size: 0.75rem;
        }

        .bekaswit-badge {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 0.3rem 0.65rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: capitalize;
        }

        .bekaswit-badge--success {
            color: #166534;
            background: #dcfce7;
        }

        .bekaswit-badge--pending {
            color: #92400e;
            background: #fef3c7;
        }

        .bekaswit-badge--cancel {
            color: #991b1b;
            background: #fee2e2;
        }

        .bekaswit-card-link {
            color: #2e7d32;
            font-size: 0.8rem;
            font-weight: 700;
            text-decoration: none;
        }

        .bekaswit-empty {
            padding: 2rem 1rem;
            color: #94a3b8;
            text-align: center;
            font-size: 0.875rem;
        }

        .dark .bekaswit-analytics-card {
            border-color: #334155;
            background: #111827;
        }

        .dark .bekaswit-analytics-card__header,
        .dark .bekaswit-table td {
            border-color: #334155;
        }

        .dark .bekaswit-analytics-card__title,
        .dark .bekaswit-status-row__value,
        .dark .bekaswit-table__primary {
            color: #f8fafc;
        }

        .dark .bekaswit-status-row__meta,
        .dark .bekaswit-table td {
            color: #cbd5e1;
        }

        .dark .bekaswit-status-row__track {
            background: #334155;
        }

        @media (max-width: 1023px) {
            .bekaswit-analytics-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="bekaswit-analytics-grid">
        <section class="bekaswit-analytics-card">
            <div class="bekaswit-analytics-card__header">
                <div>
                    <h2 class="bekaswit-analytics-card__title">Status Inventaris</h2>
                    <p class="bekaswit-analytics-card__subtitle">Ringkasan posisi seluruh barang</p>
                </div>
            </div>

            <div class="bekaswit-analytics-card__body">
                <div class="bekaswit-status-list">
                    @foreach($itemStatuses as $status)
                        <div class="bekaswit-status-row">
                            <div class="bekaswit-status-row__meta">
                                <span>{{ $status['label'] }}</span>
                                <span class="bekaswit-status-row__value">{{ number_format($status['value'], 0, ',', '.') }}</span>
                            </div>
                            <div class="bekaswit-status-row__track">
                                <div
                                    class="bekaswit-status-row__bar"
                                    style="width: {{ $status['percentage'] }}%; background-color: {{ $status['color'] }};">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="bekaswit-analytics-card">
            <div class="bekaswit-analytics-card__header">
                <div>
                    <h2 class="bekaswit-analytics-card__title">Transaksi Terbaru</h2>
                    <p class="bekaswit-analytics-card__subtitle">Aktivitas pembayaran paling baru</p>
                </div>
                <a href="{{ $transactionsUrl }}" class="bekaswit-card-link">Lihat semua</a>
            </div>

            <div class="bekaswit-table-wrap">
                @if($recentTransactions->isEmpty())
                    <div class="bekaswit-empty">Belum ada transaksi yang tercatat.</div>
                @else
                    <table class="bekaswit-table">
                        <thead>
                            <tr>
                                <th>Transaksi</th>
                                <th>Pembeli</th>
                                <th>Nilai</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentTransactions as $transaction)
                                <tr>
                                    <td>
                                        <span class="bekaswit-table__primary">
                                            {{ $transaction->item?->name ?? 'Barang tidak tersedia' }}
                                        </span>
                                        <span class="bekaswit-table__secondary">
                                            {{ $transaction->created_at->translatedFormat('d M Y, H:i') }}
                                        </span>
                                    </td>
                                    <td>{{ $transaction->user?->name ?? 'Pengguna dihapus' }}</td>
                                    <td class="bekaswit-table__primary">
                                        Rp {{ number_format((float) $transaction->price, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <span class="bekaswit-badge bekaswit-badge--{{ $transaction->status }}">
                                            {{ $transaction->status === 'success' ? 'Berhasil' : ($transaction->status === 'cancel' ? 'Batal' : 'Menunggu') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </section>
    </div>
</x-filament-panels::page>

@extends('layout.dashboard')

@section('title', 'Daftar Pembayaran')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Daftar Pembayaran</h1>
            <p class="text-gray-600">Kelola semua pembayaran paket fotografer Anda</p>
        </div>

        <!-- Filter & Search -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-64">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                           placeholder="Kode pembayaran atau nama paket..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="status" 
                            class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Diproses</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Lunas</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Gagal</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                
                <div>
                    <label for="method" class="block text-sm font-medium text-gray-700 mb-2">Metode</label>
                    <select name="method" id="method"
                            class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Metode</option>
                        <option value="bank_transfer" {{ request('method') === 'bank_transfer' ? 'selected' : '' }}>Transfer Bank</option>
                        <option value="e_wallet" {{ request('method') === 'e_wallet' ? 'selected' : '' }}>E-Wallet</option>
                        <option value="credit_card" {{ request('method') === 'credit_card' ? 'selected' : '' }}>Kartu Kredit</option>
                        <option value="cash" {{ request('method') === 'cash' ? 'selected' : '' }}>Tunai</option>
                    </select>
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200">
                        Cari
                    </button>
                    <a href="{{ route('customer.payment.index') }}" 
                       class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300 transition duration-200">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Payments List -->
        @if($payments->count() > 0)
        <div class="space-y-4">
            @foreach($payments as $payment)
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-200">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <!-- Payment Info -->
                    <div class="flex-1">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $payment->package->name }}
                                </h3>
                                <p class="text-sm text-gray-600">
                                    Kode: {{ $payment->payment_code }}
                                </p>
                            </div>
                            
                            <!-- Status Badge -->
                            @switch($payment->status)
                                @case('pending')
                                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                                        Menunggu Pembayaran
                                    </span>
                                    @break
                                @case('processing')
                                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                        Diproses
                                    </span>
                                    @break
                                @case('completed')
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                        Lunas
                                    </span>
                                    @break
                                @case('failed')
                                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                                        Gagal
                                    </span>
                                    @break
                                @case('cancelled')
                                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm font-medium">
                                        Dibatalkan
                                    </span>
                                    @break
                            @endswitch
                        </div>
                        
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Total:</span>
                                <p class="font-semibold text-lg">
                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                </p>
                            </div>
                            
                            <div>
                                <span class="text-gray-500">Metode:</span>
                                <p class="font-medium capitalize">
                                    {{ str_replace('_', ' ', $payment->payment_method) }}
                                </p>
                            </div>
                            
                            <div>
                                <span class="text-gray-500">Cicilan:</span>
                                <p class="font-medium">
                                    {{ $payment->installments->where('status', 'paid')->count() }}/{{ $payment->installments->count() }}
                                </p>
                            </div>
                            
                            <div>
                                <span class="text-gray-500">Tanggal:</span>
                                <p class="font-medium">
                                    {{ $payment->created_at->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Progress Bar for Multiple Installments -->
                        @if($payment->installments->count() > 1)
                        <div class="mt-4">
                            <div class="flex justify-between text-xs text-gray-600 mb-1">
                                <span>Progress Pembayaran</span>
                                <span>{{ number_format($payment->getPaymentProgress(), 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                                     style="width: {{ $payment->getPaymentProgress() }}%"></div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Expired Warning -->
                        @if($payment->status === 'pending' && $payment->isExpired())
                        <div class="mt-3 p-2 bg-red-50 border border-red-200 rounded-md">
                            <p class="text-sm text-red-600">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Pembayaran telah expired pada {{ $payment->expired_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Actions -->
                    <div class="mt-4 lg:mt-0 lg:ml-6 flex flex-col lg:flex-row gap-2">
                        <a href="{{ route('photographer.payment.show', $payment) }}" 
                           class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200 text-center text-sm">
                            Detail
                        </a>
                        
                        @if($payment->status === 'pending' && !$payment->isExpired())
                        <form action="{{ route('customer.payment.cancel', $payment) }}" method="POST" class="inline">
                            @csrf
                            {{-- @method('PATCH') --}}
                            <button type="submit" 
                                    onclick="return confirm('Apakah Anda yakin ingin membatalkan pembayaran ini?')"
                                    class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition duration-200 text-sm w-full lg:w-auto">
                                Batalkan
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $payments->withQueryString()->links() }}
        </div>

        @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Pembayaran</h3>
            <p class="text-gray-600 mb-6">Anda belum memiliki pembayaran. Pilih paket dan mulai proses pembayaran.</p>
            <a href="{{ route('customer.photographers') }}" 
               class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-200 inline-block">
                Cari Fotografer
            </a>
        </div>
        @endif

        <!-- Summary Cards -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Pending</p>
                        <p class="text-xl font-semibold text-gray-900">{{ $pendingCount ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Diproses</p>
                        <p class="text-xl font-semibold text-gray-900">{{ $processingCount ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Lunas</p>
                        <p class="text-xl font-semibold text-gray-900">{{ $completedCount ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-gray-100 rounded-lg">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total Pendapatan</p>
                        <p class="text-xl font-semibold text-gray-900">
                            Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when filter changes
    const statusSelect = document.getElementById('status');
    const methodSelect = document.getElementById('method');
    
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            this.form.submit();
        });
    }
    
    if (methodSelect) {
        methodSelect.addEventListener('change', function() {
            this.form.submit();
        });
    }
    
    // Search with debounce
    const searchInput = document.getElementById('search');
    let searchTimeout;
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 500);
        });
    }
});
</script>
@endpush

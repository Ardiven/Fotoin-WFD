@extends('layout.app')

@section('title', 'Pembayaran Cicilan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('customer.payment.index') }}" class="text-white hover:text-blue-600">
                            Riwayat Pembayaran
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-white">Pembayaran Cicilan</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-3xl font-bold text-white mb-2">Pembayaran Cicilan</h1>
            <p class="text-white">Selesaikan pembayaran cicilan untuk paket fotografer Anda</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Payment Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Package Details -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Detail Paket</h2>
                    <div class="flex items-start space-x-4">
                        @if($installment->payment->booking->package->image)
                        <img src="{{ asset('storage/' . $installment->payment->booking->package->image) }}" 
                             alt="{{ $installment->payment->booking->package->name }}"
                             class="w-20 h-20 object-cover rounded-lg">
                        @else
                        <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        @endif
                        
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $installment->payment->booking->package->name }}</h3>
                            <p class="text-gray-600 mb-2">
                                Fotografer: <span class="font-medium">{{ $installment->payment->booking->package->user->name }}</span>
                            </p>
                            <p class="text-sm text-gray-500">
                                Kode Pembayaran: {{ $installment->payment->payment_code }}
                            </p>
                        </div>
                        
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Total Paket</p>
                            <p class="text-xl font-bold text-gray-900">
                                Rp {{ number_format($installment->payment->amount, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Payment Progress -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Progress Pembayaran</h2>
                    
                    <div class="mb-4">
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Sudah Dibayar</span>
                            <span>{{ number_format($installment->payment->getPaymentProgress(), 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-blue-600 h-3 rounded-full transition-all duration-300" 
                                 style="width: {{ $installment->payment->getPaymentProgress() }}%"></div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Sudah Dibayar:</span>
                            <p class="font-semibold text-green-600">
                                Rp {{ number_format($installment->payment->installments->where('status', 'paid')->sum('amount'), 0, ',', '.') }}
                            </p>
                        </div>
                        <div>
                            <span class="text-gray-500">Sisa Pembayaran:</span>
                            <p class="font-semibold text-red-600">
                                Rp {{ number_format($installment->payment->amount - $installment->payment->installments->where('status', 'paid')->sum('amount'), 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Installment List -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Rincian Cicilan</h2>
                    
                    <div class="space-y-3">
                        @foreach($installment->payment->installments->sortBy('installment_number') as $inst)
                        <div class="flex items-center justify-between p-3 rounded-lg border
                                    {{ $inst->id === $installment->id ? 'border-blue-500 bg-blue-50' : 
                                       ($inst->status === 'paid' ? 'border-green-300 bg-green-50' : 'border-gray-200') }}">
                            <div class="flex items-center space-x-3">
                                @if($inst->status === 'paid')
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                @elseif($inst->id === $installment->id)
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-semibold">{{ $inst->installment_number }}</span>
                                </div>
                                @else
                                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                    <span class="text-gray-600 text-sm font-semibold">{{ $inst->installment_number }}</span>
                                </div>
                                @endif
                                
                                <div>
                                    <p class="font-medium text-gray-900">
                                        Cicilan {{ $inst->installment_number }}
                                        @if($inst->id === $installment->id)
                                            <span class="text-blue-600">(Sedang Dibayar)</span>
                                        @endif
                                    </p>
                                    @if($inst->due_date)
                                    <p class="text-sm text-gray-500">
                                        Jatuh tempo: {{ $inst->due_date->format('d M Y') }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">
                                    Rp {{ number_format($inst->amount, 0, ',', '.') }}
                                </p>
                                @if($inst->status === 'paid' && $inst->paid_at)
                                <p class="text-sm text-green-600">
                                    Dibayar {{ $inst->paid_at->format('d M Y') }}
                                </p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Payment Method Selection -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">Instruksi Pembayaran</h2>
                    
                    <form action="{{ route('customer.payment.process', $installment->payment) }}" method="POST" id="paymentForm">
                        @csrf
                        
                        @if($payment->status === 'pending' || $payment->status === 'processing' && !$payment->isExpired())
                        <div class="mt-1 bg-white rounded-lg p-6">
                            @switch($payment->payment_method)
                                @case('bank_transfer')
                                    <div class="space-y-4">
                                        <h4 class="font-medium text-blue-800">Transfer ke salah satu rekening berikut:</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="bg-white p-4 rounded-lg border">
                                                <div class="flex items-center mb-2">
                                                    <img src="https://i.pinimg.com/736x/0b/ed/5c/0bed5c44c43dc1efd1cbf6acf3aa1d89.jpg" alt="BCA" class="mr-2 w-24 h-20">
                                                    <span class="font-medium">Bank BCA</span>
                                                </div>
                                                <p class="text-sm text-gray-600">No. Rekening: 1234567890</p>
                                                <p class="text-sm text-gray-600">a.n. Studio Fotografer</p>
                                            </div>
                                            <div class="bg-white p-4 rounded-lg border">
                                                <div class="flex items-center mb-2">
                                                    <img src="https://i.pinimg.com/736x/26/b3/4a/26b34ac4c3890d30ebc7a7ba9a829999.jpg" alt="Mandiri" class="mr-2 w-24 h-20">
                                                    <span class="font-medium">Bank Mandiri</span>
                                                </div>
                                                <p class="text-sm text-gray-600">No. Rekening: 0987654321</p>
                                                <p class="text-sm text-gray-600">a.n. Studio Fotografer</p>
                                            </div>
                                        </div>
                                    </div>
                                    @break
                                    
                                @case('e_wallet')
                                    <div class="space-y-4">
                                        <h4 class="font-medium text-blue-800">Transfer ke E-Wallet:</h4>
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                            <div class="bg-white p-3 rounded-lg border text-center">
                                                <img src="https://i.pinimg.com/736x/fe/ce/b2/feceb2ca508603b06c2f7ba18a5d018d.jpg" alt="GoPay" class="mx-auto mb-2 w-36 h-30">
                                                <p class="text-xs text-gray-600">081234567890</p>
                                            </div>
                                            <div class="bg-white p-3 rounded-lg border text-center">
                                                <img src="https://i.pinimg.com/736x/76/1a/bf/761abfb9e4c628b0f4b9943c390e93b3.jpg" alt="OVO" class="mx-auto mb-2 w-36 h-30">
                                                <p class="text-xs text-gray-600">081234567890</p>
                                            </div>
                                            <div class="bg-white p-3 rounded-lg border text-center">
                                                <img src="https://i.pinimg.com/736x/f5/8c/a3/f58ca3528b238877e9855fcac1daa328.jpg" alt="DANA" class="mx-auto mb-2 w-36 h-30">
                                                <p class="text-xs text-gray-600">081234567890</p>
                                            </div>
                                            <div class="bg-white p-3 rounded-lg border text-center">
                                                <img src="https://i.pinimg.com/736x/d0/19/16/d019163d861908ed0046391ebfa42ce1.jpg" alt="ShopeePay" class="mx-auto mb-2 w-36 h-30">
                                                <p class="text-xs text-gray-600">081234567890</p>
                                            </div>
                                        </div>
                                    </div>
                                    @break
                                    
                                @case('cash')
                                    <div class="space-y-2">
                                        <h4 class="font-medium text-blue-800">Pembayaran Tunai:</h4>
                                        <p class="text-blue-700">Pembayaran akan dilakukan saat bertemu di lokasi pemotretan.</p>
                                        <p class="text-sm text-blue-600">Pastikan untuk membawa uang pas sesuai nominal.</p>
                                    </div>
                                    @break
                            @endswitch
                        </div>
                        @endif
                        
                        @error('payment_method')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </form>
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Ringkasan Pembayaran</h2>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Cicilan ke-{{ $installment->installment_number }}</span>
                            <span class="font-medium">Rp {{ number_format($installment->amount, 0, ',', '.') }}</span>
                        </div>
                        
                        @if($installment->due_date)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Jatuh Tempo</span>
                            <span class="text-gray-700">{{ $installment->due_date->format('d M Y') }}</span>
                        </div>
                        @endif
                        
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Biaya Admin</span>
                            <span class="text-gray-700">Rp 0</span>
                        </div>
                        
                        <hr class="my-3">
                        
                        <div class="flex justify-between text-lg font-semibold">
                            <span>Total Pembayaran</span>
                            <span class="text-blue-600">Rp {{ number_format($installment->amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Due Date Warning -->
                    @if($installment->due_date && $installment->due_date->diffInDays(now()) <= 3)
                    <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex">
                            <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-yellow-800">Peringatan Jatuh Tempo</p>
                                <p class="text-sm text-yellow-700">
                                    Pembayaran akan jatuh tempo dalam {{ $installment->due_date->diffInDays(now()) }} hari
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="space-y-3">
                        <button type="submit" form="paymentForm" 
                                class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                            <i class="fas fa-credit-card mr-2"></i>
                            Bayar Sekarang
                        </button>
                        
                        <a href="{{ route('customer.payment.show', $installment->payment) }}" 
                           class="w-full bg-gray-200 text-gray-800 py-3 px-4 rounded-lg hover:bg-gray-300 transition duration-200 text-center block">
                            Kembali ke Detail
                        </a>
                    </div>

                    <!-- Security Notice -->
                    <div class="mt-6 p-3 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-2">
                                <p class="text-sm font-medium text-green-800">Pembayaran Aman</p>
                                <p class="text-xs text-green-700">Data Anda dilindungi dengan enkripsi SSL</p>
                            </div>
                        </div>
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
    // Auto-select first payment method if none selected
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const paymentForm = document.getElementById('paymentForm');
    
    // Check if any payment method is already selected
    let hasSelected = false;
    paymentMethods.forEach(method => {
        if (method.checked) {
            hasSelected = true;
        }
    });
    
    // Select first method if none selected
    if (!hasSelected && paymentMethods.length > 0) {
        paymentMethods[0].checked = true;
    }
    
    // Form validation
    // paymentForm.addEventListener('submit', function(e) {
    //     let selectedMethod = false;
    //     paymentMethods.forEach(method => {
    //         if (method.checked) {
    //             selectedMethod = true;
    //         }
    //     });
        
    //     if (!selectedMethod) {
    //         e.preventDefault();
    //         alert('Silakan pilih metode pembayaran terlebih dahulu.');
    //         return false;
    //     }
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
    });
    
    // Payment method selection feedback
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            // Remove active state from all labels
            document.querySelectorAll('label').forEach(label => {
                label.classList.remove('border-blue-500', 'bg-blue-50');
            });
            
            // Add active state to selected label
            if (this.checked) {
                this.closest('label').classList.add('border-blue-500', 'bg-blue-50');
            }
        });
    });
    
    // Initialize active state for already selected method
    paymentMethods.forEach(method => {
        if (method.checked) {
            method.closest('label').classList.add('border-blue-500', 'bg-blue-50');
        }
    });
});
</script>
@endpush
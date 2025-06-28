@extends('layout.app')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Detail Pembayaran</h1>
                    <p class="text-white">Kode Pembayaran: <span class="font-semibold">{{ $payment->payment_code }}</span></p>
                </div>
                
                <!-- Status Badge -->
                <div class="flex items-center space-x-2">
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
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Payment Summary -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Ringkasan Pembayaran</h2>
                
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Paket:</span>
                        <span class="font-medium">{{ $payment->booking->package->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total:</span>
                        <span class="font-bold text-lg">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Metode:</span>
                        <span class="font-medium capitalize">{{ str_replace('_', ' ', $payment->payment_method) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Dibuat:</span>
                        <span class="font-medium">{{ $payment->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    @if($payment->expired_at)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Expired:</span>
                        <span class="font-medium {{ $payment->isExpired() ? 'text-red-600' : '' }}">
                            {{ $payment->expired_at->format('d M Y, H:i') }}
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Progress Bar -->
                @if($payment->installments->count() > 1)
                <div class="mt-6">
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>Progress Pembayaran</span>
                        <span>{{ number_format($payment->getPaymentProgress(), 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                             style="width: {{ $payment->getPaymentProgress() }}%"></div>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600 mt-1">
                        <span>Rp {{ number_format($payment->getTotalPaidAmount(), 0, ',', '.') }}</span>
                        <span>Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                    </div>
                </div>
                @endif

                @if($payment->notes)
                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-700"><strong>Catatan:</strong> {{ $payment->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Installments -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Detail Cicilan</h2>
                
                <div class="space-y-4">
                    @foreach($payment->installments as $installment)
                    <div class="border rounded-lg p-4 {{ $installment->isOverdue() ? 'border-red-200 bg-red-50' : 'border-gray-200' }}">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-medium text-gray-900">
                                    Cicilan {{ $installment->installment_number }}
                                    @if($installment->installment_number == 1 && $payment->installments->count() > 1)
                                        (DP)
                                    @endif
                                </h3>
                                <p class="text-sm text-gray-600">
                                    Jatuh tempo: {{ $installment->due_date->format('d M Y') }}
                                    @if($installment->isOverdue())
                                        <span class="text-red-600 font-medium">(Terlambat)</span>
                                    @endif
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-lg">
                                    Rp {{ number_format($installment->amount, 0, ',', '.') }}
                                </p>
                                @switch($installment->status)
                                    @case('paid')
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">
                                            Lunas
                                        </span>
                                        @break
                                    @case('overdue')
                                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">
                                            Terlambat
                                        </span>
                                        @break
                                    @default
                                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">
                                            Belum Bayar
                                        </span>
                                @endswitch
                            </div>
                        </div>
                        
                        @if($installment->paid_at)
                        <p class="text-xs text-gray-500 mt-2">
                            Dibayar: {{ $installment->paid_at->format('d M Y, H:i') }}
                        </p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>


        <!-- Payment Proofs -->
        @if($payment->proofs->count() > 0)
        <div class="mt-8 bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Bukti Pembayaran</h3>
            
            <div class="space-y-4">
                @foreach($payment->proofs as $proof)
                <div class="border rounded-lg p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-medium text-gray-900">{{ $proof->original_name }}</p>
                            <p class="text-sm text-gray-600">Upload: {{ $proof->created_at->format('d M Y, H:i') }}</p>
                            @if($proof->admin_notes)
                            <p class="text-sm text-gray-600 mt-1">
                                <strong>Catatan Admin:</strong> {{ $proof->admin_notes }}
                            </p>
                            @endif
                        </div>
                        <div class="flex items-center space-x-2">
                            @switch($proof->status)
                                @case('approved')
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">
                                        Disetujui
                                    </span>
                                    @break
                                @case('rejected')
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">
                                        Ditolak
                                    </span>
                                    @break
                                @default
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">
                                        Menunggu Verifikasi
                                    </span>
                            @endswitch
                            <a href="{{ Storage::url($proof->file_path) }}" target="_blank" 
                               class="text-blue-600 hover:text-blue-800 text-sm">
                                Lihat
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="mt-8 flex justify-between items-center">
            <a href="{{ route('customer.payment.index') }}" 
               class="bg-gray-200 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-300 transition duration-200">
                Kembali ke Daftar Pembayaran
            </a>
            @if($payment->status === 'processing' && !$payment->isExpired() && (!$payment->proofs->where('status', 'pending')->count() > 0 && $payment->proofs->where('status', 'rejected')->count() > 0))
                <form action="{{ route('customer.payment.process', $payment) }}" method="POST" id="paymentForm">
                    @csrf
                    <button type="submit" 
                            class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition duration-200">
                        Upload Bukti Pembayaran
                    </button>
                </form>
            @endif
            
            @if($payment->status === 'pending' && !$payment->isExpired())
            <form action="{{ route('customer.payment.cancel', $payment) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" 
                        onclick="return confirm('Apakah Anda yakin ingin membatalkan pembayaran ini?')"
                        class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition duration-200">
                    Batalkan Pembayaran
                </button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto refresh untuk status pembayaran
    @if($payment->status === 'pending' || $payment->status === 'processing')
    setInterval(function() {
        // Refresh halaman setiap 30 detik untuk update status
        if (document.hidden === false) {
            location.reload();
        }
    }, 30000);
    @endif
    
    // File upload preview
    const fileInput = document.getElementById('proof_file');
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validasi ukuran file
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 2MB.');
                    this.value = '';
                    return;
                }
                
                // Validasi tipe file
                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Tipe file tidak didukung. Gunakan JPG, PNG, atau GIF.');
                    this.value = '';
                    return;
                }
            }
        });
    }
});
</script>
@endpush
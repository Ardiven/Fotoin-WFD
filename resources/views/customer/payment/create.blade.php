@extends('layout.app')

@section('title', 'Pembayaran Paket')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <!-- Enhanced Header with Animation -->
            <div class="text-center mb-12 animate-fade-in">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent mb-3">
                    Pembayaran Paket
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Lengkapi pembayaran untuk paket fotografer profesional Anda
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Enhanced Package Details -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-105 transition-all duration-300">
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6 text-white">
                        <h2 class="text-2xl font-bold mb-2">Detail Paket</h2>
                        <p class="text-blue-100">Paket yang Anda pilih</p>
                    </div>
                    
                    <div class="p-8 space-y-6">
                        <div class="text-center">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $package->name }}</h3>
                            <p class="text-gray-600 leading-relaxed">{{ $package->description }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-6">
                            <h4 class="font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Fitur Paket
                            </h4>
                            <ul class="space-y-3">
                                @foreach($package->features as $feature)
                                <li class="flex items-center text-gray-700 bg-white rounded-lg p-3 border border-gray-200">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                    <span class="font-medium">{{ $feature->name }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-600">{{ $package->duration }}</div>
                                    <div class="text-sm text-gray-600 font-medium">Jam</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-purple-600">
                                        Rp {{ number_format($package->price, 0, ',', '.') }}
                                    </div>
                                    <div class="text-sm text-gray-600 font-medium">Total Harga</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Payment Form -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6 text-white">
                        <h2 class="text-2xl font-bold mb-2">Metode Pembayaran</h2>
                        <p class="text-purple-100">Pilih cara pembayaran yang nyaman</p>
                    </div>

                    <form action="{{route('customer.payment.store', $package)}}" method="POST" class="p-8 space-y-8">
                        @csrf

                        <!-- Enhanced Payment Method -->
                        <div class="space-y-4">
                            <label class="block text-lg font-bold text-gray-900 mb-4">Metode Pembayaran</label>
                            <div class="grid grid-cols-1 gap-4">
                                <label class="payment-option group">
                                    <input type="radio" name="payment_method" value="bank_transfer" class="sr-only" required>
                                    <div class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer transition-all duration-300 group-hover:border-blue-300 group-hover:shadow-md">
                                        <div class="w-6 h-6 border-2 border-gray-300 rounded-full mr-4 flex items-center justify-center group-hover:border-blue-500">
                                            <div class="w-3 h-3 bg-blue-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="font-bold text-gray-900 mb-1">Transfer Bank</div>
                                            <div class="text-sm text-gray-500">BCA, Mandiri, BNI, BRI</div>
                                        </div>
                                        <div class="text-blue-500">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </label>

                                <label class="payment-option group">
                                    <input type="radio" name="payment_method" value="e_wallet" class="sr-only">
                                    <div class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer transition-all duration-300 group-hover:border-green-300 group-hover:shadow-md">
                                        <div class="w-6 h-6 border-2 border-gray-300 rounded-full mr-4 flex items-center justify-center group-hover:border-green-500">
                                            <div class="w-3 h-3 bg-green-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="font-bold text-gray-900 mb-1">E-Wallet</div>
                                            <div class="text-sm text-gray-500">GoPay, OVO, DANA, ShopeePay</div>
                                        </div>
                                        <div class="text-green-500">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </label>

                                <label class="payment-option group">
                                    <input type="radio" name="payment_method" value="credit_card" class="sr-only">
                                    <div class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer transition-all duration-300 group-hover:border-purple-300 group-hover:shadow-md">
                                        <div class="w-6 h-6 border-2 border-gray-300 rounded-full mr-4 flex items-center justify-center group-hover:border-purple-500">
                                            <div class="w-3 h-3 bg-purple-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="font-bold text-gray-900 mb-1">Kartu Kredit</div>
                                            <div class="text-sm text-gray-500">Visa, MasterCard, JCB</div>
                                        </div>
                                        <div class="text-purple-500">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </label>

                                <label class="payment-option group">
                                    <input type="radio" name="payment_method" value="cash" class="sr-only">
                                    <div class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer transition-all duration-300 group-hover:border-yellow-300 group-hover:shadow-md">
                                        <div class="w-6 h-6 border-2 border-gray-300 rounded-full mr-4 flex items-center justify-center group-hover:border-yellow-500">
                                            <div class="w-3 h-3 bg-yellow-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="font-bold text-gray-900 mb-1">Tunai</div>
                                            <div class="text-sm text-gray-500">Bayar langsung saat bertemu</div>
                                        </div>
                                        <div class="text-yellow-500">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Enhanced Installment Type -->
                        <div class="space-y-4">
                            <label class="block text-lg font-bold text-gray-900 mb-4">Jenis Pembayaran</label>
                            <div class="space-y-4">
                                <label class="installment-option group">
                                    <input type="radio" name="installment_type" value="full" class="sr-only" required>
                                    <div class="p-4 border-2 border-gray-200 rounded-xl cursor-pointer transition-all duration-300 group-hover:border-green-300 group-hover:shadow-md relative">
                                        <div class="absolute top-2 right-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                            DISKON 5%
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 border-2 border-gray-300 rounded-full mr-4 flex items-center justify-center group-hover:border-green-500">
                                                <div class="w-3 h-3 bg-green-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-900 mb-1">Bayar Penuh</div>
                                                <div class="text-sm text-gray-500">
                                                    <span class="line-through text-gray-400">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                                                    <span class="text-green-600 font-bold ml-2">Rp {{ number_format($package->price * 0.95, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label class="installment-option group">
                                    <input type="radio" name="installment_type" value="dp_50" class="sr-only">
                                    <div class="p-4 border-2 border-gray-200 rounded-xl cursor-pointer transition-all duration-300 group-hover:border-blue-300 group-hover:shadow-md">
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 border-2 border-gray-300 rounded-full mr-4 flex items-center justify-center group-hover:border-blue-500">
                                                <div class="w-3 h-3 bg-blue-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-900 mb-1">DP 50%</div>
                                                <div class="text-sm text-gray-500">
                                                    <div>DP: <span class="font-semibold">Rp {{ number_format($package->price * 0.5, 0, ',', '.') }}</span></div>
                                                    <div>Pelunasan: <span class="font-semibold">Rp {{ number_format($package->price * 0.5, 0, ',', '.') }}</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label class="installment-option group">
                                    <input type="radio" name="installment_type" value="installment_3" class="sr-only">
                                    <div class="p-4 border-2 border-gray-200 rounded-xl cursor-pointer transition-all duration-300 group-hover:border-orange-300 group-hover:shadow-md">
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 border-2 border-gray-300 rounded-full mr-4 flex items-center justify-center group-hover:border-orange-500">
                                                <div class="w-3 h-3 bg-orange-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-900 mb-1">Cicilan 3x</div>
                                                <div class="text-sm text-gray-500">
                                                    <span class="font-semibold">Rp {{ number_format($package->price / 3, 0, ',', '.') }}</span> per bulan
                                                    <span class="text-orange-600 ml-1">(+2% admin)</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Enhanced Notes -->
                        <div>
                            <label for="notes" class="block text-lg font-bold text-gray-900 mb-3">Catatan Khusus</label>
                            <textarea name="notes" id="notes" rows="4" 
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 resize-none"
                                      placeholder="Tambahkan catatan atau permintaan khusus untuk fotografer..."></textarea>
                        </div>

                        <!-- Enhanced Terms -->
                        <div class="bg-gray-50 rounded-xl p-4">
                            <div class="flex items-start">
                                <input type="checkbox" id="terms" class="mt-1 w-5 h-5 text-blue-600 rounded focus:ring-blue-500" required>
                                <label for="terms" class="ml-3 text-sm text-gray-700 leading-relaxed">
                                    Saya setuju dengan 
                                    <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">syarat dan ketentuan</a> 
                                    serta 
                                    <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">kebijakan privasi</a>
                                    yang berlaku.
                                </label>
                            </div>
                        </div>

                        <!-- Price Display -->
                        <div id="price-display" class="mb-6">
                            <!-- Price will be updated dynamically -->
                        </div>

                        <!-- Enhanced Submit Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 pt-4">
                            <button type="submit" 
                                    class="flex-1 bg-gradient-to-r from-blue-600 to-purple-600 text-white py-4 px-6 rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-300 font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    Lanjutkan Pembayaran
                                </span>
                            </button>
                            <a href="{{ route('customer.photographers.show', $photographer) }}" 
                               class="flex-1 bg-gray-100 text-gray-700 py-4 px-6 rounded-xl hover:bg-gray-200 transition-all duration-300 font-bold text-lg text-center border-2 border-gray-200 hover:border-gray-300">
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Kembali
                                </span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Enhanced Payment Info -->
            <div class="mt-12 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-8 text-white shadow-xl">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold mb-2">Informasi Penting</h3>
                    <p class="text-blue-100">Hal-hal yang perlu Anda ketahui</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-semibold">Waktu Pembayaran</span>
                        </div>
                        <p class="text-sm text-blue-100">Pembayaran expired dalam 3 hari setelah dibuat</p>
                    </div>
                    <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-semibold">Upload Bukti</span>
                        </div>
                        <p class="text-sm text-blue-100">Upload bukti pembayaran untuk verifikasi</p>
                    </div>
                    <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-semibold">Konfirmasi Cepat</span>
                        </div>
                        <p class="text-sm text-blue-100">Konfirmasi pembayaran dalam 1x24 jam</p>
                    </div>
                    <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                            </svg>
                            <span class="font-semibold">Customer Service</span>
                        </div>
                        <p class="text-sm text-blue-100">Hubungi CS jika ada kendala pembayaran</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}

.payment-option input[type="radio"]:checked + div {
    border-color: #3B82F6;
    background-color: #EFF6FF;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.payment-option input[type="radio"]:checked + div .w-6.h-6 {
    border-color: #3B82F6;
}

.payment-option input[type="radio"]:checked + div .w-6.h-6 .w-3.h-3 {
    opacity: 1;
}

.installment-option input[type="radio"]:checked + div {
    border-color: #10B981;
    background-color: #ECFDF5;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

.installment-option input[type="radio"]:checked + div .w-6.h-6 {
    border-color: #10B981;
}

.installment-option input[type="radio"]:checked + div .w-6.h-6 .w-3.h-3 {
    opacity: 1;
    background-color: #10B981;
}

.installment-option input[name="installment_type"][value="dp_50"]:checked + div {
    border-color: #3B82F6;
    background-color: #EFF6FF;
}

.installment-option input[name="installment_type"][value="dp_50"]:checked + div .w-6.h-6 .w-3.h-3 {
    background-color: #3B82F6;
}

.installment-option input[name="installment_type"][value="installment_3"]:checked + div {
    border-color: #F59E0B;
    background-color: #FFFBEB;
}

.installment-option input[name="installment_type"][value="installment_3"]:checked + div .w-6.h-6 .w-3.h-3 {
    background-color: #F59E0B;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const installmentTypes = document.querySelectorAll('input[name="installment_type"]');
    
    // Auto-select full payment for cash method
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            if (this.value === 'cash') {
                document.querySelector('input[value="full"]').checked = true;
                installmentTypes.forEach(type => {
                    if (type.value !== 'full') {
                        type.disabled = true;
                        type.closest('.installment-option').classList.add('opacity-50', 'pointer-events-none');
                    }
                });
            } else {
                installmentTypes.forEach(type => {
                    type.disabled = false;
                    type.closest('.installment-option').classList.remove('opacity-50', 'pointer-events-none');
                });
            }
        });
    });

    // Add smooth scrolling to form sections
    const radioButtons = document.querySelectorAll('input[type="radio"]');
    
    radioButtons.forEach(radio => {
        radio.addEventListener('change', function() {
            // Add a subtle animation when selection changes
            this.closest('label').style.transform = 'scale(1.02)';
            setTimeout(() => {
                this.closest('label').style.transform = 'scale(1)';
            }, 150);
        });
    });

    // Form validation enhancement
    const form = document.querySelector('form');
    const submitButton = form.querySelector('button[type="submit"]');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Add loading state to submit button
        submitButton.innerHTML = `
            <span class="flex items-center justify-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses Pembayaran...
            </span>
        `;
        submitButton.disabled = true;
        
        // Simulate form submission (replace with actual form submission)
        setTimeout(() => {
            // Reset button state or redirect to next page
            this.submit();
        }, 2000);
    });

    // Add interactive effects
    const cards = document.querySelectorAll('.bg-white.rounded-2xl');
    
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Price calculation display
    function updatePriceDisplay() {
        const selectedInstallment = document.querySelector('input[name="installment_type"]:checked');
        const packagePrice = {{ $package->price }};
        
        if (selectedInstallment) {
            let displayPrice = packagePrice;
            let priceLabel = 'Total Pembayaran';
            
            switch(selectedInstallment.value) {
                case 'full':
                    displayPrice = packagePrice * 0.95;
                    priceLabel = 'Total Pembayaran (Diskon 5%)';
                    break;
                case 'dp_50':
                    displayPrice = packagePrice * 0.5;
                    priceLabel = 'Pembayaran DP (50%)';
                    break;
                case 'installment_3':
                    displayPrice = packagePrice / 3;
                    priceLabel = 'Pembayaran Per Bulan';
                    break;
            }
            
            // Update price display if exists
            const priceDisplay = document.getElementById('price-display');
            if (priceDisplay) {
                priceDisplay.innerHTML = `
                    <div class="text-center p-4 bg-gradient-to-r from-green-50 to-blue-50 rounded-xl border-2 border-green-200">
                        <div class="text-sm text-gray-600 mb-1">${priceLabel}</div>
                        <div class="text-2xl font-bold text-green-600">Rp ${displayPrice.toLocaleString('id-ID')}</div>
                    </div>
                `;
            }
        }
    }
    
    // Add price display update listeners
    installmentTypes.forEach(type => {
        type.addEventListener('change', updatePriceDisplay);
    });
    
    // Initial price display
    updatePriceDisplay();
});
</script>
@endpush
{{-- resources/views/customer/payment/upload.blade.php --}}
@extends('layout.app')

@section('title', 'Upload Bukti Pembayaran')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('customer.payment.index') }}" class="text-gray-700 hover:text-blue-600 transition-colors">
                            Riwayat Pembayaran
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('customer.payment.show', $installment->payment) }}" class="ml-1 text-gray-700 hover:text-blue-600 transition-colors">
                                Detail Pembayaran
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-gray-500">Upload Bukti Pembayaran</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Upload Bukti Pembayaran</h1>
            <p class="text-gray-600">Upload bukti pembayaran untuk cicilan ke-{{ $installment->installment_number }}</p>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <p class="text-green-800">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <p class="text-red-800">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Upload Form -->
            <div class="lg:col-span-2">
                <form action="{{ route('customer.payment.upload.store', $payment) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="payment_method" value="{{ $paymentMethod }}">
                    <input type="hidden" name="installment_id" value="{{ $installment->id }}">
                    
                    <div class="space-y-6">
                        <!-- Payment Information -->
                        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Informasi Pembayaran
                            </h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                                    <div class="p-3 bg-gray-50 rounded-lg border">
                                        @switch($paymentMethod)
                                            @case('bank_transfer')
                                                <div class="flex items-center space-x-2">
                                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                    </svg>
                                                    <span class="font-medium">Transfer Bank</span>
                                                </div>
                                                @break
                                            @case('e_wallet')
                                                <div class="flex items-center space-x-2">
                                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                    </svg>
                                                    <span class="font-medium">E-Wallet</span>
                                                </div>
                                                @break
                                            @case('credit_card')
                                                <div class="flex items-center space-x-2">
                                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                    </svg>
                                                    <span class="font-medium">Kartu Kredit</span>
                                                </div>
                                                @break
                                        @endswitch
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Pembayaran</label>
                                    <div class="p-3 bg-gray-50 rounded-lg border">
                                        <span class="text-lg font-semibold text-gray-900">
                                            Rp {{ number_format($installment->amount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Upload Section -->
                        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                Upload Bukti Pembayaran
                            </h2>
                            
                            <div class="space-y-4">
                                <!-- File Upload -->
                                <div>
                                    <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-2">
                                        Bukti Pembayaran <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                                        <div class="space-y-1 text-center">
                                            <div class="flex text-sm text-gray-600">
                                                <label for="payment_proof" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                    <input id="payment_proof" 
                                                           name="payment_proof" 
                                                           type="file"
                                                           accept="image/*"
                                                           required>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @error('payment_proof')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                <!-- Payment Date -->
                                <div>
                                    <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tanggal Pembayaran <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" 
                                           id="payment_date" 
                                           name="payment_date" 
                                           value="{{ old('payment_date', date('Y-m-d')) }}"
                                           max="{{ date('Y-m-d') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('payment_date') border-red-300 @enderror"
                                           required>
                                    @error('payment_date')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                <!-- Account Information -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="account_name" class="block text-sm font-medium text-gray-700 mb-2">
                                            Nama Pemilik Rekening <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               id="account_name" 
                                               name="account_name" 
                                               value="{{ old('account_name') }}"
                                               placeholder="Nama sesuai rekening/e-wallet"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('account_name') border-red-300 @enderror"
                                               required>
                                        @error('account_name')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="account_number" class="block text-sm font-medium text-gray-700 mb-2">
                                            Nomor Rekening/HP
                                        </label>
                                        <input type="text" 
                                               id="account_number" 
                                               name="account_number" 
                                               value="{{ old('account_number') }}"
                                               placeholder="Nomor rekening atau nomor HP"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('account_number') border-red-300 @enderror">
                                        @error('account_number')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Notes -->
                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                        Catatan Tambahan
                                    </label>
                                    <textarea id="notes" 
                                              name="notes" 
                                              rows="3" 
                                              placeholder="Catatan tambahan (opsional)"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none @error('notes') border-red-300 @enderror">{{ old('notes') }}</textarea>
                                    @error('notes')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="mt-6">
                                <button type="submit" 
                                        class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                    <span class="flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        Upload Bukti Pembayaran
                                    </span>
                                </button>
                            </div>
                        </div>

                        <!-- Instructions -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-blue-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Petunjuk Upload Bukti Pembayaran
                            </h3>
                            <ul class="space-y-2 text-sm text-blue-800">
                                <li class="flex items-start space-x-2">
                                    <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Pastikan foto bukti pembayaran jelas dan tidak buram</span>
                                </li>
                                <li class="flex items-start space-x-2">
                                    <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Foto harus menampilkan jumlah, tanggal, dan tujuan transfer</span>
                                </li>
                                <li class="flex items-start space-x-2">
                                    <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Format file yang diterima: PNG, JPG, JPEG (maksimal 2MB)</span>
                                </li>
                                <li class="flex items-start space-x-2">
                                    <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Verifikasi akan dilakukan dalam 1x24 jam</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Payment Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-6 border border-gray-100">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Ringkasan Pembayaran
                    </h2>
                    
                    <!-- Package Info -->
                    <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center space-x-3">
                            @if($installment->payment->booking->package->image)
                            <img src="{{ asset('storage/' . $installment->payment->booking->package->image) }}" 
                                 alt="{{ $installment->payment->booking->package->name }}"
                                 class="w-12 h-12 object-cover rounded-lg border border-gray-200">
                            @else
                            <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center border border-gray-300">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                </svg>
                            </div>
                            @endif
                            <div>
                                <h3 class="font-semibold text-gray-900 text-sm">{{ $installment->payment->booking->package->name }}</h3>
                                <p class="text-xs text-gray-500">Paket Wisata</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-sm text-gray-600">Cicilan ke-</span>
                            <span class="font-medium">{{ $installment->installment_number }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-sm text-gray-600">Jatuh Tempo</span>
                            <span class="font-medium">{{ date('d/m/Y', strtotime($installment->due_date)) }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-sm text-gray-600">Status</span>
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                @if($installment->status === 'pending')
                                    bg-yellow-100 text-yellow-800
                                @elseif($installment->status === 'paid')
                                    bg-green-100 text-green-800
                                @elseif($installment->status === 'overdue')
                                    bg-red-100 text-red-800
                                @else
                                    bg-gray-100 text-gray-800
                                @endif
                            ">
                                @switch($installment->status)
                                    @case('pending')
                                        Menunggu Pembayaran
                                        @break
                                    @case('paid')
                                        Lunas
                                        @break
                                    @case('overdue')
                                        Terlambat
                                        @break
                                    @default
                                        {{ ucfirst($installment->status) }}
                                @endswitch
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 pt-4">
                            <span class="text-lg font-semibold text-gray-900">Total Pembayaran</span>
                            <span class="text-xl font-bold text-blue-600">
                                Rp {{ number_format($installment->amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <!-- Contact Support -->
                    <div class="mt-6 text-center">
                        <p class="text-xs text-gray-500 mb-2">Butuh bantuan?</p>
                        <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors">
                            Hubungi Customer Service
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Simple JavaScript for file preview (optional) --}}
@push('scripts')
<script>
// Fixed JavaScript untuk upload bukti pembayaran dengan preview
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('payment_proof');
    const dropZone = document.querySelector('.border-dashed');
    const form = document.querySelector('form');
    
    // File input change handler
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (validateFile(file)) {
                    showFilePreview(file);
                } else {
                    this.value = '';
                    removePreview();
                }
            } else {
                removePreview();
            }
        });
    }
    
    // Enhanced drag and drop functionality
    if (dropZone) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight(e) {
            dropZone.classList.add('border-blue-500', 'bg-blue-50');
        }
        
        function unhighlight(e) {
            dropZone.classList.remove('border-blue-500', 'bg-blue-50');
        }
        
        dropZone.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                const file = files[0];
                
                if (validateFile(file)) {
                    // Assign to file input
                    if (fileInput) {
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        fileInput.files = dataTransfer.files;
                        showFilePreview(file);
                    }
                }
            }
        }
    }
    
    // File validation function
    function validateFile(file) {
        const maxSize = 2 * 1024 * 1024; // 2MB
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        
        if (file.size > maxSize) {
            alert('Ukuran file terlalu besar. Maksimal 2MB');
            return false;
        }
        
        if (!allowedTypes.includes(file.type)) {
            alert('Format file tidak didukung. Gunakan PNG, JPG, atau JPEG');
            return false;
        }
        
        return true;
    }
    
    // Show file preview function
    function showFilePreview(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Remove existing preview
            removePreview();
            
            // Create preview element
            const preview = document.createElement('div');
            preview.id = 'file-preview';
            preview.className = 'mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200';
            
            preview.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <img src="${e.target.result}" 
                                 alt="Preview" 
                                 class="w-16 h-16 object-cover rounded-lg border border-gray-300 shadow-sm">
                            <div class="absolute -top-2 -right-2">
                                <button type="button" 
                                        onclick="removeFilePreview()" 
                                        class="bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition-colors shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">${file.name}</p>
                            <p class="text-xs text-gray-500">${formatFileSize(file.size)}</p>
                            <p class="text-xs text-green-600 mt-1 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                File siap diupload
                            </p>
                        </div>
                    </div>
                </div>
            `;
            
            // Insert preview after the drop zone
            if (dropZone && dropZone.parentNode) {
                dropZone.parentNode.insertBefore(preview, dropZone.nextSibling);
            }
        };
        
        reader.onerror = function() {
            console.error('Error reading file');
            alert('Error membaca file. Silakan coba lagi.');
        };
        
        reader.readAsDataURL(file);
    }
    
    // Date validation - prevent future dates
    const dateInput = document.getElementById('payment_date');
    if (dateInput) {
        dateInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(23, 59, 59, 999);
            
            if (selectedDate > today) {
                alert('Tanggal pembayaran tidak boleh lebih dari hari ini');
                this.value = new Date().toISOString().split('T')[0];
            }
        });
    }
    
    // Form validation before submit
    if (form) {
        form.addEventListener('submit', function(e) {
            const requiredFields = [
                { name: 'payment_proof', label: 'Bukti Pembayaran' },
                { name: 'payment_date', label: 'Tanggal Pembayaran' },
                { name: 'account_name', label: 'Nama Pemilik Rekening' }
            ];
            
            let isValid = true;
            let missingFields = [];
            
            requiredFields.forEach(field => {
                const element = document.querySelector(`[name="${field.name}"]`);
                if (element && !element.value.trim()) {
                    isValid = false;
                    missingFields.push(field.label);
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert(`Mohon lengkapi field berikut:\n- ${missingFields.join('\n- ')}`);
                return;
            }
            
            // Show loading state
            showLoadingState();
        });
    }
    
    // Show loading state for form submission
    function showLoadingState() {
        const submitButton = form?.querySelector('button[type="submit"]');
        
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <span class="flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Mengupload...
                </span>
            `;
        }
    }
});

// Global function to remove preview (dipanggil dari button onclick)
function removeFilePreview() {
    const preview = document.getElementById('file-preview');
    const fileInput = document.getElementById('payment_proof');
    
    if (preview) {
        preview.remove();
    }
    
    if (fileInput) {
        fileInput.value = '';
    }
}

// Global function to remove preview (untuk konsistensi)
function removePreview() {
    removeFilePreview();
}

// File size formatter
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}
@endpush
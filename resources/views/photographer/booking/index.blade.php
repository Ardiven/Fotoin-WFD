@extends('layout.dashboard')

@section('title', 'My Bookings - Photographer Dashboard')

@push('styles')
<style>
    .bg-gradient-auth {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .bg-gradient-secondary {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    }
    .glass-effect {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.1);
    }
    .booking-card {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }
    .booking-card:hover {
        background: rgba(255, 255, 255, 0.12);
        transform: translateY(-2px);
    }
    .status-pending {
        background-color: rgba(255, 193, 7, 0.595);
        color: white;
        border: 1px solid rgba(255, 193, 7, 0.3);
    }
    .status-confirmed {
        background-color: rgba(40, 167, 70, 0.718);
        color: white;
        border: 1px solid rgba(40, 167, 69, 0.3);
    }
    .status-completed {
        background-color: rgba(23, 163, 184, 0.659);
        color: white;
        border: 1px solid rgba(23, 162, 184, 0.3);
    }
    .status-cancelled {
         background-color: rgba(220, 53, 70, 0.653);
        color: white;
        border: 1px solid rgba(220, 53, 69, 0.3);
    }
    .filter-btn {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        transition: all 0.3s ease;
    }
    .filter-btn.active {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.4);
    }
    .filter-btn:hover {
        background: rgba(255, 255, 255, 0.15);
    }
    .search-input {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
    }
    .search-input:focus {
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.4);
        outline: none;
    }
    .search-input::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: rgba(255, 255, 255, 0.7);
    }
    .payment-status-pending {
        color:white;
        background-color: rgba(255, 193, 7, 0.595);
        border: 1px solid rgba(255, 193, 7, 0.3);
    }
    .payment-status-processing {
        color: white;
        background-color: rgba(23, 163, 184, 0.659);
        border: 1px solid rgba(23, 162, 184, 0.3);
    }
    .payment-status-paid {
        color: white;
        background-color: rgba(40, 167, 70, 0.718);
        border: 1px solid rgba(40, 167, 69, 0.3);
    }
    .payment-status-failed {
        color: white;
        background-color: rgba(220, 53, 70, 0.653);
        border: 1px solid rgba(220, 53, 69, 0.3);
    }
    .urgent-booking {
        border-left: 4px solid #ffc107;
        background: rgba(255, 193, 7, 0.05);
    }
    .stats-card {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
    }
</style>
@endpush

@section('content')
<div class="relative pb-16">
    <div id="toast" class="fixed top-5 right-5 bg-white text-black px-4 py-2 rounded shadow-lg hidden z-50"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">Booking Management</h1>
            <p class="text-white/80">Manage your photography sessions and client bookings</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="stats-card rounded-xl p-4 text-center">
                <div class="text-2xl font-bold text-white">{{ $stats['total'] ?? 0 }}</div>
                <div class="text-white/70 text-sm">Total Bookings</div>
            </div>
            <div class="stats-card rounded-xl p-4 text-center">
                <div class="text-2xl font-bold text-yellow-400">{{ $stats['pending'] ?? 0 }}</div>
                <div class="text-white/70 text-sm">Pending</div>
            </div>
            <div class="stats-card rounded-xl p-4 text-center">
                <div class="text-2xl font-bold text-green-400">{{ $stats['confirmed'] ?? 0 }}</div>
                <div class="text-white/70 text-sm">Confirmed</div>
            </div>
            <div class="stats-card rounded-xl p-4 text-center">
                <div class="text-2xl font-bold text-blue-400">{{ $stats['completed'] ?? 0 }}</div>
                <div class="text-white/70 text-sm">Completed</div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="glass-effect rounded-2xl p-6 border border-white/20 mb-8">
            <form method="GET" id="filterForm">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <!-- Status Filters -->
                    <div class="flex flex-wrap gap-2">
                        <button type="button" class="filter-btn {{ request('status', 'all') === 'all' ? 'active' : '' }} px-4 py-2 rounded-lg text-sm font-medium" data-status="all">
                            All Bookings
                        </button>
                        <button type="button" class="filter-btn {{ request('status') === 'pending' ? 'active' : '' }} px-4 py-2 rounded-lg text-sm font-medium" data-status="pending">
                            Pending
                        </button>
                        <button type="button" class="filter-btn {{ request('status') === 'confirmed' ? 'active' : '' }} px-4 py-2 rounded-lg text-sm font-medium" data-status="confirmed">
                            Confirmed
                        </button>
                        <button type="button" class="filter-btn {{ request('status') === 'completed' ? 'active' : '' }} px-4 py-2 rounded-lg text-sm font-medium" data-status="completed">
                            Completed
                        </button>
                        <button type="button" class="filter-btn {{ request('status') === 'cancelled' ? 'active' : '' }} px-4 py-2 rounded-lg text-sm font-medium" data-status="cancelled">
                            Cancelled
                        </button>
                    </div>

                    <!-- Search -->
                    <div class="relative">
                        <input type="text" name="search" id="searchInput" value="{{ request('search') }}" 
                               placeholder="Search by client name or booking number..." 
                               class="search-input pl-10 pr-4 py-2 rounded-lg w-full lg:w-80">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-white/60"></i>
                    </div>
                </div>
                <input type="hidden" name="status" id="statusInput" value="{{ request('status', 'all') }}">
            </form>
        </div>

        <!-- Bookings List -->
        <div id="bookingsList" class="space-y-6">
            @forelse($bookings as $booking)
                @php
                    $isUrgent = \Carbon\Carbon::parse($booking->date)->isToday() || \Carbon\Carbon::parse($booking->date)->isTomorrow();
                @endphp
                
                <div class="booking-card rounded-2xl p-6 {{ $isUrgent && $booking->status === 'confirmed' ? 'urgent-booking' : '' }}" data-status="{{ $booking->status }}">
                    <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
                        <div class="flex items-start gap-4 flex-1">
                            <!-- Client Avatar -->
                            <img src="{{ $booking->user->profile_photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($booking->user->name ?? $booking->client_name) . '&background=667eea&color=fff&size=128' }}" 
                                 alt="{{ $booking->user->name ?? $booking->client_name }}" 
                                 class="w-16 h-16 rounded-full border-2 border-white/30 object-cover flex-shrink-0">
                            
                            <!-- Booking Details -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center flex-wrap gap-3 mb-2">
                                    <h3 class="text-white font-bold text-lg">{{ $booking->package->name }}</h3>
                                    <span class="status-{{ $booking->status }} px-3 py-1 rounded-full text-xs font-medium">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                    <span class="payment-status-{{ $booking->payment_status }} px-3 py-1 rounded-full text-xs font-medium">
                                        Payment: {{ ucfirst($booking->payment_status) }}
                                    </span>
                                    @if($isUrgent && $booking->status === 'confirmed')
                                        <span class="bg-yellow-500/20 text-yellow-400 px-3 py-1 rounded-full text-xs font-medium border border-yellow-500/30">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Upcoming
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Booking Number -->
                                <p class="text-white/60 text-sm font-mono mb-2">
                                    #{{ $booking->booking_number }}
                                </p>
                                
                                <!-- Client Info -->
                                <div class="mb-3">
                                    <p class="text-white/80 font-medium mb-1">
                                        Client: {{ $booking->user->name ?? $booking->client_name }}
                                    </p>
                                    <div class="text-sm text-white/70">
                                        <div class="flex items-center gap-2 mb-1">
                                            <i class="fas fa-phone w-4"></i>
                                            <span>{{ $booking->client_phone }}</span>
                                        </div>
                                        @if($booking->user->email ?? $booking->client_email)
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-envelope w-4"></i>
                                                <span>{{ $booking->user->email ?? $booking->client_email }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Booking Details Grid -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-white/70 mb-3">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-calendar-alt w-4"></i>
                                        <span>{{ \Carbon\Carbon::parse($booking->date)->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-clock w-4"></i>
                                        <span>{{ \Carbon\Carbon::parse($booking->time)->format('g:i A') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-map-marker-alt w-4"></i>
                                        <span>{{ ucfirst($booking->location_type) }} - {{ $booking->location }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-clock w-4"></i>
                                        <span>{{ $booking->package->duration }} hours</span>
                                    </div>
                                </div>
                                
                                <!-- Price -->
                                <div class="mb-3">
                                    <span class="text-white font-bold text-xl">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                                    @if($booking->deposit_amount)
                                        <span class="text-white/60 text-sm ml-2">
                                            (Deposit: Rp {{ number_format($booking->deposit_amount, 0, ',', '.') }})
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Notes -->
                                @if($booking->notes)
                                    <div class="mb-3">
                                        <p class="text-white/60 text-sm">
                                            <i class="fas fa-sticky-note mr-1"></i>
                                            Notes: {{ $booking->notes }}
                                        </p>
                                    </div>
                                @endif
                                
                                <!-- Booking Created -->
                                <div class="text-white/60 text-xs">
                                    <i class="fas fa-calendar-plus mr-1"></i>
                                    Booked on {{ $booking->created_at->format('M d, Y \a\t g:i A') }}
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col sm:flex-row gap-2 lg:flex-col lg:w-48">
                            <!-- View Details (always available) -->
                            <a href="#" 
                               class="px-4 py-2 bg-gradient-secondary text-white rounded-lg hover:opacity-90 transition-opacity text-sm font-medium text-center">
                                <i class="fas fa-eye mr-1"></i> View Details
                            </a>

                            @if($booking->status === 'pending')
                                <!-- Confirm Button -->
                                <button onclick="confirmBooking({{ $booking->id }}, '{{ route('photographer.booking.confirm', $booking->id) }}')"
                                        class=" px-4 py-2 bg-green-500/20 text-green-400 border border-green-500/30 rounded-lg hover:bg-green-500/30 transition-colors text-sm font-medium">
                                    <i class="fas fa-check mr-1"></i> Confirm
                                </button>
                                
                                <!-- Reject Button -->
                                <button onclick="rejectBooking({{ $booking->id }}, '{{ route('photographer.booking.reject', $booking->id) }}')"
                                        class="px-4 py-2 bg-red-500/20 text-red-400 border border-red-500/30 rounded-lg hover:bg-red-500/30 transition-colors text-sm font-medium">
                                    <i class="fas fa-times mr-1"></i> Reject
                                </button>
                            @endif

                            @if(in_array($booking->status, ['confirmed', 'completed']))
                                <!-- Message Client -->
                                <a href="#" 
                                   class="px-4 py-2 bg-blue-500/20 text-blue-400 border border-blue-500/30 rounded-lg hover:bg-blue-500/30 transition-colors text-sm font-medium text-center">
                                    <i class="fas fa-message mr-1"></i> Message
                                </a>
                            @endif

                            @if($booking->status === 'confirmed')
                                <!-- Mark as Completed -->
                                <button onclick="completeBooking({{ $booking->id }}, '{{ route('photographer.booking.complete', $booking->id) }}')" 
                                        class="px-4 py-2 bg-purple-500/20 text-purple-400 border border-purple-500/30 rounded-lg hover:bg-purple-500/30 transition-colors text-sm font-medium">
                                    <i class="fas fa-check-circle mr-1"></i> Complete
                                </button>
                            @endif

                            @if($booking->status === 'completed' && $booking->payment && $booking->payment_status === 'paid')
                                <!-- Upload Photos -->
                                <a href="#" 
                                   class="px-4 py-2 bg-purple-500/20 text-purple-400 border border-purple-500/30 rounded-lg hover:bg-purple-500/30 transition-colors text-sm font-medium text-center">
                                    <i class="fas fa-upload mr-1"></i> Upload Photos
                                </a>
                                
                                <!-- View Reviews -->
                                <a href="#" 
                                   class="px-4 py-2 bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 rounded-lg hover:bg-yellow-500/30 transition-colors text-sm font-medium text-center">
                                    <i class="fas fa-star mr-1"></i> View Review
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <!-- Empty State -->
                <div class="glass-effect rounded-2xl border border-white/20 empty-state">
                    <i class="fas fa-calendar-check text-6xl mb-4 text-white/40"></i>
                    <h3 class="text-xl font-bold text-white mb-2">No bookings found</h3>
                    <p class="text-white/60 mb-6">
                        @if(request('search') || request('status', 'all') !== 'all')
                            No bookings match your search criteria. Try adjusting your filters.
                        @else
                            You don't have any bookings yet. Share your packages to start receiving bookings!
                        @endif
                    </p>
                    <a href="#" 
                       class="inline-block px-6 py-3 bg-gradient-secondary text-white rounded-lg hover:opacity-90 transition-opacity font-medium">
                        <i class="fas fa-camera mr-2"></i>Manage Packages
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        {{-- @if($bookings->hasPages())
            <div class="mt-8 flex justify-center">
                <div class="glass-effect rounded-lg border border-white/20 p-4">
                    {{ $bookings->appends(request()->query())->links() }}
                </div>
            </div>
        @endif --}}
    </div>
</div>

<!-- Confirm Booking Modal -->
<div id="confirmModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="glass-effect rounded-2xl border border-white/20 p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-white mb-4">Confirm Booking</h3>
        <p class="text-white/80 mb-4">Are you sure you want to confirm this booking? The client will be notified.</p>
        
        <form id="confirmForm" method="POST">
            @csrfc
            @method('PATCH')
            <div class="mb-4">
                <label class="block text-white/80 text-sm font-medium mb-2">Confirmation message (optional)</label>
                <textarea name="confirmation_message" rows="3" class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/60 focus:outline-none focus:border-white/40" placeholder="Add any additional details for the client..."></textarea>
            </div>
            
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeConfirmModal()" 
                        class="px-4 py-2 border border-white/30 text-white rounded-lg hover:bg-white/10 transition-colors">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                    Confirm Booking
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Booking Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="glass-effect rounded-2xl border border-white/20 p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-white mb-4">Reject Booking</h3>
        <p class="text-white/80 mb-4">Please provide a reason for rejecting this booking. The client will be notified.</p>
        
        <form id="rejectForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label class="block text-white/80 text-sm font-medium mb-2">Reason for rejection <span class="text-red-400">*</span></label>
                <textarea name="rejection_reason" rows="3" required class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/60 focus:outline-none focus:border-white/40" placeholder="Please explain why you're rejecting this booking..."></textarea>
            </div>
            
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeRejectModal()" 
                        class="px-4 py-2 border border-white/30 text-white rounded-lg hover:bg-white/10 transition-colors">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                    Reject Booking
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Complete Booking Modal -->
<div id="completeModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="glass-effect rounded-2xl border border-white/20 p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-white mb-4">Mark as Completed</h3>
        <p class="text-white/80 mb-4">Mark this booking as completed. The client will be able to view photos and leave a review.</p>
        
        <form id="completeForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label class="block text-white/80 text-sm font-medium mb-2">Completion notes (optional)</label>
                <textarea name="completion_notes" rows="3" class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/60 focus:outline-none focus:border-white/40" placeholder="Add any notes about the completed session..."></textarea>
            </div>
            
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeCompleteModal()" 
                        class="px-4 py-2 border border-white/30 text-white rounded-lg hover:bg-white/10 transition-colors">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-colors">
                    Mark Completed
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const searchInput = document.getElementById('searchInput');
    const statusInput = document.getElementById('statusInput');
    const filterForm = document.getElementById('filterForm');

    // Filter functionality
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            // Update hidden input and submit form
            statusInput.value = this.dataset.status;
            filterForm.submit();
        });
    });

    // Search functionality with debounce
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filterForm.submit();
        }, 500);
    });
});

function confirmBooking(bookingId) {
    console.log('Booking ID:', bookingId); // Pastikan ini bukan undefined
    const modal = document.getElementById('confirmModal');
    const form = document.getElementById('confirmForm');
    form.action = `/photographer/bookings-confirm/${bookingId}`;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}


function closeConfirmModal() {
    const modal = document.getElementById('confirmModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function rejectBooking(bookingId) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    
    form.action = `/photographer/bookings-reject/${bookingId}`;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function completeBooking(bookingId) {
    const modal = document.getElementById('completeModal');
    const form = document.getElementById('completeForm');
    
    form.action = `/photographer/bookings-complete/${bookingId}`;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeCompleteModal() {
    const modal = document.getElementById('completeModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Handle form submissions
function handleFormSubmission(formId) {
    document.getElementById(formId).addEventListener('submit', function(e) {
        e.preventDefault();
        closeConfirmModal();
        closeRejectModal();
        closeCompleteModal();

        const form = this;
        const formData = new FormData(form);

        // Menambahkan spoof method PATCH jika diperlukan
        if (!formData.has('_method')) {
            formData.append('_method', 'PATCH');
        }

        // Show loading feedback
        showToast('Processing...', 'gray');

        fetch(form.action, {
            method: 'POST', // Tetap POST, spoof PATCH dari _method
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('HTTP error ' + response.status);
            return response.json();
        })
        // Ganti Swal dengan showToast
        .then(data => {
            if (data.success) {
                showToast(data.message || 'Berhasil!', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.message || 'Gagal memproses.', 'error');
            }
        })
        .catch(error => {
            console.error(error);
            showToast('Terjadi kesalahan saat memproses.', 'error');
        });

    });
}

function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    toast.textContent = message;

    // Set warna background berdasarkan tipe
    toast.className = `fixed top-5 right-5 px-4 py-2 rounded shadow-lg z-50 text-white`;
    toast.classList.add(
        type === 'success' ? 'bg-green-500' :
        type === 'error' ? 'bg-red-500' : 
        'bg-gray-500'
    );

    toast.classList.remove('hidden');
    setTimeout(() => {
        toast.classList.add('hidden');
    }, 3000);
}



// Initialize form handlers
handleFormSubmission('confirmForm');
handleFormSubmission('rejectForm');
handleFormSubmission('completeForm');
</script>
@endpush
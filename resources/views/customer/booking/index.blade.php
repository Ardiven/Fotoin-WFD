@extends('layout.app')

@section('title', 'My Bookings')

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
        background: rgba(255, 193, 7, 0.66);
        color: white;
        border: 1px solid rgba(255, 193, 7, 0.3);
    }
    .status-confirmed {
        background: rgba(37, 214, 78, 0.659);
       color: white;
        border: 1px solid rgba(40, 167, 69, 0.3);
    }
    .status-completed {
        background: rgba(23, 163, 184, 0.647);
        color: white;
        border: 1px solid rgba(23, 162, 184, 0.3);
    }
    .status-cancelled {
        background: rgba(220, 53, 70, 0.667);
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
    .booking-count {
        font-size: 0.75rem;
        background: rgba(255, 255, 255, 0.2);
        padding: 0.25rem 0.5rem;
        border-radius: 0.75rem;
        margin-left: 0.5rem;
    }
    .payment-status-pending {
        color: white;
        background-color: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    .payment-status-processing {
        color: white;
        background-color: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .payment-status-completed {
        color: white;
        background-color: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    .payment-status-failed {
        color: white;
        background-color: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
</style>
@endpush

@section('content')
<div class="relative pb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">My Bookings</h1>
            <p class="text-white/80">Manage and track all your photography sessions</p>
            <div class="mt-4">
                <span class="text-white/60">Total: {{ $bookings->total() }} bookings</span>
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
                               placeholder="Search by package name or photographer..." 
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
                <div class="booking-card rounded-2xl p-6" data-status="{{ $booking->status }}">
                    <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
                        <div class="flex items-start gap-4 flex-1">
                            <!-- Photographer Avatar -->
                            <img src="{{ asset("storage/" . $booking->package->user->profile_photo) }}" 
                                 alt="{{ $booking->package->user->name }}" 
                                 class="w-16 h-16 rounded-full border-2 border-white/30 object-cover flex-shrink-0">
                            
                            <!-- Booking Details -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center flex-wrap gap-3 mb-2">
                                    <h3 class="text-white font-bold text-lg">{{ $booking->package->name }}</h3>
                                    <span class="status-{{ $booking->status }} px-3 py-1 rounded-full text-xs font-medium">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                    @if($booking->payment)
                                    <span class="payment-status-{{ $booking->payment->status }} px-3 py-1 rounded-full text-xs font-medium">
                                        Payment: {{ ucfirst($booking->payment->status) }}
                                    </span>
                                    @else
                                    <span class="payment-status-{{ 'pending' }} px-3 py-1 rounded-full text-xs font-medium">
                                        Payment: {{ 'pending' }}
                                    </span>
                                    @endif
                                </div>
                                
                                <!-- Booking Number -->
                                <p class="text-white/60 text-sm font-mono mb-2">
                                    #{{ $booking->booking_number }}
                                </p>
                                
                                <!-- Photographer Info -->
                                <p class="text-white/80 font-medium mb-3">
                                    Photographer: {{ $booking->package->user->name }}
                                </p>
                                
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
                                
                                <!-- Client Info -->
                                <div class="text-sm text-white/70 mb-3">
                                    <div class="flex items-center gap-2 mb-1">
                                        <i class="fas fa-user w-4"></i>
                                        <span>{{ $booking->client_name }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-phone w-4"></i>
                                        <span>{{ $booking->client_phone }}</span>
                                    </div>
                                </div>
                                
                                <!-- Price -->
                                <div class="mb-3">
                                    <span class="text-white font-bold text-xl">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
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
                                
                                <!-- Package Description -->
                                @if($booking->package->description)
                                    <div class="text-white/60 text-sm">
                                        <p class="line-clamp-2">{{ $booking->package->description }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col sm:flex-row gap-2 lg:flex-col lg:w-48">
                            <!-- View Details (always available) -->
                            {{-- <a href="{{ route('bookings.show', $booking->id) }}" 
                               class="px-4 py-2 bg-gradient-secondary text-white rounded-lg hover:opacity-90 transition-opacity text-sm font-medium text-center">
                                <i class="fas fa-eye mr-1"></i> View Details
                            </a> --}}

                            @if($booking->status === 'pending')
                                <!-- Cancel Button -->
                                <button onclick="cancelBooking({{ $booking->id }})" 
                                        class="px-4 py-2 bg-red-500/20 text-white border border-red-500/30 rounded-lg hover:bg-red-500/30 transition-colors text-sm font-medium">
                                    <i class="fas fa-times mr-1"></i> Cancel
                                </button>

                            @endif

                            @if(in_array($booking->status, ['confirmed', 'completed']))
                                <!-- Message Photographer -->
                                <a href="#" 
                                   class="px-4 py-2 bg-blue-500/20 text-white border border-blue-500/30 rounded-lg hover:bg-blue-500/30 transition-colors text-sm font-medium text-center">
                                    <i class="fas fa-message mr-1"></i> Message
                                </a>
                            @endif
                            
                            @if(!$booking->payment || $booking->payment->status === 'pending' && !$booking->status === 'cancelled')
                                <!-- Pay Now Button -->
                                <form action="{{ route('customer.booking.pay', $booking) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full px-4 py-2 bg-green-500/20 text-white border border-green-500/30 rounded-lg hover:bg-green-500/30 transition-colors text-sm font-medium">
                                        <i class="fas fa-credit-card mr-1"></i> Pay Now
                                    </button>
                                </form>
                            @endif
                            

                            @if($booking->status === 'completed' && $booking->payment && $booking->payment_status === 'paid')
                                <!-- View Photos -->
                                <a href="#" 
                                   class="px-4 py-2 bg-purple-500/20 text-white border border-purple-500/30 rounded-lg hover:bg-purple-500/30 transition-colors text-sm font-medium text-center">
                                    <i class="fas fa-images mr-1"></i> View Photos
                                </a>
                                
                                <!-- Leave Review -->
                                <a href="#" 
                                   class="px-4 py-2 bg-yellow-500/20 text-white border border-yellow-500/30 rounded-lg hover:bg-yellow-500/30 transition-colors text-sm font-medium text-center">
                                    <i class="fas fa-star mr-1"></i> Leave Review
                                </a>
                            @endif

                            @if($booking->status === 'cancelled')
                                <!-- Book Again -->
                                <a href="{{route('customer.photographers.show', $booking->package->user)}}" 
                                   class="px-4 py-2 bg-green-500/20 text-white border border-green-500/30 rounded-lg hover:bg-green-500/30 transition-colors text-sm font-medium text-center">
                                    <i class="fas fa-redo mr-1"></i> Book Again
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <!-- Empty State -->
                <div class="glass-effect rounded-2xl border border-white/20 empty-state">
                    <i class="fas fa-camera text-6xl mb-4 text-white/40"></i>
                    <h3 class="text-xl font-bold text-white mb-2">No bookings found</h3>
                    <p class="text-white/60 mb-6">
                        @if(request('search') || request('status', 'all') !== 'all')
                            No bookings match your search criteria. Try adjusting your filters.
                        @else
                            You haven't made any bookings yet. Start exploring our talented photographers!
                        @endif
                    </p>
                    <a href="{{ route('customer.photographers') }}" 
                       class="inline-block px-6 py-3 bg-gradient-secondary text-white rounded-lg hover:opacity-90 transition-opacity font-medium">
                        <i class="fas fa-search mr-2"></i>Find Photographers
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($bookings->hasPages())
            <div class="mt-8 flex justify-center">
                <div class="glass-effect rounded-lg border border-white/20 p-4">
                    {{ $bookings->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Cancel Booking Modal -->
<div id="cancelModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="glass-effect rounded-2xl border border-white/20 p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-white mb-4">Cancel Booking</h3>
        <p class="text-white/80 mb-4">Are you sure you want to cancel this booking? This action cannot be undone.</p>
        
        <form id="cancelForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label class="block text-white/80 text-sm font-medium mb-2">Reason for cancellation (optional)</label>
                <textarea name="cancellation_reason" rows="3" class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/60 focus:outline-none focus:border-white/40" placeholder="Please tell us why you're cancelling..."></textarea>
            </div>
            
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeCancelModal()" 
                        class="px-4 py-2 border border-white/30 text-white rounded-lg hover:bg-white/10 transition-colors">
                    Keep Booking
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                    Cancel Booking
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

function cancelBooking(bookingId) {
    const modal = document.getElementById('cancelModal');
    const form = document.getElementById('cancelForm');
    
    form.action = `/bookings/${bookingId}/cancel`;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeCancelModal() {
    const modal = document.getElementById('cancelModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Handle cancel form submission
document.getElementById('cancelForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'An error occurred while cancelling the booking.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while cancelling the booking.');
    });
});
</script>
@endpush
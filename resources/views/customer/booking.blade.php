@extends('layout.app')

@section('title', 'Booking')
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
        .form-input {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            transition: all 0.3s ease;
        }
        .form-input:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.4);
            outline: none;
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.1);
        }
        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        .form-input option {
            background: #764ba2;
            color: white;
        }
        .photographer-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .package-selected {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.5);
        }
        .error-message {
            color: #ff6b6b;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        .success-message {
            color: #51cf66;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>
@endpush

@section('content')
    <!-- Back Button -->
    <div class="relative pt-8 pb-4">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="javascript:history.back()" class="inline-flex items-center text-white/80 hover:text-white transition-colors duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Photographer Profile
            </a>
        </div>
    </div>

    <div class="relative pb-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-white mb-2">Book Your Session</h1>
                <p class="text-white/80">Complete your booking details to secure your photoshoot</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Booking Form -->
                <div class="lg:col-span-2">
                    <div class="glass-effect rounded-2xl p-8 border border-white/20">
                        <form id="bookingForm" action="{{ route('customer.bookings.store', $package) }}" method="POST" class="space-y-6">
                            @csrf
                            
                            <!-- Hidden fields for photographer and package -->
                            <input type="hidden" name="photographer_id" value="{{ $photographer->id }}">
                            <input type="hidden" name="package_id" value="{{ $package->id }}">
                            
                            <!-- Date Selection -->
                            <div>
                                <label for="date" class="block text-white font-medium mb-2">
                                    <i class="fas fa-calendar-alt mr-2"></i>Select Date
                                </label>
                                <input type="date" id="date" name="date" 
                                       class="w-full px-4 py-3 rounded-lg form-input @error('date') border-red-500 @enderror" 
                                       value="{{ old('date') }}" required>
                                @error('date')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Time Selection -->
                            <div>
                                <label for="time" class="block text-white font-medium mb-2">
                                    <i class="fas fa-clock mr-2"></i>Select Time
                                </label>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    <button type="button" class="time-slot px-4 py-2 rounded-lg border border-white/20 text-white hover:bg-white/10 transition-all duration-300" data-time="09:00">09:00</button>
                                    <button type="button" class="time-slot px-4 py-2 rounded-lg border border-white/20 text-white hover:bg-white/10 transition-all duration-300" data-time="10:00">10:00</button>
                                    <button type="button" class="time-slot px-4 py-2 rounded-lg border border-white/20 text-white hover:bg-white/10 transition-all duration-300" data-time="11:00">11:00</button>
                                    <button type="button" class="time-slot px-4 py-2 rounded-lg border border-white/20 text-white hover:bg-white/10 transition-all duration-300" data-time="13:00">13:00</button>
                                    <button type="button" class="time-slot px-4 py-2 rounded-lg border border-white/20 text-white hover:bg-white/10 transition-all duration-300" data-time="14:00">14:00</button>
                                    <button type="button" class="time-slot px-4 py-2 rounded-lg border border-white/20 text-white hover:bg-white/10 transition-all duration-300" data-time="15:00">15:00</button>
                                    <button type="button" class="time-slot px-4 py-2 rounded-lg border border-white/20 text-white hover:bg-white/10 transition-all duration-300" data-time="16:00">16:00</button>
                                    <button type="button" class="time-slot px-4 py-2 rounded-lg border border-white/20 text-white hover:bg-white/10 transition-all duration-300" data-time="17:00">17:00</button>
                                </div>
                                <input type="hidden" id="selectedTime" name="time" required>
                            </div>
                            <!-- Location Type -->
                            <div>
                                <label for="locationType" class="block text-white font-medium mb-2">
                                    <i class="fas fa-map-marker-alt mr-2"></i>Location Type
                                </label>
                                <select id="locationType" name="location_type" 
                                        class="w-full px-4 py-3 rounded-lg form-input @error('location_type') border-red-500 @enderror" required>
                                    <option value="">Choose Location Type</option>
                                    <option value="studio" {{ old('location_type') == 'studio' ? 'selected' : '' }}>Studio</option>
                                    <option value="outdoor" {{ old('location_type') == 'outdoor' ? 'selected' : '' }}>Outdoor</option>
                                    <option value="client_home" {{ old('location_type') == 'client_home' ? 'selected' : '' }}>Client's Home</option>
                                    <option value="venue" {{ old('location_type') == 'venue' ? 'selected' : '' }}>Event Venue</option>
                                </select>
                                @error('location_type')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Specific Location -->
                            <div>
                                <label for="location" class="block text-white font-medium mb-2">
                                    <i class="fas fa-location-dot mr-2"></i>Specific Location
                                </label>
                                <input type="text" id="location" name="location" 
                                       placeholder="Enter detailed address or location name" 
                                       class="w-full px-4 py-3 rounded-lg form-input @error('location') border-red-500 @enderror" 
                                       value="{{ old('location') }}" required>
                                @error('location')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Additional Notes -->
                            <div>
                                <label for="notes" class="block text-white font-medium mb-2">
                                    <i class="fas fa-sticky-note mr-2"></i>Additional Notes (Optional)
                                </label>
                                <textarea id="notes" name="notes" rows="4" 
                                          placeholder="Any special requirements or additional information..." 
                                          class="w-full px-4 py-3 rounded-lg form-input resize-none @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Contact Information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="clientName" class="block text-white font-medium mb-2">
                                        <i class="fas fa-user mr-2"></i>Your Name
                                    </label>
                                    <input type="text" id="clientName" name="client_name" 
                                           placeholder="Enter your full name" 
                                           class="w-full px-4 py-3 rounded-lg form-input @error('client_name') border-red-500 @enderror" 
                                           value="{{ old('client_name') }}" required>
                                    @error('client_name')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label for="clientPhone" class="block text-white font-medium mb-2">
                                        <i class="fas fa-phone mr-2"></i>Phone Number
                                    </label>
                                    <input type="tel" id="clientPhone" name="client_phone" 
                                           placeholder="Enter your phone number" 
                                           class="w-full px-4 py-3 rounded-lg form-input @error('client_phone') border-red-500 @enderror" 
                                           value="{{ old('client_phone') }}" required>
                                    @error('client_phone')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4">
                                <button type="submit" id="submitBtn" class="w-full bg-gradient-secondary hover:opacity-90 text-white font-bold py-4 px-6 rounded-lg transition-all duration-300 text-lg">
                                    <i class="fas fa-credit-card mr-2"></i>
                                    Proceed to Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Booking Summary -->
                <div class="space-y-6">
                    <!-- Photographer Info -->
                    <div class="photographer-card rounded-2xl p-6">
                        <div class="text-center mb-4">
                            <img src="{{ $photographer->avatar ?? 'https://res.cloudinary.com/djv4xa6wu/image/upload/v1735722163/AbhirajK/Abhirajk%20mykare.webp' }}" 
                                 alt="Photographer" class="w-20 h-20 rounded-full mx-auto mb-3 border-2 border-white/30 object-cover">
                            <h3 id="photographerName" class="text-white font-bold text-lg">{{ $photographer->name }}</h3>
                            <div class="flex items-center justify-center mt-2">
                                <div class="flex text-yellow-400 text-sm mr-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= ($photographer->rating ?? 5) ? '' : '-o' }}"></i>
                                    @endfor
                                </div>
                                <span class="text-white/80 text-sm">{{ $photographer->rating ?? '4.9' }} ({{ $photographer->reviews_count ?? '127' }} reviews)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Package -->
                    <div class="package-selected rounded-2xl p-6">
                        <h3 class="text-white font-bold text-lg mb-4">Selected Package</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span id="packageName" class="text-white font-medium">{{ $package->name }}</span>
                            </div>
                            <div class="text-white/80 text-sm" id="packageDescription">
                                {{ $package->description }}
                            </div>
                            <div class="border-t border-white/20 pt-3">
                                <div class="flex justify-between items-center text-lg">
                                    <span id="packagePrice" class="text-white font-bold">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Summary -->
                    <div class="glass-effect rounded-2xl p-6 border border-white/20">
                        <h3 class="text-white font-bold text-lg mb-4">Booking Summary</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-white/80">Date:</span>
                                <span id="summaryDate" class="text-white">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-white/80">Time:</span>
                                <span id="summaryTime" class="text-white">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-white/80">Location:</span>
                                <span id="summaryLocation" class="text-white">-</span>
                            </div>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="glass-effect rounded-2xl p-6 border border-white/20">
                        <div class="grid grid-cols-1 gap-4 text-center text-sm">
                            <div>
                                <div class="text-2xl font-bold text-white">1,200+</div>
                                <div class="text-white/70">Fotografer Aktif</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-white">24+</div>
                                <div class="text-white/70">Kota di Indonesia</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-white">15K+</div>
                                <div class="text-white/70">Momen Terabadikan</div>
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
            // Get URL parameters to populate photographer and package info
            const urlParams = new URLSearchParams(window.location.search);
            const packageId = urlParams.get('package');
            const photographerId = urlParams.get('photographer');

            // Sample data - replace with actual data from your backend
            const packages = {
                '1': {
                    name: 'Basic Session',
                    price: 'Rp 500.000',
                    description: 'Basic portrait session with 5 edited photos'
                },
                '2': {
                    name: 'Premium Session', 
                    price: 'Rp 1.200.000',
                    description: 'Professional portrait session with 15 edited photos'
                },
                '3': {
                    name: 'Wedding Package',
                    price: 'Rp 3.500.000', 
                    description: 'Complete wedding coverage with full gallery'
                }
            };

            const photographers = {
                '1': {
                    name: 'Alex Johnson',
                    rating: '4.9',
                    reviews: '127'
                },
                '2': {
                    name: 'Jane Smith',
                    rating: '4.8', 
                    reviews: '89'
                }
            };

            // Populate package info if available
            if (packageId && packages[packageId]) {
                document.getElementById('packageName').textContent = packages[packageId].name;
                document.getElementById('packagePrice').textContent = packages[packageId].price;
                document.getElementById('packageDescription').textContent = packages[packageId].description;
            }

            // Populate photographer info if available
            if (photographerId && photographers[photographerId]) {
                document.getElementById('photographerName').textContent = photographers[photographerId].name;
            }

            // Time slot selection
            const timeSlots = document.querySelectorAll('.time-slot');
            const selectedTimeInput = document.getElementById('selectedTime');

            timeSlots.forEach(slot => {
                slot.addEventListener('click', function() {
                    // Remove active class from all slots
                    timeSlots.forEach(s => {
                        s.classList.remove('bg-white/20', 'border-white/50');
                        s.classList.add('border-white/20');
                    });
                    
                    // Add active class to selected slot
                    this.classList.add('bg-white/20', 'border-white/50');
                    this.classList.remove('border-white/20');
                    
                    // Set hidden input value
                    selectedTimeInput.value = this.dataset.time;
                    
                    // Update summary
                    document.getElementById('summaryTime').textContent = this.dataset.time;
                });
            });

            // Real-time summary updates
            document.getElementById('date').addEventListener('change', function() {
                const date = new Date(this.value);
                const formattedDate = date.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                document.getElementById('summaryDate').textContent = formattedDate;
            });

            document.getElementById('location').addEventListener('input', function() {
                const location = this.value || '-';
                document.getElementById('summaryLocation').textContent = location.length > 20 ? 
                    location.substring(0, 20) + '...' : location;
            });

            // Form submission
            document.getElementById('bookingForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate time selection
                if (!selectedTimeInput.value) {
                    alert('Please select a time slot');
                    return;
                }

                // Simulate form submission
                const submitButton = this.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;
                
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
                submitButton.disabled = true;
                
                setTimeout(() => {
                    alert('Booking submitted successfully! Redirecting to payment...');
                    // Here you would redirect to payment page
                    // window.location.href = '/payment';
                    
                    // Reset button for demo
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                }, 2000);
            });

            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('date').setAttribute('min', today);
        });
    </script>
@endpush
<!-- Add Portfolio Modal -->
<div id="addPortfolioModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity bg-black bg-opacity-75 backdrop-blur-sm" 
             onclick="closeModal('addPortfolioModal')"></div>

        <!-- Modal positioning -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <!-- Modal content -->
        <div class="inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white/10 backdrop-blur-lg shadow-xl rounded-2xl border border-white/20">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-500/20 rounded-lg mr-3">
                        <i class="fas fa-plus text-blue-400 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-white">Add New Portfolio Project</h3>
                        <p class="text-white/60 text-sm">Share your amazing work with the community</p>
                    </div>
                </div>
                <button type="button" 
                        onclick="closeModal('addPortfolioModal')"
                        class="text-white/60 hover:text-white transition-colors p-2 hover:bg-white/10 rounded-lg">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Form -->
            <form method="POST" action="{{ route('photographer.portfolio.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Title -->
                <div>
                    <label for="title" class="block text-white text-sm font-medium mb-2">
                        <i class="fas fa-heading mr-2"></i>Project Title *
                    </label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           required
                           placeholder="Enter your project title"
                           class="w-full px-4 py-3 form-input rounded-lg focus:ring-2 focus:ring-blue-500"
                           value="{{ old('title') }}">
                    @error('title')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-white text-sm font-medium mb-2">
                        <i class="fas fa-align-left mr-2"></i>Description
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="4"
                              placeholder="Describe your project, the challenges you solved, and the impact it made..."
                              class="w-full px-4 py-3 form-input rounded-lg focus:ring-2 focus:ring-blue-500 resize-vertical">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Specialty -->
                <div>
                    <label for="specialty_id" class="block text-white text-sm font-medium mb-2">
                        <i class="fas fa-tags mr-2"></i>Specialty *
                    </label>
                    <select id="specialty_id" 
                            name="specialty_id" 
                            required
                            class="w-full px-4 py-3 form-input rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Choose a specialty</option>
                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty->id }}" {{ old('specialty_id') == $specialty->id ? 'selected' : '' }}>
                                {{ $specialty->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('specialty_id')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Technologies -->
                

                <!-- Image Upload -->
                <div>
                    <label for="image" class="block text-white text-sm font-medium mb-2">
                        <i class="fas fa-image mr-2"></i>Project Image
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-white/20 border-dashed rounded-lg hover:border-white/40 transition-colors">
                        <div class="space-y-1 text-center">
                            <!-- Image Preview -->
                            <div id="imagePreviewContainer" class="hidden mb-4">
                                <img id="imagePreview" class="mx-auto h-32 w-auto rounded-lg shadow-lg" alt="Preview">
                            </div>
                            
                            <!-- Upload Icon -->
                            <div id="uploadIcon">
                                <i class="fas fa-cloud-upload-alt text-white/40 text-3xl mb-3"></i>
                                <div class="flex text-sm text-white/60">
                                    <label for="image" class="relative cursor-pointer bg-white/10 rounded-md p-2 hover:bg-white/20 transition-colors">
                                        <span class="text-blue-400 font-medium">Upload a file</span>
                                        <input id="image" 
                                               name="image" 
                                               type="file" 
                                               accept="image/*"
                                               class="sr-only"
                                               onchange="previewImage(this, 'imagePreview')">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-white/50">PNG, JPG, GIF up to 2MB</p>
                            </div>
                        </div>
                    </div>
                    @error('image')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Project Details -->
                

                <!-- Additional Options -->
                <div class="space-y-3">
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="is_featured" 
                               name="is_featured" 
                               value="1"
                               {{ old('is_featured') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-500 bg-white/10 border-white/30 rounded focus:ring-blue-500 focus:ring-2">
                        <label for="is_featured" class="ml-2 text-white text-sm">
                            <i class="fas fa-star mr-2 text-yellow-400"></i>Mark as Featured Project
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="is_public" 
                               name="is_public" 
                               value="1"
                               {{ old('is_public', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-500 bg-white/10 border-white/30 rounded focus:ring-blue-500 focus:ring-2">
                        <label for="is_public" class="ml-2 text-white text-sm">
                            <i class="fas fa-globe mr-2 text-green-400"></i>Make Public (Visible to everyone)
                        </label>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3 pt-6 border-t border-white/20">
                    <button type="button" 
                            onclick="closeModal('addPortfolioModal')"
                            class="w-full sm:w-auto px-6 py-3 bg-white/10 hover:bg-white/20 text-white rounded-lg transition-colors">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </button>
                    <button type="submit" 
                            class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white rounded-lg transition-all transform hover:scale-105">
                        <i class="fas fa-plus mr-2"></i>Add Portfolio Project
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Additional Scripts for Add Modal -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview functionality
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        const previewContainer = document.getElementById('imagePreviewContainer');
        const uploadIcon = document.getElementById('uploadIcon');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.classList.remove('hidden');
                uploadIcon.classList.add('hidden');
            };
            
            reader.readAsDataURL(input.files[0]);
        } else {
            previewContainer.classList.add('hidden');
            uploadIcon.classList.remove('hidden');
        }
    }

    // Make previewImage globally available
    window.previewImage = previewImage;

    // Auto-hide success messages
    const successMessage = document.querySelector('.alert-success');
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.opacity = '0';
            setTimeout(() => {
                successMessage.remove();
            }, 300);
        }, 5000);
    }

    // Form validation enhancements
    const form = document.querySelector('#addPortfolioModal form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const title = document.getElementById('title').value.trim();
            const specialtyId = document.getElementById('specialty_id').value;

            if (!title) {
                e.preventDefault();
                alert('Please enter a project title.');
                document.getElementById('title').focus();
                return;
            }

            if (!specialtyId) {
                e.preventDefault();
                alert('Please select a specialty.');
                document.getElementById('specialty_id').focus();
                return;
            }

            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating Project...';
            }
        });
    }

    // Drag and drop functionality for image upload
    const dropZone = document.querySelector('#addPortfolioModal .border-dashed');
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
            dropZone.classList.add('border-blue-400', 'bg-blue-500/10');
        }

        function unhighlight(e) {
            dropZone.classList.remove('border-blue-400', 'bg-blue-500/10');
        }

        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files.length > 0) {
                const imageInput = document.getElementById('image');
                imageInput.files = files;
                previewImage(imageInput, 'imagePreview');
            }
        }
    }

    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('addPortfolioModal');
            if (modal && !modal.classList.contains('hidden')) {
                closeModal('addPortfolioModal');
            }
        }
    });
});

// Close modal function
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        // Reset form if it exists
        const form = modal.querySelector('form');
        if (form) {
            form.reset();
            // Hide image preview
            const previewContainer = document.getElementById('imagePreviewContainer');
            const uploadIcon = document.getElementById('uploadIcon');
            if (previewContainer && uploadIcon) {
                previewContainer.classList.add('hidden');
                uploadIcon.classList.remove('hidden');
            }
        }
    }
}
</script>

<style>
/* Additional styles for the modal */
#addPortfolioModal .form-input {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: white;
    backdrop-filter: blur(10px);
}

#addPortfolioModal .form-input:focus {
    outline: none;
    border-color: rgba(59, 130, 246, 0.5);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

#addPortfolioModal .form-input::placeholder {
    color: rgba(255, 255, 255, 0.5);
}

#addPortfolioModal select.form-input option {
    background: #1f2937;
    color: white;
}

/* Drag and drop animation */
#addPortfolioModal .border-dashed {
    transition: all 0.3s ease;
}

/* Checkbox styling */
#addPortfolioModal input[type="checkbox"] {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

#addPortfolioModal input[type="checkbox"]:checked {
    background: rgb(59, 130, 246);
    border-color: rgb(59, 130, 246);
}

/* Date input styling */
#addPortfolioModal input[type="date"] {
    color-scheme: dark;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    #addPortfolioModal .inline-block {
        margin: 1rem;
        width: calc(100% - 2rem);
    }
}
</style>
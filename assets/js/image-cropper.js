/**
 * Image Cropper Script for Career Portal
 * Uses Cropper.js to provide image cropping functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const imageInput = document.getElementById('profile_image');
    const previewContainer = document.getElementById('imagePreview');
    const previewImage = document.getElementById('preview');
    const cropButton = document.getElementById('cropButton');
    const cropModal = document.getElementById('cropModal');
    const cropperImage = document.getElementById('cropperImage');
    const saveCropButton = document.getElementById('saveCrop');
    const cancelCropButton = document.getElementById('cancelCrop');
    const croppedImageInput = document.getElementById('croppedImage');
    
    let cropper;
    
    // Initialize cropper when image is selected
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    // Set the image in the cropper
                    cropperImage.src = e.target.result;
                    
                    // Show the modal
                    cropModal.style.display = 'block';
                    
                    // Initialize cropper
                    if (cropper) {
                        cropper.destroy();
                    }
                    
                    // Initialize with a square aspect ratio
                    cropper = new Cropper(cropperImage, {
                        aspectRatio: 1,
                        viewMode: 1,
                        dragMode: 'move',
                        autoCropArea: 1,
                        restore: false,
                        guides: true,
                        center: true,
                        highlight: false,
                        cropBoxMovable: true,
                        cropBoxResizable: true,
                        toggleDragModeOnDblclick: false
                    });
                };
                
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    }
    
    // Save the cropped image
    if (saveCropButton) {
        saveCropButton.addEventListener('click', function() {
            if (cropper) {
                // Get the cropped canvas as a perfect square
                const cropData = cropper.getData();
                const size = Math.min(cropData.width, cropData.height);
                
                // Ensure the canvas is a perfect square with consistent dimensions
                const canvas = cropper.getCroppedCanvas({
                    width: 300,
                    height: 300,
                    minWidth: 300,
                    minHeight: 300,
                    maxWidth: 1000,
                    maxHeight: 1000,
                    fillColor: '#fff',
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high',
                });
                
                // Convert canvas to blob
                canvas.toBlob(function(blob) {
                    // Update the preview image
                    previewImage.src = URL.createObjectURL(blob);
                    previewContainer.style.display = 'block';
                    
                    // Store the cropped image data in the hidden input
                    // This ensures the cropped image is submitted with the form
                    // even if the DataTransfer API is not supported
                    if (croppedImageInput) {
                        croppedImageInput.value = canvas.toDataURL('image/jpeg');
                    }
                    
                    // Browser compatibility check for DataTransfer API
                    try {
                        // Create a new File object
                        const file = new File([blob], 'cropped-image.jpg', {
                            type: 'image/jpeg',
                            lastModified: new Date().getTime()
                        });
                        
                        // Check if DataTransfer is supported
                        if (window.DataTransfer) {
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file);
                            imageInput.files = dataTransfer.files;
                        }
                    } catch (e) {
                        console.log('Browser does not support DataTransfer API, falling back to base64');
                        // The form will still work using the base64 data in croppedImageInput
                    }
                    
                    // Hide the modal
                    cropModal.style.display = 'none';
                    
                    // Destroy the cropper
                    cropper.destroy();
                    cropper = null;
                }, 'image/jpeg', 0.95);
            }
        });
    }
    
    // Cancel cropping
    if (cancelCropButton) {
        cancelCropButton.addEventListener('click', function() {
            // Clear the input
            imageInput.value = '';
            
            // Hide the modal
            cropModal.style.display = 'none';
            
            // Destroy the cropper
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        });
    }
    
    // Close the modal when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target === cropModal) {
            // Clear the input
            imageInput.value = '';
            
            // Hide the modal
            cropModal.style.display = 'none';
            
            // Destroy the cropper
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        }
    });
});
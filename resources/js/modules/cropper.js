// Importing Cropper.js
import Cropper from 'cropperjs';



// Your custom code to initialize Cropper.js
document.addEventListener('DOMContentLoaded', () => {
	const image = document.getElementById('image-to-crop');
	let cropper;
	
	if (image) {
		cropper = new Cropper(image, {
			aspectRatio: 16 / 9,   // Aspect ratio for the crop box
			viewMode: 1,           // Control the mode of the cropper (e.g., 0: normal, 1: restricted, etc.)
			autoCropArea: 0.5,     // Set the initial area of the crop box
			responsive: true,      // Enable responsive mode
			background: true,     // Show background
		});
	}
	
	// Handle the cropping event or any other custom event
	document.getElementById('crop-image-btn').addEventListener('click', () => {
		if (cropper) {
			const canvas = cropper.getCroppedCanvas();
			const croppedImage = canvas.toDataURL();
			document.getElementById('cropped-image-preview').innerHTML = `<img src="${croppedImage}" alt="Cropped Image" />`;
		}
	});
});
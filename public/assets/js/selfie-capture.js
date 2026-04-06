/**
 * Selfie Capture Utility
 * Handles webcam access and frame capture for registration forms.
 */

window.SelfieCapture = {
    stream: null,
    video: null,
    canvas: null,
    input: null,
    preview: null,

    init: function(videoSelector, canvasSelector, inputSelector, previewSelector) {
        this.video = document.querySelector(videoSelector);
        this.canvas = document.querySelector(canvasSelector);
        this.input = document.querySelector(inputSelector);
        this.preview = document.querySelector(previewSelector);

        if (!this.video || !this.canvas || !this.input) {
            console.error('SelfieCapture: Required elements not found.');
            return;
        }
    },

    startCamera: async function() {
        try {
            this.stream = await navigator.mediaDevices.getUserMedia({ 
                video: { facingMode: "user" }, 
                audio: false 
            });
            this.video.srcObject = this.stream;
            this.video.play();
            return true;
        } catch (err) {
            console.error("Error accessing webcam: ", err);
            alert("Could not access camera. Please ensure you have given permission.");
            return false;
        }
    },

    stopCamera: function() {
        if (this.stream) {
            this.stream.getTracks().forEach(track => track.stop());
        }
    },

    capture: function() {
        const context = this.canvas.getContext('2d');
        this.canvas.width = this.video.videoWidth;
        this.canvas.height = this.video.videoHeight;
        context.drawImage(this.video, 0, 0, this.canvas.width, this.canvas.height);
        
        const dataURL = this.canvas.toDataURL('image/png');
        this.input.value = dataURL;
        
        if (this.preview) {
            this.preview.src = dataURL;
            this.preview.style.display = 'block';
            this.video.style.display = 'none';
        }
        
        this.stopCamera();
        return dataURL;
    },

    retake: function() {
        this.input.value = '';
        if (this.preview) {
            this.preview.style.display = 'none';
            this.video.style.display = 'block';
        }
        this.startCamera();
    }
};

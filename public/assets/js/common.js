// number only count hub limit
$(".onlyDigit").keypress(function (e) {
    if (
        e.which != 8 &&
        e.which != 0 &&
        e.which != 43 &&
        e.which != 107 &&
        (e.which < 48 || e.which > 57)
    ) {
        //display error message
        $(".onlyDigit_error").html("Enter Digits Only").show().fadeOut(5000);
        return false;
    }
});


document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('customFile1');
    const imagePreview = document.getElementById('selectedImage');
    const removeBtn = document.getElementById('removeImageBtn');

    fileInput.addEventListener('change', function(event) {
        if (event.target.files.length > 0) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.classList.remove('hidden'); // Show image after upload
                removeBtn.classList.remove('hidden'); // Show remove button after upload
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    });

    removeBtn.addEventListener('click', function() {
        imagePreview.src = ''; // Clear image source
        fileInput.value = null; // Reset file input
        imagePreview.classList.add('hidden'); // Hide image after removal
        removeBtn.classList.add('hidden'); // Hide remove button after removal
    });
});


// number only
$(".onlyDigit").keypress(function (e) {
    if (
        e.which != 8 &&
        e.which != 0 &&
        e.which != 43 &&
        e.which != 107 &&
        (e.which < 48 || e.which > 57)
    ) {
         if (e.key.toLowerCase() == "k") {
             $(".onlyDigit_error")
                 .html("Enter Digits Only")
                 .show()
                 .fadeOut(3000);
             return false;
         }
        //display error message
        $(".onlyDigit_error")
                 .html("Enter Digits Only")
                 .show()
                 .fadeOut(3000);
        return false;
    }
});

$(".onlyDigitSpeed").keypress(function (e) {
    if (
        e.which != 8 &&
        e.which != 0 &&
        e.which != 43 &&
        e.which != 107 &&
        (e.which < 48 || e.which > 57)
    ) {
        if (e.key.toLowerCase() == "k") {
            $(".onlyDigitSpeed_error")
                .html("Enter Digits Only")
                .show()
                .fadeOut(3000);
            return false;
        }

        //display error message
        $(".onlyDigitSpeed_error")
            .html("Enter Digits Only")
            .show()
            .fadeOut(3000);
        return false;
    }
});
$(".onlyDigitRent").keypress(function (e) {
    if (
        e.which != 8 &&
        e.which != 0 &&
        e.which != 43 &&
        e.which != 107 &&
        (e.which < 48 || e.which > 57)
    ) {
        if (e.key.toLowerCase() == "k") {
            $(".onlyDigitRent_error")
                .html("Enter Digits Only")
                .show()
                .fadeOut(3000);
            return false;
        }

        //display error message
        $(".onlyDigitRent_error")
            .html("Enter Digits Only")
            .show()
            .fadeOut(3000);
        return false;
    }
});
$(".onlyDigitMonthly").keypress(function (e) {
    if (
        e.which != 8 &&
        e.which != 0 &&
        e.which != 43 &&
        e.which != 107 &&
        (e.which < 48 || e.which > 57)
    ) {
        if (e.key.toLowerCase() == "k") {
            $(".onlyDigitMonthly_error")
                .html("Enter Digits Only")
                .show()
                .fadeOut(3000);
            return false;
        }

        //display error message
        $(".onlyDigitMonthly_error")
            .html("Enter Digits Only")
            .show()
            .fadeOut(3000);
        return false;
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const fileInputs = document.querySelectorAll(".customFile");
    const imagePreviews = document.querySelectorAll(".selectedImage");
    const removeBtns = document.querySelectorAll(".removeImageBtn");

    fileInputs.forEach(function (fileInput, index) {
        fileInput.addEventListener("change", function (event) {
            const imagePreview = imagePreviews[index];
            const removeBtn = removeBtns[index];

            if (event.target.files.length > 0) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove("hidden"); // Show image after upload
                    removeBtn.classList.remove("hidden"); // Show remove button after upload
                };
                reader.readAsDataURL(event.target.files[0]);
            }
        });
    });

    removeBtns.forEach(function (removeBtn, index) {
        removeBtn.addEventListener("click", function () {
            const imagePreview = imagePreviews[index];
            const fileInput = fileInputs[index];

            imagePreview.src = ""; // Clear image source
            fileInput.value = null; // Reset file input
            imagePreview.classList.add("hidden"); // Hide image after removal
            removeBtn.classList.add("hidden"); // Hide remove button after removal
        });
    });
});

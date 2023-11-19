// number only count hub limit
$("#hub_limit").keypress(function (e) {
    if (
        e.which != 8 &&
        e.which != 0 &&
        e.which != 43 &&
        e.which != 107 &&
        (e.which < 48 || e.which > 57)
    ) {
        //display error message
        $("#hub_errors").html("Enter Digits Only").show().fadeOut(5000);
        return false;
    }
});
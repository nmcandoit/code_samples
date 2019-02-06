/*************************************************************************

                Sample of new password form validation


     Features:
     - Check the criteria that the password must contain
     - Change color of criteria when this is completed
     - enable1/disable submit button of the modal

************************************************************************/

$(document).ready(function () {

    $pwrlength = false;
    $pwrletter = false;
    $pwrnumber = false;

    $pwstruct = false
    $pwsame = false;


    $("#newpw").on('input', function () {

        // Check if the password contain 8 characters or more
        if ($("#newpw").val().length >= 8) {
            $pwrlength = true;
            // console.log("lenght ok");
        } else {
            $pwrlength = false;
        }

        // Check if the password contain letter
        if ($("#newpw").val().match(/[a-z]/i)) {
            $pwrletter = true;
            // console.log("letter ok");
        } else {
            $pwrletter = false;
        }

        // Check if the password contain number
        if ($("#newpw").val().match(/\d+/g)) {
            $pwrnumber = true;
            // console.log("number ok");
        } else {
            $pwrnumber = false;
        }

        if ($pwrlength == true && $pwrletter == true && $pwrnumber == true) {
            // console.log("password ok");
            $pwstruct = true;
        } else {
            $pwstruct = false;
        }

        if ($pwrlength == true) {
            $("#pwrlength").css("color", "#2ecc71");
        } else {
            $("#pwrlength").css("color", "#fd3737");
        }

        if ($pwrletter == true) {
            $("#pwrletter").css("color", "#2ecc71");
        } else {
            $("#pwrletter").css("color", "#fd3737");
        }

        if ($pwrnumber == true) {
            $("#pwrnumber").css("color", "#2ecc71");
        } else {
            $("#pwrnumber").css("color", "#fd3737");
        }

    });

    $("#newpw2").on('input', function () {
        if ($("#newpw").val() != $("#newpw2").val()) {
            $("#pwsame").css("display", "block");
            $pwsame = false;
        } else {
            $("#pwsame").css("display", "none");
            $pwsame = true;
        }

    });

    $("#newpw, #newpw2").on('input', function () {
        if ($pwstruct == true && $pwsame == true) {
            $("#pwsubmit").removeAttr("disabled");
        } else {
            $("#pwsubmit").attr("disabled", "disabled");
        }

    });
});

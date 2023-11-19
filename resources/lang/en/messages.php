<?php

return [
    'INSERT' => 'Data has been successfully added.',
    'INSERT_ERROR' => 'Record Inserting Error! Please try again.',
    'UPDATE' => 'Data has been successfully updated.',
    'DELETE' => 'Data has been successfully deleted.',
    'INCORRECT_EMAIL' => 'Either email or Password is incorrect.',
    'LOGIN_SUCCESS' => 'Login Successfully.',
    'LOGOUT_SUCCESS' => 'Logout Successfully.',
    'UPDATE_IMAGE_DESC' => 'Image description updated successfully.',
    'ERROR_UPDATE_IMAGE_DESC' => 'Image description not updated.',

    'PUBLISH_ERROR' => 'Property not published getting some error.',
    'PUBLISH_SUCCESS' => 'Property published successfully.',
    'SUCCESS' => 'Success.',
    'REGISTER' => "Thanks for signing up. Welcome to our community. We are happy to have you on board.",
    'HTTP_ACCEPTED' => 'Accepted.',
    'MAINTANANCE_MODE' => 'App in maintanace mode.',
    'HTTP_TOO_MANY_REQUESTS' => 'Too many requests',
    'UNAUTHORIZED' => 'Unautorized.',
    'INTERNAL_SERVER_ERROR' => 'Internal server error.',
    'VALIDATION_ERROR' => 'Some request parameters failed for validation.',
    'INVALID_CREDENTIAL' => 'Credentials are invalid.',
    'PROXY_AUTHENTICATION_EQUIRED' => 'Proxy authentication required.',
    'HTTP_NOT_FOUND' => 'Data not found.',
    'FOB_NOT_FOUND' => 'FOB id is not available.',
    'INVALID_REQUEST_METHOD' => "This method is not supported this route.",
    'forget_email' => [
        "SUCCESS_MAIL" => "<br>Please check your mail.<br>Click reset link button for reset your password.<br>",
        'MAIL_ERROR' => "Please insert valid email so that <br> we can sent you reset password link.",
    ],
    'register_user' => [
        "SUCCESS_MAIL" => "Thank you for registration.<br>Please check your mail.<br>Click activate button for activate your account.<br>",
        'MAIL_ERROR' => "Please insert valid email so that <br> we can sent you account activation link.",
    ],
    'REGISTER_INSERT' => "Thank you for registration.<br>Please check your mail.<br>Click activate button for activate your account.<br>",
    "FORGET_SUCCESS_MAIL" => "<br>Please Check Your Mail.<br>Click Reset Link Button For Reset Your Password.<br>",

    'MOBILE_INSERT' => "Your cleaning project has been created and sent to your Cleaner in their Turno app. If you've added a same-day clean, it's always a good idea to do a quick check in with the cleaner to make sure they've received it in their Turno app!",

    'EXPORT' => 'Data has been successfully exported.',
    'IMPORT' => 'Data has been successfully imported.',
    'COPY_DATA' => 'Data has been successfully copied.',
    'SELECT' => 'Data found.',
    'COPY_TEMPLETE' => 'Template has been copied to inactive tab.',
    'CANCEL' => 'Transaction Canceled',

    'REGISTER_MAIL_ERROR' => "Please insert valid email so that <br> we can sent you account activation link.",

    'UPDATE_ERROR' => 'Oops! Something went wrong on our end. Our team has been notified and is working to resolve the issue as soon as possible. Please try again later, or contact support if the problem persists.',
    'COPY_ERROR' => 'Oops! Something went wrong on our end. Our team has been notified and is working to resolve the issue as soon as possible. Please try again later, or contact support if the problem persists.',
    'DELETE_ERROR_PAR' => 'Oops! Something went wrong on our end. Our team has been notified and is working to resolve the issue as soon as possible. Please try again later, or contact support if the problem persists.',
    'DELETE_ERROR_PAR_GUEST' => 'Could not delete, because data used in reservations.',
    'DELETE_ERROR_PAR_RESIDENT' => 'Could not delete, because data used in leases.',
    'NOT_EXITS' => 'Oops! Something went wrong on our end. Our team has been notified and is working to resolve the issue as soon as possible. Please try again later, or contact support if the problem persists.',
    'DELETE_ERROR' => 'Oops! Something went wrong on our end. Our team has been notified and is working to resolve the issue as soon as possible. Please try again later, or contact support if the problem persists.',
    'EXPORT_ERROR' => 'Oops! Something went wrong on our end. Our team has been notified and is working to resolve the issue as soon as possible. Please try again later, or contact support if the problem persists.',
    'IMPORT_ERROR' => 'Oops! Something went wrong on our end. Our team has been notified and is working to resolve the issue as soon as possible. Please try again later, or contact support if the problem persists.',
    'USER_ACTIVATION_PENDING' => 'Your account activation is pending.',
    'USER_APPROVE_PENDING' => 'Currently your account is not approved. Please try later after you get activation email link.',
    'INVALID_CREDENTIAL' => 'Credentials are invalid.',
    'TOKEN_NOT_GENERATED' => 'Password token not generated',
    'UPLOAD_SUCCESS' => 'File uploaded successfully.',
    'UPLOAD_ERROR' => 'Something went wrong. File not Upload.',
    'MAIL_DATA_INSERT' => 'Mail queue data inserted successfully.',
    'MAIL_DATA_NOT_INSERT' => 'Mail queue data not inserted.',
    'PERMISSION_ERROR' => 'Sorry, you are not allowed to access this page.',
    'attempt_failed' => [
        'login_attempt' => "You have blocked for 24 hours due to 3 times wrong login attempt.",
        'wrong_2FA' => 'Your account is blocked for 1 hour due to 3 times invalid attempts.',
        'wrong_credential' => 'Invalid credential, you are left with %s more attempt.',
        'wrong_otp' => 'Invalid code, You are left with %s more attempt.',
        'resend_otp_block' => 'Your account is blocked for 1 hour as you have exhausted maximum allowed resend code request.',
        'resend_otp' => 'You are left with %s more resend code request.',
        'login_no_more_attempt' => 'Invalid credential, no more attempt left.',
        'wrong_otp_no_more_attempt' => 'Invalid code, no more attempt left.',
        'resend_otp_no_more_attempt' => 'You cannot make further resend code request.',
        "no_more_attempt" => "You cannot send further verification code requests due to 3 wrong attempts. Please try after 2 minutes.",
    ],
];
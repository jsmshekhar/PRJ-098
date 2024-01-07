 <!-- JAVASCRIPT -->
 <script src="{{ asset('public/assets/libs/jquery/jquery.min.js') }}"></script>
 <script src="{{ asset('public/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
 <script src="{{ asset('public/assets/libs/metismenu/metisMenu.min.js') }}"></script>
 <script src="{{ asset('public/assets/libs/simplebar/simplebar.min.js') }}"></script>
 <script src="{{ asset('public/assets/libs/node-waves/waves.min.js') }}"></script>
 <script src="{{ asset('public/assets/libs/feather-icons/feather.min.js') }}"></script>
 <!-- pace js -->
 <script src="{{ asset('public/assets/libs/pace-js/pace.min.js') }}"></script>
 <!-- choices js -->
 {{-- <script src="{{ asset('public/assets/libs/choices.js/public/assets/scripts/choices.min.js')}}"></script> --}}
 <!-- apexcharts -->
 <script src="{{ asset('public/assets/libs/apexcharts/apexcharts.min.js') }}"></script>

 <!-- Plugins js-->
 <script src="{{ asset('public/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}">
 </script>
 <script src="{{ asset('public/assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js') }}">
 </script>
 <!-- dashboard init -->
 {{-- <script src="{{ asset('public/assets/js/pages/dashboard.init.js') }}"></script> --}}
 <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
 {{-- <script src="{{ asset('public/assets/js/pages/form-advanced.init.js') }}"></script> --}}
 <script src="{{ asset('public/assets/js/app.js') }}"></script>
 <script src="{{ asset('public/assets/js/common.js') }}"></script>

 <script>
     window.addEventListener('load', () => {
         $('.select2').select2();
     });
     window.addEventListener('load', () => {
         $('.selectBasic').select2({
             minimumResultsForSearch: -1
         });

     });
 </script>

 <script>
     $(document).ready(function() {
         $('#submitUserProfileForm').click(function(e) {
             e.preventDefault();
             var companyName = $('#user_fname').val();
             if (companyName == "") {
                 $(".user_fname_error").html('This field is required!');
                 $("input#user_fname").focus();
                 return false;
             }
             $('#submitUserProfileForm').prop('disabled', true);
             $('#submitUserProfileForm').html('Please wait...')
             var formDatas = new FormData(document.getElementById('updateUserProfile'));
             $.ajax({
                 headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 },
                 method: 'POST',
                 url: "{{ route('update-user-profile') }}",
                 data: formDatas,
                 contentType: false,
                 processData: false,
                 success: function(data) {
                     $('#message').html("<span class='sussecmsg'>" + data.message +
                         "</span>");
                     $('#submitCompanyForm').prop('disabled', false);
                     setTimeout(function() {
                         window.location.reload();
                     }, 1000);

                 },
                 errors: function() {
                     $('#message').html(
                         "<span class='sussecmsg'>Somthing went wrong!</span>");
                 }
             });
         });
     });

     $(document).ready(function() {
         $('#submitCompanyForm').click(function(e) {
             e.preventDefault();
             var companyName = $('#company_name').val();
             if (companyName == "") {
                 $(".company_name_error").html('This field is required!');
                 $("input#company_name").focus();
                 return false;
             }
             var companyAddress = $('#company_address').val();
             if (companyAddress == "") {
                 $(".company_address_errors").html('This field is required!');
                 $("input#company_address").focus();
                 return false;
             }

             $('#submitCompanyForm').prop('disabled', true);
             $('#submitCompanyForm').html('Please wait...')
             var formDatas = new FormData(document.getElementById('companyDetailForm'));
             $.ajax({
                 headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 },
                 method: 'POST',
                 url: "{{ route('update-company-details') }}",
                 data: formDatas,
                 contentType: false,
                 processData: false,
                 success: function(data) {
                     $('#message').html("<span class='sussecmsg'>" + data.message +
                         "</span>");
                     $('#submitCompanyForm').prop('disabled', false);
                     setTimeout(function() {
                         window.location.reload();
                     }, 1000);

                 },
                 errors: function() {
                     $('#message').html(
                         "<span class='sussecmsg'>Somthing went wrong!</span>");
                 }
             });
         });
     });

     $(document).ready(function() {
         $('#submitPasswordForm').click(function(e) {
             e.preventDefault();
             var old_pwd = $('#old_password').val();
             if (old_pwd == "") {
                 $(".old_password_error").html('This field is required!');
                 $("input#old_password").focus();
                 return false;
             }
             var new_pwd = $('#new_password').val();
             if (new_pwd == "") {
                 $(".new_password_error").html('This field is required!');
                 $("input#new_password").focus();
                 return false;
             }
             var cn_pwd = $('#confirm_password').val();
             if (cn_pwd == "") {
                 $(".confirm_password_error").html('This field is required!');
                 $("input#confirm_password").focus();
                 return false;
             }

             if (new_pwd != cn_pwd) {
                 $(".confirm_password_error").html('The password does not match the new password.');
                 $("input#confirm_password").focus();
                 return false;
             }

             $('#submitPasswordForm').prop('disabled', true);
             $('#submitPasswordForm').html('Please wait...')
             var formDatas = new FormData(document.getElementById('passwordChangeForm'));
             $.ajax({
                 headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 },
                 method: 'POST',
                 url: "{{ route('change-password') }}",
                 data: formDatas,
                 contentType: false,
                 processData: false,
                 success: function(data) {
                     $('#message').html("<span class='sussecmsg'>" + data.message +
                         "</span>");
                     $('#submitPasswordForm').prop('disabled', false);
                     setTimeout(function() {
                         window.location.reload();
                     }, 1000);

                 },
                 errors: function() {
                     $('#message').html(
                         "<span class='sussecmsg'>Somthing went wrong!</span>");
                 }
             });
         });
     });
 </script>

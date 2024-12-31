<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Print Den | Login</title>

    <!-- Custom fonts for this template-->
    <link href="{{ url('admin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Poppins:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ url('admin/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link rel="icon" href="{{asset('admin/printden_main.svg')}}" type="image/gif" sizes="16x16">
    <style>
        .sign-button {
            background: #292929;
            width: 50%;
            height: 36px;
            padding: 0;
            font-size: 16px;
            /* Fixing the typo in your code */

            /* Media query for screens with a width of 768px or wider */
            @media (min-width: 768px) {
                width: 300px;
            }
        }

        .bg_color {
            background: #F8F8F8 0% 0% no-repeat padding-box;
            background: #F8F8F8 0% 0% no-repeat padding-box;
        }

        .login_card {
            background: rgb(255, 255, 255);
            border-radius: 1rem;
            box-shadow: 0px 40px 99px #527AB533;
            min-height: 30rem;
            padding: 3rem 3rem 0rem 3rem;
        }
        .tc {
            background: #F7FAFB;
            text-align: center;
            border-radius: 0 0 .5rem .5rem;
            padding: 1rem 0 1rem 0;
        }

        .site_button {
            /* border-color: #000000; */
            /* font-weight: 800; */
            background: #292929;
            font-size: 13px !important;
            border-radius: 6px;
            width: 100%;
            height: 40px;
        }
        .site_button:hover {
                background: #484848 !important;
            }

        .site_button_secondary {
            /* border-color: #000000; */
            /* font-weight: 800;
            font-size: 13px !important;*/
            border-radius: 6px;
            width: 100%;
            border: 1px solid #E4E7EB;
            height: 40px;
        }

        .form-control-user {
            border: none;
            border-bottom: 1px solid rgb(155, 155, 155);
            border-radius: 1px !important;
            height: 39px;
            padding: 0 !important;
        }

        .form-control:focus {
            color: #6e707e;
            background-color: #fff;
            /* border-color: #bac8f3; */
            outline: 0;
            /* box-shadow: 0 0 0 0.1rem rgb(219 219 219 / 25%); */
            box-shadow: 0 0 0 0 rgb(219 219 219 / 25%);
        }

        .custom-control-input:checked~.custom-control-label::before {
            border-color: #000000;
            background-color: #000000;
        }

        .alert-danger {
            background: rgba(255, 255, 255, 0.2) !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1) !important;
            backdrop-filter: blur(10px) !important;
            -webkit-backdrop-filter: blur(5px) !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            color: red;
        }

        .artboard_section {
            color: #292929;
        }

        .artboard_section h6 {
            margin: 2rem 0;
        }

        /* .show_hide_password {
            margin-left: 14rem;

            @media (min-width: 768px) {
                margin-left: 18rem;

            }
        } */
        .separator {
            display: flex;
            align-items: center;
            text-align: center;
            margin-top: 1rem;
            margin-bottom: 1rem;
            font-size: 0.7rem;
        }

        .separator::before,
        .separator::after {
          content: '';
          flex: 1;
          border-bottom: 1px solid #718791;
          opacity: .5;
        }

        .separator:not(:empty)::before {
          margin-right: .5rem;
        }

        .separator:not(:empty)::after {
          margin-left: .5rem;
        }
        .visit-web {
            color: #FFFFFF;
            font-size: .85rem;
            background: #404040;
            padding: 0.5rem 1.75rem;
            border-radius: .5rem;
            border: 1px solid #404040;
        }
        .visit-web:hover {
            color: #FFFFFF;
            background: #2E2424;
            padding: 0.5rem 1.75rem;
            border-radius: .5rem;
            border: 1px solid #2E2424;
        }
    </style>

</head>

<body class="bg_color">


    <div class="container-fluid min-vh-100">
        <div class="row justify-content-center w-100 mx-0" style="padding: 2rem 0rem 2rem 0rem;">
            <div class="col-12">
                <a class="btn visit-web" href="https://printden.store/">
                    {{-- <img class="img-fluid mr-1" src="{{ asset('frontend/images/icons/arrow-left.svg') }}" /> --}}
                    Visit Website  
                </a>
            </div>
        </div>
        <!-- Outer Row -->
        <div class="row justify-content-center w-100 mx-0" style="padding: 5rem 0rem 5rem 0rem;">
            
            <div class="col-12 col-md-4 col-lg-4">
                <div class="login_card">
                    <div class="text-center">
                        @if (Session::has('success_message'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success:</strong> {{ Session::get('success_message') }}
                                <button type="button" class="close" data-dismiss="alert"
                                    aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if (Session::has('error_message'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error:</strong> {{ Session::get('error_message') }}
                                <button type="button" class="close" data-dismiss="alert"
                                    aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="sidebar-brand-icon">
                            <img class="img-fluid" src="{{ asset('admin/printden_main.svg') }}" style="width: 80px;" />
                        </div>

                        
                    </div>
                    <form id="signInForm" class="" action="{{ route('login') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group has-validation">
                            <label for="emailInput">Email<span class="text-danger">
                                    *</span></label>
                            <input type="email" class="form-control form-control-user" id="emailInput"
                                name="email" value="{{ old('email') }}" title="Email" required>
                            {{-- <span id="emailRightSign" style="display:none"><i><img
                                        src="{{ asset('admin/img/icons/rightSign.png') }}"
                                        style="margin-top: -4rem;margin-left: 18.5rem;" /></i></span> --}}
                            @error('email')
                                <span class="pl-2 text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="passwordInput">Password<span class="text-danger">
                                    *</span></label>
                            <input type="password" class="form-control form-control-user"
                                id="passwordInput" name="password" required>
                            <div class="input-group-append" onclick="showHidePass()"
                                style="cursor: pointer; margin-top: -2rem; margin-left: 90%;">
                                <span class="show_hide_password"><i id="pass_icon"><img
                                            src="{{ asset('admin/img/icons/password.svg') }}"
                                            width="30" /></i></span>
                            </div>
                            <div class="text-right mt-2">
                                <a href="{{ route('forget.pass') }}"
                                    style="text-decoration: none;"><span
                                        style="font-size:12px; color:#718791 ">Forgot
                                        Password?</span></a>
                            </div>
                            @error('password')
                                <span class="pl-2 text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary site_button">
                            Login
                        </button>
                    </form>
                    {{-- @endif --}}
                     
            </div>
        </div>

    </div>


    <!-- Bootstrap core JavaScript-->
    <script src="{{ url('admin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ url('admin/vendor/jquery-eaSign/jquery.eaSign.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ url('admin/js/sb-admin-2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            function showHidePass(formId) {
                var passwordInput = $("#" + formId + " #passwordInput");

                if (passwordInput.attr("type") === "password") {
                    passwordInput.attr("type", "text");
                    $("#" + formId + " #pass_icon").html(
                        '<img src="{{ asset('admin/img/icons/password-show.svg') }}" width="30" />'
                    );
                } else {
                    passwordInput.attr("type", "password");
                    $("#" + formId + " #pass_icon").html(
                        '<img src="{{ asset('admin/img/icons/password.svg') }}" width="30" />'
                    );
                }
            }

            $("#signUpForm .show_hide_password").on("click", function() {
                showHidePass("signUpForm");
            });

            $("#signInForm .show_hide_password").on("click", function() {
                showHidePass("signInForm");
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            // Function to check password strength
            // const uppercaseRegex = /[A-Z]/;
            // Function to check password strength
            function checkPasswordStrength(password) {
                const uppercaseRegex = /[A-Z]/;
                const numberRegex = /[0-9]/;
                const specialCharRegex = /[!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?]/;

                let strength = 0;

                if (password.length >= 8 && uppercaseRegex.test(password) && numberRegex.test(password) &&
                    specialCharRegex.test(password)) {
                    $("#password-strength").text("Great! Looks like a strong password.");
                    $("#password-strength").css("color", "#4AB37B");
                } else if (uppercaseRegex.test(password) && numberRegex.test(password) || numberRegex.test(
                        password) && specialCharRegex.test(password) || uppercaseRegex.test(password) &&
                    specialCharRegex.test(password)) {
                    $("#password-strength").text("Seems good, just make it stronger!");
                    $("#password-strength").css("color", "#EA9F48");
                } else {
                    $("#password-strength").text("Too weak, make your password strong.");
                    $("#password-strength").css("color", "#EF717D");
                }

                if (uppercaseRegex.test(password)) {
                    strength++;
                    $(".uppercase-instruction").css("color", "#4AB37B");
                } else {
                    $(".uppercase-instruction").css("color", "");
                }

                if (numberRegex.test(password)) {
                    strength++;
                    $(".number-instruction").css("color", "#4AB37B");
                } else {
                    $(".number-instruction").css("color", "");
                }

                if (specialCharRegex.test(password)) {
                    strength++;
                    $(".special-char-instruction").css("color", "#4AB37B");
                } else {
                    $(".special-char-instruction").css("color", "");
                }
                return strength;
            }

            // Event listener for password input
            $("#passwordInput").keyup(function() {
                const password = $(this).val();
                // alert(uppercaseRegex.test(password));
                checkPasswordStrength(password);
            });
        });
    </script>
    {{-- email right sign --}}
    <script>
        // Function to validate email format
        function isValidEmail(email) {
            // Regular expression to validate email format
            const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            return emailPattern.test(email);
        }

        // Function to handle input event and show/hide the emailRightSign span
        function handleEmailInput() {
            const emailInput = this; // 'this' refers to the element that triggered the event
            const emailRightSign = emailInput.nextElementSibling;

            const email = emailInput.value.trim();
            const isValid = isValidEmail(email);

            if (isValid) {
                emailRightSign.style.display = 'inline'; // Show the span
            } else {
                emailRightSign.style.display = 'none'; // Hide the span
            }
        }

        // Attach the handleEmailInput function to both emailInput elements
        const emailInputs = document.querySelectorAll('input[type="email"]');
        emailInputs.forEach(emailInput => {
            emailInput.addEventListener('input', handleEmailInput);
        });
    </script>

</body>

</html>

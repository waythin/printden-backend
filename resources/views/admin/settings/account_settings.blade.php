@extends('admin.layout.layout')
@push('styles')
    <style>
        .tab_button {
            font: var(--unnamed-font-style-normal) normal var(--unnamed-font-weight-medium) var(--unnamed-font-size-13)/var(--unnamed-line-spacing-24) var(--unnamed-font-family-montserrat);
            letter-spacing: var(--unnamed-character-spacing-0);
            color: var(--secondary-font-color);
            text-align: center;
            font: normal normal medium 13px/24px Montserrat;
            font-size: 13px;
            letter-spacing: 0px;
            color: #718791;
            opacity: 0.7;
            padding: 0.5rem 1rem;
            margin-right: 1rem;
        }

        .tab_button:hover {
            text-decoration: none;
            color: #000000;
        }

        .tab_button.active {
            color: #FFFFFF;
            background: var(--primary) 0% 0% no-repeat padding-box;
            background: #4D7CFF 0% 0% no-repeat padding-box;
            border-radius: 6px;
            opacity: 1;
        }

        .section_border {
            border-bottom: 1px solid #E4E7EB;
        }

        .logo_container {
            width: 60px;
            height: 60px;
            display: flex;
        }

        .onboard-input-field {
            background-color: #FCFDFE;
            border: 0.12rem solid#E4E7EB;
            height: 3rem;
        }

        .onboard-input-field {
            background-color: #FCFDFE;
            border: 0.12rem solid#E4E7EB;
            height: 3rem;
        }

        .form-control:focus {
            color: #6e707e !important;
            background-color: #fff !important;
            border-color: #bac8f3 !important;
            outline: 0 !important;
            /* box-shadow: 0 0 0 0.1rem rgb(219 219 219 / 25%); */
            box-shadow: 0 0 0 0 rgb(219 219 219 / 25%) !important;
        }

        .submit_btn {
            background-color: #4D7CFF;
            color: #ffffff;
            font-size: 13px;
            padding: 0.75rem 2rem;
        }

        .submit_btn:hover {
            background-color: #5d87fc;
            color: #ffffff;
        }

        .form-control-user {
            border: none;
            border-bottom: 1px solid rgb(155, 155, 155);
            border-radius: 1px !important;
            height: 39px;
            padding: 0 !important;
        }

        .round-upload-feild {
            width: 5.5rem;
            height: 5.5rem;
            /* padding: 2rem; */
            border-radius: 50%;
            background-color: #FCFDFE;
            border: 0.15rem dashed #E4E7EB;
        }

        .logo-upload-field {
            opacity: 0;
            width: 5.5rem;
            height: 5.5rem;
            position: absolute;
        }

    </style>
@endpush
@section('title', $title)
@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
    </div>

    <div class="row padding-top">
        <div class="col-lg-12">
             @include('admin.settings.settings_nav')
            <div class="card mt-4 p-4">
                {{-- @if (Session::has('error_message'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error:</strong> {{ Session::get('error_message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if (Session::has('success_message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success:</strong> {{ Session::get('success_message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif --}}
                <div class="section_border">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form action="{{ route('admin.update.details') }}" method="POST" class="" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-10">
                                        <h2 class="ml-4 mt-4 pl-2 mb-2"
                                            style="color:#384A52; font-size:1.125rem !important; font: normal normal 600 32px/34px Montserrat;">
                                            Profile</h2>
                                    </div>
                                    <div class="col-md-8 p-2 pl-4 ml-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="round-upload-feild text-center d-flex align-items-center">
                                                <input type="file" name="photo" id="imageUpload"
                                                    onchange="loadFile(event)" class="logo-upload-field">
                                                <img id="imageShow" class="img-fluid"
                                                   @if (Auth::guard('admin')->user()->image)
                                                   src="{{ asset(Auth::guard('admin')->user()->image) }}"
                                                   @else 
                                                   src="{{ asset('admin/img/onboard/camera.svg') }}"
                                                   @endif
                                                    alt="">
                                            </div>
                                            @if(Auth::guard('admin')->user()->image)
                                            <input type="hidden" name="current_image" value="{{ Auth::guard('admin')->user()->image }}">
                                            @endif
                                            {{-- <div class="logo_container">
                                                <img class="rounded-circle img-fluid"
                                                    src="{{ asset(Auth::guard('admin')->user()->image) }}">
                                                <input type="hidden" name="current_image"
                                                    value="{{ Auth::guard('admin')->user()->image }}">
                                            </div> --}}

                                            <div class="">
                                                <h2 class="ml-1 mt-4 pl-2 mb-3"
                                                    style="color:#384A52; font-size:1.125rem !important;">
                                                    {{ Auth::guard('admin')->user()->name }}</h2>

                                            </div>
                                        </div>

                                        <div class="col-md-8 w-50 mb-4 pl-0">
                                            <label for="email">Email Address <span class="text-danger"
                                                    style="font-size: 1.25rem;">*</span></label>
                                            <input type="email" class="form-control onboard-input-field" id=""
                                                name="email" placeholder="johnsmith@example.com"
                                                value="{{ auth('admin')->user()->email }}">

                                            @error('email')
                                                <span class="pl-2 text-danger" role="alert">
                                                    <strong class="warning-msg-email">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-8 w-50 mb-4 pl-0">
                                            <label for="mobile">Personal Phone Number <span class="text-danger"
                                                    style="font-size: 1.25rem;">*</span></label>
                                            <input type="phone" class="form-control onboard-input-field" id=""
                                                name="mobile" placeholder=""
                                                @if (isset(auth('admin')->user()->mobile)) value="{{ auth('admin')->user()->mobile }}" @else  
                                                    @if (is_null(old('mobile')))
                                                        value="+880"
                                                    @else
                                                        value="{{ old('mobile') }}" @endif
                                                @endif>

                                            @error('mobile')
                                                <span class="pl-2 text-danger" role="alert">
                                                    <strong class="warning-msg-email">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-8 w-50 mb-4 pl-0">
                                            <button type="submit" class="btn submit_btn">Save Profile</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="section_border">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form id="changePassForm" action="{{ route('admin.change.password') }}" method="POST" class=""
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-10">
                                        <h2 class="ml-4 mt-4 pl-2 mb-2"
                                            style="color:#384A52; font-size:1.125rem !important; font: normal normal 600 32px/34px Montserrat;">
                                            Change Password</h2>
                                    </div>
                                    <div class="col-md-8 p-2 pl-4 ml-4">
                                        <div class="col-md-8 w-50 mb-4 pl-0">

                                            <label for="passwordInput">Current Password <span class="text-danger"
                                                    style="font-size: 1.25rem;">*</span></label>
                                            <input type="password" class="form-control onboard-input-field"
                                                id="passwordInput" name="current_password" required>
                                            <div class="input-group-append" onclick="showHidePass()"
                                                style="cursor: pointer; margin-top: -2.4rem;margin-left: 90%;">
                                                <span class="show_hide_password"><i id="pass_icon"><img
                                                            src="{{ asset('admin/img/icons/password.svg') }}"
                                                            width="30" /></i></span>
                                            </div>
                                            @error('current_password')
                                                <span class="pl-2 text-danger" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-8 w-50 mb-4 pl-0">
                                            <label for="p_email">New Password <span class="text-danger"
                                                    style="font-size: 1.25rem;">*</span></label>
                                            <input type="password" class="form-control onboard-input-field"
                                                id="passwordInput" name="password" required>
                                            <div class="input-group-append" onclick="showHidePass()"
                                                style="cursor: pointer; margin-top: -2.4rem;margin-left: 90%;">
                                                <span class="show_hide_password"><i id="pass_icon"><img
                                                            src="{{ asset('admin/img/icons/password.svg') }}"
                                                            width="30" /></i></span>
                                            </div>
                                            @error('password')
                                                <span class="pl-2 text-danger" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-8 w-50 mb-4 pl-0">
                                            <label for="mobile">Confirm New Password <span class="text-danger"
                                                    style="font-size: 1.25rem;">*</span></label>
                                            <input type="password" class="form-control onboard-input-field"
                                                id="passwordInput" name="password_confirmation" required>
                                            <div class="input-group-append" onclick="showHidePass()"
                                                style="cursor: pointer; margin-top: -2.4rem;margin-left: 90%;">
                                                <span class="show_hide_password"><i id="pass_icon"><img
                                                            src="{{ asset('admin/img/icons/password.svg') }}"
                                                            width="30" /></i></span>
                                            </div>
                                            @error('password_confirmation')
                                                <span class="pl-2 text-danger" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-8 w-50 mb-4 pl-0">
                                            <button type="submit" class="btn submit_btn">Change Password</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            $(document).ready(function() {
                var success_msg = <?php echo json_encode(Session::get('success_message')); ?>;
                var error_msg = <?php echo json_encode(Session::get('error_message')); ?>;

                const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

            console.log(success_msg);
            if(success_msg){
                    Toast.fire({
                        icon: 'success',
                        title: success_msg
                    })
            }
            if(error_msg){
                Toast.fire({
                        icon: 'error',
                        title: error_msg
                    })
            }
            
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

                $("#changePassForm .show_hide_password").on("click", function() {
                    showHidePass("changePassForm");
                });


            });
        </script>
        <script>
            var loadFile = function(event) {
                var imageSowElement = document.getElementById('imageShow');
                var output = document.getElementById('imageShow');
                output.src = URL.createObjectURL(event.target.files[0]);
                if (output.src != "") {
                    imageSowElement.style.display = 'block';
                }
                output.onload = function() {
                    URL.revokeObjectURL(output.src) // free memory
                }
                document.getElementById('imageName').textContent = event.target.files[0].name.substr(0, 15) + "...";
                document.getElementById('imageSize').textContent = event.target.files[0].size / 1000 + " KB";
            };
            var clearImage = function() {
                var fileInput = document.getElementById('imageUpload');
                fileInput.value = '';
                var output = document.getElementById('output');
                output.src = "";
                var imageNameElement = document.getElementById('imageShow');
                imageNameElement.style.display = 'none';
    
            };
    
    
            // document.querySelector("#imageUpload").onchange = function() {
    
    
            // }
        </script>
    @endpush
@endsection

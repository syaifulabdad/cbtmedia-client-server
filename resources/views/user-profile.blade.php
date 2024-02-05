@extends('layouts.master')
@section('title')
    {{ $title }}
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Dashboard
        @endslot
        @slot('title')
            {{ $title }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-xxl-3">
            <div class="card card-bg-fill">
                <div class="card-body p-4">
                    <div class="text-center">
                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                            {{-- @dd($user->avatar) --}}
                            <img src="{{ $user->avatar ? $user->avatar : asset('build/images/users/user-dummy-img.jpg') }}" class="#rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">
                            <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                <form action="#" id="formFile" method="POST" enctype="multipart/form-data">
                                    <input id="file" type="file" name="file" class="profile-img-file-input">
                                    <label for="file" class="profile-photo-edit avatar-xs">
                                        <span class="avatar-title rounded-circle bg-light text-body">
                                            <i class="ri-camera-fill"></i>
                                        </span>
                                    </label>
                                </form>
                            </div>
                        </div>
                        <h5 class="fs-16 mb-1">{{ strtoupper($user->name) }}</h5>
                        <p class="text-muted mb-0">{{ $user->email }}</p>
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
        <div class="col-xxl-9">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab">
                                <i class="fas fa-home"></i> Personal Details
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab">
                                <i class="far fa-user"></i> Change Password
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content">
                        <div class="tab-pane active" id="personalDetails" role="tabpanel">
                            <form id="form-profile" action="javascript:void(0);">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email Address</label>
                                            <input type="email" class="form-control" id="email" value="{{ $user->email }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone Number</label>
                                            <input type="text" class="form-control" id="phone" name="phone_number" value="{{ $user->phone_number }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="wa" class="form-label">Whatsapp Number</label>
                                            <input type="text" class="form-control" id="wa" name="whatsapp_number" value="{{ $user->whatsapp_number }}">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea class="form-control" id="address" name="address">{{ $user->address }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="hstack gap-2">
                                            <button type="submit" class="btn btn-primary btnUpdate">Update</button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>
                        </div>
                        <!--end tab-pane-->
                        <div class="tab-pane" id="changePassword" role="tabpanel">
                            <form id="form-password" action="javascript:void(0);">
                                @csrf

                                <div class="row g-2">
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="password" class="form-label">New Password*</label>
                                            <input type="password" class="form-control" id="password" name="password">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="password2" class="form-label">Confirm Password*</label>
                                            <input type="password" class="form-control" id="password2" name="password2">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="">
                                            <button type="submit" class="btn btn-primary btnUpdatePass">Change Password</button>
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
@endsection

@section('script')
    <script>
        $('.btnUpdate').click(function() {
            $('.btnUpdate').attr('disabled', true).html('<i class="fa fa-save"></i> menyimpan...');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').empty();

            var formData = $('#form-profile').serialize();
            $.ajax({
                data: formData,
                url: "{{ $cUrl }}/update",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    if (data.status) {
                        Swal.fire({
                            title: "Sukses.!",
                            text: 'Data berhasil diubah.',
                            icon: "success",
                        });
                        // location.reload();
                    } else {
                        for (var i = 0; i < data.error_string.length; i++) {
                            if (data.error_string[i]) {
                                $('[name="' + data.inputerror[i] + '"]').addClass('is-invalid').next(
                                    '.invalid-feedback').html(
                                    data.error_string[i]);
                            }
                        }
                    }
                    $('.btnUpdate').attr('disabled', false).html('<i class="fa fa-save"></i> Simpan');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert(textStatus);
                    $('.btnUpdate').attr('disabled', false).html('<i class="fa fa-save"></i> Simpan');

                }
            });
        });

        $('.btnUpdatePass').click(function() {
            $('.btnUpdatePass').attr('disabled', true).html('<i class="fa fa-save"></i> menyimpan...');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').empty();

            var formData = $('#form-password').serialize();
            $.ajax({
                data: formData,
                url: "{{ $cUrl }}/update-pass",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    if (data.status) {
                        Swal.fire({
                            title: "Sukses.!",
                            text: 'Password berhasil diubah.',
                            icon: "success",
                        });
                        // location.reload();
                    } else {
                        Swal.fire({
                            title: "Gagal.!",
                            text: data.message,
                            icon: "warning",
                        });
                    }
                    $('.btnUpdatePass').attr('disabled', false).html('<i class="fa fa-save"></i> Simpan');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert(textStatus);
                    $('.btnUpdatePass').attr('disabled', false).html('<i class="fa fa-save"></i> Simpan');

                }
            });
        });

        $('[name="file"]').change(function() {
            // Get the selected file
            var files = $('#file')[0].files;
            var formData = new FormData();

            // Append data 
            formData.append('file', files[0]);
            formData.append('_token', '{{ csrf_token() }}');

            // AJAX request 
            $.ajax({
                url: "{{ $cUrl }}/upload-file",
                method: 'post',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success == true) { // Uploaded successfully
                        Swal.fire({
                            title: "Sukses.!",
                            text: response.message,
                            icon: "success",
                        });
                        location.reload();
                    } else if (response.success == false) {
                        alert(response.error);
                    } else {
                        // Display Error
                        Swal.fire({
                            title: "Sukses.!",
                            text: response.error,
                            icon: "error",
                        });
                    }
                },
                error: function(response) {
                    console.log("error : " + JSON.stringify(response));
                }
            });
        });
    </script>
@endsection

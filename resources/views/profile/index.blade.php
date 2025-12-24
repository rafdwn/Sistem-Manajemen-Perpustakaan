@extends('layouts.app')

@section('title', 'Profile Pustakawan')

@section('content')

<div class="container">

    <h3><b>Edit Profil</b></h3>

    <div class="row mt-4">

        <!-- KARTU PROFIL KIRI -->
        <div class="col-md-4">
            <div class="card p-3 text-center" style="background: linear-gradient(135deg, #cce0ff, #a8ffdd)">

                <img 
                    src="{{ $user->photo ? asset('uploads/photos/' . $user->photo) : asset('user.png') }}" 
                    style="width:120px;" 
                    class="mx-auto mb-3 rounded-circle"
                >

                <h5>{{ $user->name }}</h5>
                <p>{{ '@' . $user->username }}</p>

                <button class="btn btn-primary btn-md" data-toggle="modal" data-target="#modalUploadFoto">
                    Upload Foto Baru
                </button>

                <p class="mt-3">Pustakawan sejak :
                    <b>{{ $user->created_at->format('d F Y') }}</b>
                </p>

            </div>
        </div>

        <!-- FORM EDIT PROFIL -->
        <div class="col-md-8">
            <div class="card p-4" style="background: linear-gradient(135deg, #cce0ff, #a8ffdd)">

                <form id="formUpdate" method="POST" action="{{ route('profile.update') }}">
                    @csrf

                    <div class="row">

                        <!-- Nama -->
                        <div class="col-md-6">
                            <label>Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ $user->name }}">
                        </div>

                        <!-- Username -->
                        <div class="col-md-6">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" value="{{ $user->username }}">
                        </div>

                        <!-- PASSWORD SEKARANG -->
                        <div class="col-md-6 mt-3">
                            <label>Password Sekarang</label>
                            <input type="password" class="form-control" value="{{ $user->password_plain }}" readonly>
                        </div>

                        <!-- PASSWORD BARU -->
                        <div class="col-md-6 mt-3">
                            <label>Password Baru</label>
                            <input type="password" name="password_new" class="form-control" placeholder="Kosongkan jika tidak diganti">
                        </div>

                        <!-- EMAIL SEKARANG -->
                        <div class="col-md-6 mt-3">
                            <label>Email Sekarang</label>
                            <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                        </div>

                        <!-- EMAIL BARU -->
                        <div class="col-md-6 mt-3">
                            <label>Email Baru</label>
                            <input type="email" name="email_new" class="form-control" placeholder="Kosongkan jika tidak diganti">
                        </div>

                    </div>

                    <button type="button" class="btn btn-primary btn-md mt-4" data-toggle="modal" data-target="#confirmUpdateModal">
                        Update Profile
                    </button>

                </form>
                <!-- Modal Konfirmasi -->
                <div class="modal fade" id="confirmUpdateModal" tabindex="-1" aria-labelledby="confirmUpdateLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title" id="confirmUpdateLabel"><b>Konfirmasi Update</b></h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>

                            <div class="modal-body">
                                Apakah Anda yakin ingin memperbarui profil ini?
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>

                                <!-- Tombol Submit Form -->
                                <button type="button" class="btn btn-primary" onclick="document.getElementById('formUpdate').submit();">
                                    Ya, Update
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Modal Upload Foto -->
                <div class="modal fade" id="modalUploadFoto">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title"><b>Upload Foto Baru</b></h5>
                                <button class="close" data-dismiss="modal">&times;</button>
                            </div>

                    <form id="uploadPhotoForm" method="POST" action="{{ route('profile.updatePhoto') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="modal-body text-center">

                            <!-- PREVIEW FOTO -->
                            <img id="previewImage" src="{{ asset('user.png') }}" style="width:140px; border-radius:10px; margin-bottom:10px; display:none;">

                            <div class="form-group">
                                <input type="file" name="photo" id="photoInput" class="form-control" accept="image/*" required>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                            <button type="submit" class="btn btn-primary">
                                Update Foto
                            </button>
                        </div>
                    </form>

                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

@endsection
@push('scripts')
<script>
document.getElementById("photoInput").onchange = function(event) {
    let preview = document.getElementById("previewImage");
    preview.src = URL.createObjectURL(event.target.files[0]);
    preview.style.display = "block";
};
</script>
@endpush


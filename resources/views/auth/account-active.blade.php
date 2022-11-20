@extends('admin.app')
@section('contact')
    <div class="container m-2">
        <div class="bg-light rounded-top p-4">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="card p-0 m-0">
                        <div class="card-header">
                            Please Enter OTP Code
                        </div>
                        <div class="card-body">
                            <span class="card-title">We mailed a code to <i>{{ Auth::user()->email }}</i>.</span>
                            <div class="card-text mt-4">
                                <input id="otpCodeContainer" type="text" class="form-control" placeholder="######">
                            </div>
                            <button id="otpSubmit" disabled class="btn w-100 btn-primary mt-3">Submit</button>
                        </div>
                        <div class="card-footer">
                            <a id="otpCodeResend" class="btn btn-link float-end d-none">Resend Code</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        activeSidebar();
        $(document).ready(function() {
            $('#otpSubmit').removeAttr('disabled');
            $('#otpSubmit').click(function(e) {
                $('#otpSubmit').attr('disabled', 'true');
                e.preventDefault();
                let formData = {
                    code: $(this).parent().find('#otpCodeContainer').val(),
                    email: '{{ Auth::user()->email }}',
                    _token: '{{ csrf_token() }}',
                    from_server: 'true'
                }
                $.ajax({
                    type: "POST",
                    url: "{{ route('otp_check') }}",
                    data: formData,
                    dataType: "JSON",
                    success: function(response) {
                        $('#otpSubmit').removeAttr('disabled');
                        if (response.error) {
                            let err = '';
                            response.error.forEach(e => {
                                err += e;
                            });
                            Swal.fire('Error', err, 'error');
                            if (err == 'Otp Code is Expired!' || err ==
                                'Please Request OTPs again!') {
                                $('.otpCodeResend').removeAttr('disabled');
                            }
                        }
                        if (response.success) {
                            Swal.fire('Success', 'Actount Active Success',
                                'success')
                            this.location.href = "{{ route('member#product') }}"
                        }
                    },
                    error: function(err) {
                        console.log(err);
                        Swal.fire('Error', 'Submit Again',
                            'error')
                        $('#otpSubmit').removeAttr('disabled');

                    }
                });
            });
        });
    </script>
@endpush

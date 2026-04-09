  @if(session('showVerificationModal'))
    <!-- staticBackdrop Modal -->
    <div class="modal fade" id="verificationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center ">
                    <div class="row justify-content-center">
                        <div class="col-12 ">
                            <div class="card mt-4">
                                <div class="card-body p-0">
                                    <div class="mb-4">
                                        <div class="avatar-lg mx-auto">
                                            <div class="avatar-title bg-light text-primary display-5 rounded-circle">
                                                <i class="ri-mail-line"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-2 mt-4">
                                        <div class="text-muted text-center mb-4 mx-lg-3">
                                            <h4 class="">Verify Your Email</h4>
                                            <p>Please enter the 4 digit code sent to <span class="fw-semibold">{{session('email')?session('email'):''}}</span></p>
                                        </div>

                                        <form id="verify_account" action="{{ route('user.verify.email') }}" method="POST">

                                            @include('includes.alerts')
                                            @csrf
                                            <input type="hidden" name="email" value="{{session('email')??session('email')}}">
                                            <div class="mb-3">

                                                <label for="verification" class="form-label">Verification code</label>
                                                <input type="text" class="form-control"  value="" id="verification" name="token" placeholder="Enter Verification Code">

                                            </div>
                                            <div class="mt-3">
                                                <button type="submit" class="btn g2z-btn-primary w-100 submit-btn">Confirm</button>
                                            </div>
                                        </form>


                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->

                            <div class="mt-4 text-center">
                                <p class="mb-0">Didn't receive a code ? <a href="javascript:;" data-href="{{route('user.resend.verify',session('email')??session('email'))}}"  class="fw-semibold text-primary text-decoration-underline resendcodelk">Resend</a> </p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('script')
        <script>
            $(document).ready(function(){
                $('#verificationModal').modal('show');
            });
        </script>
    @endsection
  @elseif(session('showReferralModal'))

      <div class="modal fade" id="referralModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                  <div class="modal-body text-center ">
                      <div class="row justify-content-center">
                          <div class="col-12 ">
                              <div class="card mt-4">
                                  <div class="card-body p-0">
                                      <div class="mb-4">
                                          <div class="avatar-lg mx-auto">
                                              <div class="avatar-title bg-light text-primary display-5 rounded-circle">
                                                  <i class="ri-mail-line"></i>
                                              </div>
                                          </div>
                                      </div>

                                      <div class="p-2 mt-4">
                                          <div class="text-muted text-center mb-4 mx-lg-3">
                                              <h4 class="">Add Referral Code</h4>
                                              <p>Please enter the 8 digit referral code. </p>
                                          </div>

                                          <form id="referralForm" action="{{ route('user.google-referral-submit') }}" method="POST">

                                              @include('includes.alerts')
                                              @csrf
                                              <input type="hidden" name="user_id" value="{{ session('user_id') }}">
                                              <div class="mb-3">
                                                  <input type="text" class="form-control" id="referral_code_input" name="referral_code" placeholder="Enter referral code">
                                              </div>
                                              <div class="mt-3">
                                                  <a href="{{ route('user.google-skip-referral') }}"  class="btn btn-secondary" >Skip</a>
                                                  <button type="submit" class="btn g2z-btn-primary submit-btn" form="referralForm">Add Referral Code</button>                                              </div>
                                          </form>


                                      </div>
                                  </div>
                                  <!-- end card body -->
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>


      @section('script')
      <script>
          $(document).ready(function(){
              $('#referralModal').modal('show');
              
          });
      </script>
      @endsection
        @endif




@extends('front.layouts.app')
@section('title') Email @endsection
@section('css')


@endsection
@section('content')

           
           <section class="section  page__content">
                    <div class="container"> 
                        <div class="row justify-content-center">
                            <div class="col-md-8 col-lg-6 col-xl-5">
                                <div class="card mt-4">

                                    <div class="card-body p-4">
                                        <div class="text-center mt-2">
                                            <h5 class="text-primary">Forgot Password?</h5>
                                            <p class="text-muted">Reset password with {{$gs->name}}</p>

                                            <lord-icon src="https://cdn.lordicon.com/rhvddzym.json" trigger="loop"
                                                colors="primary:#0ab39c" class="avatar-xl">
                                            </lord-icon>

                                        </div>

                                        <div class="alert alert-borderless alert-warning text-center mb-2 mx-2" role="alert">
                                            Enter your email and instructions will be sent to you!
                                        </div>
                                        <div class="p-2">
                                             <form class="form-horizontal" method="POST"
                                                action="{{ route('user.forgot.submit') }}">
                                                @include('includes.alerts')
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="useremail" class="form-label">Email</label>
                                                    <input type="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        id="useremail" name="email" placeholder="Enter email"
                                                        value="{{ old('email') }}" id="email">
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="text-end">
                                                    <button class="btn  g2z-btn-primary w-md waves-effect waves-light"
                                                        type="submit">Reset</button>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                    <!-- end card body -->
                                </div>
                                <!-- end card -->

                                <div class="mt-4 text-center">
                                    <p class="mb-0">Wait, I remember my password... <a href="{{route('user.login')}}"
                                            class="fw-semibold text-primary text-decoration-underline"> Click here </a> </p>
                                </div>

                            </div>
                        </div>
                    </div><!--end col-->
           </section>
            <!-- end footer -->
            <div class="ellipse"></div>

    @endsection
    @section('script')

    @endsection

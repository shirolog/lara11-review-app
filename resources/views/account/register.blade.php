@extends('layouts.app')

@section('main')
<section class=" p-3 p-md-4 p-xl-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-9 col-lg-7 col-xl-6 col-xxl-5">
                <div class="card border border-light-subtle rounded-4">
                    <div class="card-body p-3 p-md-4 p-xl-5">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-5">
                                    <h4 class="text-center">Register Here</h4>
                                </div>
                            </div>
                        </div>
                        <form action="{{route('account.store')}}" method="post">
                            @csrf
                            <div class="row gy-3 overflow-hidden">
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"  id="name" value="{{old('name')}}" placeholder="Name" >
                                        <label for="text" class="form-label">Name</label>
                                        @error('name')
                                            <p class="invalid-feedback">{{$message}}</p> 
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input type="text" name="email" class="form-control @error('email') is-invalid @enderror"  id="email" value="{{old('email')}}" placeholder="name@example.com" >
                                        <label for="text" class="form-label">Email</label>
                                        @error('email')
                                            <p class="invalid-feedback">{{$message}}</p> 
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"  id="password" value="" placeholder="Password" >
                                        <label for="password" class="form-label">Password</label>
                                        @error('password')
                                            <p class="invalid-feedback">{{$message}}</p> 
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" id="confirm_password" value="" placeholder="Confirm Password" >
                                        <label for="password" class="form-label">Confirm Password</label>
                                        @error('password_confirmation')
                                            <p class="invalid-feedback">{{$message}}</p> 
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-grid">
                                        <button class="btn bsb-btn-xl btn-primary py-3" type="submit">Register Now</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-12">
                                <hr class="mt-5 mb-4 border-secondary-subtle">
                                <div class="d-flex gap-2 gap-md-4 flex-column flex-md-row justify-content-center">
                                    <a href="{{route('account.login')}}" class="link-secondary text-decoration-none">Click here to login</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

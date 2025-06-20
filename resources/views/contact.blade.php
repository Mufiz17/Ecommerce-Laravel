@extends('layouts.app')
@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="contact-us container">
            <div class="mw-930">
                <h2 class="page-title">CONTACT US</h2>
            </div>
        </section>

        <hr class="mt-2 text-secondary " />
        <div class="mb-4 pb-4"></div>

        <section class="contact-us container">
            <div class="mw-930">
                <div class="contact-us__form">
                    @session('success')
                        <div class="alert alert-success alert-dismissible position-absolute bottom-0 end-0 me-3" role="alert">
                            <div class="d-flex">
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24"
                                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M5 12l5 5l10 -10"></path>
                                    </svg>
                                </div>
                                <div>
                                    {{ session('success') }}
                                </div>
                            </div>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                        </div>
                    @endsession
                    <form name="contact-us-form" class="needs-validation" method="POST"
                        action="{{ route('home.contact.store') }}">
                        @csrf
                        <h3 class="mb-5">Get In Touch</h3>
                        <div class="form-floating my-4">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Name *" required=""
                                value="{{ old('name') }}">
                            <label for="contact_us_name">Name *</label>
                            <span class="text-danger"></span>
                        </div>
                        <div class="form-floating my-4">
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" placeholder="Phone *" required=""
                                value="{{ old('phone') }}">
                            <label for="contact_us_name">Phone *</label>
                            <span class="text-danger"></span>
                        </div>
                        <div class="form-floating my-4">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email address *"
                                required="" value="{{ old('email') }}">
                            <label for="contact_us_name">Email address *</label>
                            <span class="text-danger"></span>
                        </div>
                        <div class="my-4">
                            <textarea class="form-control @error('comment') is-invalid @enderror form-control_gray" name="comment" placeholder="Your Message" cols="30" rows="8"
                                required="">{{ old('comment') }}</textarea>
                            <span class="text-danger"></span>
                        </div>
                        <div class="my-4">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
@endsection

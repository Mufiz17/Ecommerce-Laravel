@extends('layouts.app')
@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="contact-us container">
            <div class="mw-930">
                <h2 class="page-title">About Us</h2>
            </div>

            <div class="about-us__content pb-5 mb-5">
                <p class="mb-5">
                    <img loading="lazy" class="w-100 h-auto d-block" src="{{ asset('assets/images/about/about-1.jpg') }}"
                        width="1410" height="550" alt="Teamwork image" />
                </p>
                <div class="mw-930">
                    <h3 class="mb-4">Who We Are</h3>
                    <p class="fs-6 fw-medium mb-4">
                        We are a passionate team of creatives, engineers, and thinkers dedicated to building impactful digital experiences. Since our founding, we’ve focused on combining technology with empathy to deliver solutions that not only work — but feel right.
                    </p>
                    <p class="mb-4">
                        Our culture thrives on collaboration, continuous learning, and a shared mission to push boundaries. Whether it’s through innovative design, robust backend systems, or seamless user experiences, we strive to create work that matters.
                    </p>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5 class="mb-3">Our Mission</h5>
                            <p class="mb-3">To empower individuals and businesses through meaningful technology that inspires, connects, and creates lasting value.</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="mb-3">Our Vision</h5>
                            <p class="mb-3">To become a global leader in digital innovation, crafting experiences that shape the future of how people interact with technology.</p>
                        </div>
                    </div>
                </div>

                <div class="mw-930 d-lg-flex align-items-lg-center mt-5">
                    <div class="image-wrapper col-lg-6">
                        <img class="h-auto rounded" loading="lazy" src="{{ asset('assets/images/about/about-2.jpg') }}"
                            width="450" height="500" alt="Office environment">
                    </div>
                    <div class="content-wrapper col-lg-6 px-lg-4">
                        <h5 class="mb-3">The Company</h5>
                        <p>
                            Founded in 2020, we started as a small startup with a big dream — to simplify complexity through intuitive design and smart technology. Today, we serve clients across industries, helping them digitize operations, engage users, and scale faster.
                        </p>
                        <p>
                            With a strong belief in transparency, innovation, and user-first thinking, we continue to evolve while staying true to our core values. At the heart of it all is a team driven by curiosity and a passion for excellence.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

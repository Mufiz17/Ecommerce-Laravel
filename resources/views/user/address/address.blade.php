@extends('layouts.app')
@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>

        <section class="my-account container">
            <h2 class="page-title mb-4">Addresses</h2>

            <div class="row">
                <div class="col-lg-2">
                    @include('user.nav')
                </div>

                <div class="col-lg-10">
                    <div class="page-content my-account__address">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <p class="notice mb-0">The following addresses will be used on the checkout page by default.</p>
                            <a href="{{ route('user.address.create') }}" class="btn btn-sm btn-info">+ Add New</a>
                        </div>

                        <div class="row g-3">
                            @forelse ($address as $item)
                                <div class="col-md-6">
                                    <div class="card shadow-sm h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <h5 class="card-title mb-0">
                                                        {{ $item->name }}
                                                        @if ($item->is_default)
                                                            <span class="badge bg-success ms-2">Default</span>
                                                        @endif
                                                    </h5>
                                                </div>
                                            </div>

                                            <div class="card-text text-muted">
                                                <p class="mb-1">{{ $item->address }}</p>
                                                <p class="mb-1">{{ $item->locality }}, {{ $item->city }}</p>
                                                <p class="mb-1">{{ $item->state }}, {{ $item->zip }}</p>
                                                <p class="mb-1">{{ $item->country }}</p>
                                                @if ($item->landmark)
                                                    <p class="mb-1">Landmark: {{ $item->landmark }}</p>
                                                @endif
                                                <p class="mb-0">Phone: {{ $item->phone }}</p>
                                            </div>
                                            @if (!$item->is_default)
                                                <form action="{{ route('user.address.setDefault', $item->id) }}"
                                                    method="POST" class="mt-2">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-success">Set as
                                                        Default</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        No addresses found. <a href="#" class="alert-link">Add your first address</a>.
                                    </div>
                                </div>
                            @endforelse
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

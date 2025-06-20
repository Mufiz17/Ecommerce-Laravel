@extends('layouts.app')
@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="my-account container">
            <h2 class="page-title">Wishlist</h2>
            <div class="row">
                <div class="col-lg-2">
                    @include('user.nav')
                </div>
                <div class="col-lg-9">
                    <div class="page-content my-account__wishlist">
                        <div class="products-grid row row-cols-2 row-cols-lg-3" id="products-grid">
                            @foreach ($products as $product)
                                <div class="product-card-wrapper">
                                    <div class="product-card mb-3 mb-md-4 mb-xxl-5">
                                        <div class="pc__img-wrapper">
                                            <div class="swiper-container background-img js-swiper-slider"
                                                data-settings='{"resizeObserver": true}'>
                                                <div class="swiper-wrapper">
                                                    <div class="swiper-slide">
                                                        <a
                                                            href="{{ route('shop.product.details', ['product_slug' => $product->slug]) }}"><img
                                                                loading="lazy"
                                                                src="{{ asset('uploads/products') }}/{{ $product->image }}"
                                                                width="330" height="400" alt="{{ $product->name }}"
                                                                class="pc__img"></a>
                                                    </div>
                                                    @foreach (explode(',', $product->images) as $gimg)
                                                        <div class="swiper-slide">
                                                            <a
                                                                href="{{ route('shop.product.details', ['product_slug' => $product->slug]) }}"><img
                                                                    loading="lazy"
                                                                    src="{{ asset('uploads/products') }}/{{ $gimg }}"
                                                                    width="330" height="400" alt="{{ $product->name }}"
                                                                    class="pc__img"></a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <span class="pc__img-prev"><svg width="7" height="11"
                                                        viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg">
                                                        <use href="#icon_prev_sm" />
                                                    </svg></span>
                                                <span class="pc__img-next"><svg width="7" height="11"
                                                        viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg">
                                                        <use href="#icon_next_sm" />
                                                    </svg></span>
                                            </div>
                                            <button class="btn-remove-from-wishlist">
                                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <use href="#icon_close" />
                                                </svg>
                                            </button>
                                        </div>

                                        <div class="pc__info position-relative">
                                            <h6 class="pc__category">{{ $product->category->name }}</h6>
                                            <h6 class="pc__title">{{ $product->name }}</h6>
                                            <div class="product-card__price d-flex">
                                                <span class="money price">
                                                    @if ($product->sale_price)
                                                        <s class="discount">Rp{{ $product->regular_price }}</s>
                                                        Rp{{ $product->sale_price }}
                                                    @else
                                                        <s>Rp{{ $product->regular_price }}</s>
                                                    @endif
                                                </span>
                                            </div>

                                            @if (Cart::instance('wishlist')->content()->where('id', $product->id)->count() > 0)
                                                <form
                                                    action="{{ route('wishlist.delete.qty', ['rowId' => Cart::instance('wishlist')->content()->where('id', $product->id)->first()->rowId]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        class="pc__btn-wl position-absolute top-0 end-0 bg-transparent border-0 js-add-wishlist filled-heart"
                                                        title="Add To Wishlist">
                                                        <svg width="16" height="16" viewBox="0 0 20 20"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <use href="#icon_heart" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

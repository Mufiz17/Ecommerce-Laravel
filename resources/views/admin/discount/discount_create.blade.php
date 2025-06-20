@extends('layouts.admin')
@section('content')
    <div class="main-content">
        <div class="main-content-inner">
            <div class="main-content-wrap">
                <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                    <h3>Discount infomation</h3>
                    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                        <li>
                            <a href="{{ route('admin.index') }}">
                                <div class="text-tiny">Dashboard</div>
                            </a>
                        </li>
                        <li>
                            <i class="icon-chevron-right"></i>
                        </li>
                        <li>
                            <a href="{{ route('admin.discounts') }}">
                                <div class="text-tiny">Discounts</div>
                            </a>
                        </li>
                        <li>
                            <i class="icon-chevron-right"></i>
                        </li>
                        <li>
                            <div class="text-tiny">New Discount</div>
                        </li>
                    </ul>
                </div>
                <div class="wg-box">
                    <form class="form-new-product form-style-1" method="POST" action="{{ route('admin.discount.store') }}">
                        @csrf
                        <fieldset class="name">
                            <div class="body-title">Discount Code <span class="tf-color-1">*</span></div>
                            <input class="flex-grow @error('code') is-invalid @enderror" type="text" placeholder="Discount Code" name="code" tabindex="0"
                                value="{{ old('code') }}" aria-required="true" required="">
                        </fieldset>

                        <fieldset class="category">
                            <div class="body-title">Discount Type</div>
                            <div class="select flex-grow">
                                <select class="@error('type') is-invalid @enderror" name="type">
                                    <option value="">Select</option>
                                    <option value="fixed">Fixed</option>
                                    <option value="percent">Percent</option>
                                </select>
                            </div>
                        </fieldset>

                        <fieldset class="name">
                            <div class="body-title">Value <span class="tf-color-1">*</span></div>
                            <input class="flex-grow @error('value') is-invalid @enderror" type="number" placeholder="Discount Value" name="value" tabindex="0"
                                value="{{ old('value') }}" aria-required="true" required="">
                        </fieldset>

                        <fieldset class="name">
                            <div class="body-title">Cart Value <span class="tf-color-1">*</span></div>
                            <input class="flex-grow @error('cart_value') is-invalid @enderror" type="number" placeholder="Cart Value" name="cart_value"
                                tabindex="0" value="{{ old('cart_value') }}" aria-required="true" required="">
                        </fieldset>

                        <fieldset class="name">
                            <div class="body-title">Expiry Date <span class="tf-color-1">*</span></div>
                            <input class="flex-grow @error('expiry_date') is-invalid @enderror" type="date" placeholder="Expiry Date" name="expiry_date"
                                tabindex="0" value="{{ old('expiry_date') }}" aria-required="true" required="">
                        </fieldset>

                        <div class="bot">
                            <div></div>
                            <button class="tf-button w208" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="bottom-page">
            <div class="body-text">Copyright © 2025 Hztore Promestte</div>
        </div>
    </div>
@endsection

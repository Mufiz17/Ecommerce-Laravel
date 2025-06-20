@extends('layouts.admin')
@section('content')
    <div class="main-content">

        <!-- main-content-wrap -->
        <div class="main-content-inner">
            <!-- main-content-wrap -->
            <div class="main-content-wrap">
                <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                    <h3>Add Product</h3>
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
                            <a href="{{ route('admin.products') }}">
                                <div class="text-tiny">Products</div>
                            </a>
                        </li>
                        <li>
                            <i class="icon-chevron-right"></i>
                        </li>
                        <li>
                            <div class="text-tiny">Edit product</div>
                        </li>
                    </ul>
                </div>
                <!-- form-add-product -->
                <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data"
                    action="{{ route('admin.product.update') }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value="{{ $products->id }}">
                    <div class="wg-box">
                        <fieldset class="name">
                            <div class="body-title mb-10">Product name <span class="tf-color-1">*</span>
                            </div>
                            <input class="mb-10 @error('name') is-invalid @enderror" type="text" placeholder="Enter product name" name="name"
                                tabindex="0" value="{{ $products->name }}" aria-required="true" required="">
                            <div class="text-tiny">Do not exceed 100 characters when entering the
                                product name.</div>
                        </fieldset>

                        <fieldset class="name">
                            <div class="body-title mb-10">Slug <span class="tf-color-1">*</span></div>
                            <input class="mb-10 @error('slug') is-invalid @enderror" type="text" placeholder="Enter product slug" name="slug"
                                tabindex="0" value="{{ $products->slug }}" aria-required="true" required="">
                            <div class="text-tiny">Do not exceed 100 characters when entering the
                                product slug.</div>
                        </fieldset>

                        <div class="gap22 cols">
                            <fieldset class="category">
                                <div class="body-title mb-10">Category <span class="tf-color-1">*</span>
                                </div>
                                <div class="select">
                                    <select class="@error('category_id') is-invalid @enderror" name="category_id">
                                        <option>Choose category</option>
                                        @foreach ($categories as $category)
                                            <option
                                                value="{{ $category->id }}"{{ $products->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </fieldset>
                        </div>

                        <fieldset class="shortdescription">
                            <div class="body-title mb-10">Short Description <span class="tf-color-1">*</span></div>
                            <textarea class="mb-10 ht-150 @error('short_description') is-invalid @enderror" name="short_description" placeholder="Short Description" tabindex="0"
                                aria-required="true" required="">{{ $products->short_description }}</textarea>
                            <div class="text-tiny">Do not exceed 100 characters when entering short description of the
                                product.</div>
                        </fieldset>

                        <fieldset class="description">
                            <div class="body-title mb-10">Description <span class="tf-color-1">*</span>
                            </div>
                            <textarea class="mb-10 @error('description') is-invalid @enderror" name="description" placeholder="Description" tabindex="0" aria-required="true"
                                required="">{{ $products->description }}</textarea>
                            <div class="text-tiny">Do not exceed 100 characters when entering description of
                                product.</div>
                        </fieldset>
                    </div>
                    <div class="wg-box">
                        <fieldset>
                            <div class="body-title">Upload images <span class="tf-color-1">*</span>
                            </div>
                            <div class="upload-image flex-grow">
                                @if ($products->image)
                                    <div class="item" id="imgpreview">
                                        <img src="{{ asset('uploads/products') }}/{{ $products->image }}" class="effect8"
                                            alt="{{ $products->name }}">
                                    </div>
                                @endif
                                <div id="upload-file" class="item up-load">
                                    <label class="uploadfile" for="myFile">
                                        <span class="icon">
                                            <i class="icon-upload-cloud"></i>
                                        </span>
                                        <span class="body-text">Drop your images here or select <span class="tf-color">click
                                                to browse</span></span>
                                        <input type="file" id="myFile" name="image" accept="image/*" class="@error('image') is-invalid @enderror">
                                    </label>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="body-title mb-10">Upload Gallery Images</div>
                            <div class="upload-image mb-16">
                                @if ($products->images)
                                    @foreach (explode(',', $products->images) as $img)
                                        <div class="item gitems">
                                            <img src="{{ asset('uploads/products') }}/{{ trim($img) }}" alt="">
                                        </div>
                                    @endforeach
                                @endif
                                <div id="galUpload" class="item up-load">
                                    <label class="uploadfile" for="gFile">
                                        <span class="icon">
                                            <i class="icon-upload-cloud"></i>
                                        </span>
                                        <span class="text-tiny">Drop your images here or select <span
                                                class="tf-color">click to browse</span></span>
                                        <input type="file" id="gFile" name="images[]" accept="image/*"
                                            multiple="" class="@error('images') is-invalid @enderror">
                                    </label>
                                </div>
                            </div>
                        </fieldset>
                        <div class="cols gap22">
                            <fieldset class="name">
                                <div class="body-title mb-10">Regular Price <span class="tf-color-1">*</span></div>
                                <input class="mb-10 @error('regular_price') is-invalid @enderror" type="number" placeholder="Enter regular price"
                                    name="regular_price" tabindex="0" value="{{ $products->regular_price }}"
                                    aria-required="true" required="">
                            </fieldset>

                            <fieldset class="name">
                                <div class="body-title mb-10">Sale Price <span class="tf-color-1">*</span></div>
                                <input class="mb-10 @error('sale_price') is-invalid @enderror" type="number" placeholder="Enter sale price" name="sale_price"
                                    tabindex="0" value="{{ $products->sale_price }}" aria-required="true"
                                    required="">
                            </fieldset>
                        </div>


                        <div class="cols gap22">
                            <fieldset class="name">
                                <div class="body-title mb-10">SKU <span class="tf-color-1">*</span>
                                </div>
                                <input class="mb-10 @error('SKU') is-invalid @enderror" type="text" placeholder="Enter SKU" name="SKU"
                                    tabindex="0" value="{{ $products->SKU }}" aria-required="true" required="">
                            </fieldset>
                            <fieldset class="name">
                                <div class="body-title mb-10">Quantity <span class="tf-color-1">*</span>
                                </div>
                                <input class="mb-10 @error('quantity') is-invalid @enderror" type="number" placeholder="Enter quantity" name="quantity"
                                    tabindex="0" value="{{ $products->quantity }}" aria-required="true"
                                    required="">
                            </fieldset>
                        </div>

                        <div class="cols gap22">
                            <fieldset class="name">
                                <div class="body-title mb-10">Stock</div>
                                <div class="select mb-10">
                                    <select class="@error('stock_status') is-invalid @enderror" name="stock_status">
                                        <option value="instock"
                                            {{ $products->stock_status == 'instock' ? 'selected' : '' }}>InStock</option>
                                        <option value="outofstock"
                                            {{ $products->stock_status == 'outstock' ? 'selected' : '' }}>Out of Stock
                                        </option>
                                    </select>
                                </div>
                            </fieldset>
                            <fieldset class="name">
                                <div class="body-title mb-10">Featured</div>
                                <div class="select mb-10">
                                    <select class="@error('featured') is-invalid @enderror" name="featured">
                                        <option value="0" {{ $products->featured == 0 ? 'selected' : '' }}>No
                                        </option>
                                        <option value="1" {{ $products->featured == 1 ? 'selected' : '' }}>Yes
                                        </option>
                                    </select>
                                </div>
                            </fieldset>
                        </div>
                        <div class="cols gap10">
                            <button class="tf-button w-full" type="submit">Add product</button>
                        </div>
                    </div>
                </form>
                <!-- /form-add-product -->
            </div>
            <!-- /main-content-wrap -->
        </div>
        <!-- /main-content-wrap -->

        <div class="bottom-page">
            <div class="body-text">Copyright © 2025 Hztore Promestte</div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $("#myFile").on("change", function(e) {
                const photoInp = $("#myFile");
                const [file] = this.files;
                if (file) {
                    $("#imgpreview img").attr('src', URL.createObjectURL(file));
                    $("#imgpreview").show();
                }
            });

            $("#gFile").on("change", function(e) {
                const photoInp = $("#gFile");
                const gphotos = this.files;
                $.each(gphotos, function(key, val) {
                    $("#galUpload").prepend(
                        `<div class="item gitems">
                            <img src="${URL.createObjectURL(val)}"/>
                        </div>`
                    )
                });
            });

            $("input[name='name']").on("change", function() {
                $("input[name='slug']").val(stringToSlug($(this).val()));
            });
        });

        function StringToSlug(Text) {
            return Text.toLowerCase()
                .replace(/[^\w ]+ /g, "")
                .replace(/ +/g, "-");
        }
    </script>
@endpush

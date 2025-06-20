<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Brand;
use App\Models\Order;
use App\Models\Slide;
use App\Models\Product;
use App\Models\Category;
use App\Models\Discount;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{
    public function index()
    {
        $orders = Order::orderBy('created_at', 'DESC')->get()->take(10);
        $dashboardDatas = DB::select("
        SELECT
            SUM(total) AS TotalAmount,
            SUM(IF(status = 'ordered', total, 0)) AS TotalOrderedAmount,
            SUM(IF(status = 'delivered', total, 0)) AS TotalDeliveredAmount,
            SUM(IF(status = 'canceled', total, 0)) AS TotalCanceledAmount,
            COUNT(*) AS Total,
            SUM(IF(status = 'ordered', 1, 0)) AS TotalOrdered,
            SUM(IF(status = 'delivered', 1, 0)) AS TotalDelivered,
            SUM(IF(status = 'canceled', 1, 0)) AS TotalCanceled
        FROM Orders
         ");

        $monthlyDatas = DB::select("
        SELECT
            M.id AS MonthNo,
            M.name AS MonthName,
            IFNULL(D.TotalAmount, 0) AS TotalAmount,
            IFNULL(D.TotalOrderedAmount, 0) AS TotalOrderedAmount,
            IFNULL(D.TotalDeliveredAmount, 0) AS TotalDeliveredAmount,
            IFNULL(D.TotalCanceledAmount, 0) AS TotalCanceledAmount
        FROM month_names M
        LEFT JOIN (
            SELECT
                DATE_FORMAT(created_at, '%b') AS MonthName,
                MONTH(created_at) AS MonthNo,
                SUM(total) AS TotalAmount,
                SUM(IF(status = 'ordered', total, 0)) AS TotalOrderedAmount,
                SUM(IF(status = 'delivered', total, 0)) AS TotalDeliveredAmount,
                SUM(IF(status = 'canceled', total, 0)) AS TotalCanceledAmount
            FROM Orders
            WHERE YEAR(created_at) = YEAR(NOW())
            GROUP BY
                YEAR(created_at),
                MONTH(created_at),
                DATE_FORMAT(created_at, '%b')
        ) D ON D.MonthNo = M.id
         ");

        $AmountM = implode(',', collect($monthlyDatas)->pluck('TotalAmount')->toArray());
        $OrderedAmountM = implode(',', collect($monthlyDatas)->pluck('TotalOrderedAmount')->toArray());
        $DeliveredAmountM = implode(',', collect($monthlyDatas)->pluck('TotalDeliveredAmount')->toArray());
        $CanceledAmountM = implode(',', collect($monthlyDatas)->pluck('TotalCanceledAmount')->toArray());

        $TotalAmount = collect($monthlyDatas)->sum('TotalAmount');
        $TotalOrderedAmount = collect($monthlyDatas)->sum('TotalOrderedAmount');
        $TotalDeliveredAmount = collect($monthlyDatas)->sum('TotalDeliveredAmount');
        $TotalCanceledAmount = collect($monthlyDatas)->sum('TotalCanceledAmount');

        return view('admin.index', compact(
            'orders',
            'dashboardDatas',
            'AmountM',
            'OrderedAmountM',
            'DeliveredAmountM',
            'CanceledAmountM',
            'TotalAmount',
            'TotalOrderedAmount',
            'TotalDeliveredAmount',
            'TotalCanceledAmount'
        ));
    }

    // public function brands()
    // {
    //     $brands = Brand::orderBy('id', 'DESC')->paginate(10);
    //     return view('admin.brand.brands', compact('brands'));
    // }

    // public function brand_create()
    // {
    //     return view('admin.brand.brand_create');
    // }

    // public function brand_store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required',
    //         'slug' => 'required|unique:brands,slug',
    //         'image' => 'mimes:png, jpg, jpeg|max:2048',
    //     ], [
    //         'name.required' => 'Brand name is requires',
    //         'slug.required' => 'Slug is required',
    //         'image.required' => 'Max size is 2MB',
    //     ]);

    //     $brand = new Brand();
    //     $brand->name = $request->name;
    //     $brand->slug = Str::slug($request->name);
    //     $image = $request->file('image');
    //     $file_extention = $request->file('image')->extension();
    //     $file_name = Carbon::now()->timestamp . '.' . $file_extention;
    //     $this->GenerateBrandThumbnails($image, $file_name);
    //     $brand->image = $file_name;
    //     $brand->save();

    //     return redirect()->route('admin.brands')->with('success', 'Succesfully added brand!');
    // }

    // public function brand_edit($id)
    // {
    //     $brand = Brand::find($id);
    //     return view('admin.brand.brand_update', compact('brand'));
    // }

    // public function brand_update(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required',
    //         'slug' => 'required|unique:brands,slug,' . $request->id,
    //         'image' => 'mimes:png, jpg, jpeg|max:2048',
    //     ]);

    //     $brand = Brand::find($request->id);
    //     $brand->name = $request->name;
    //     $brand->slug = Str::slug($request->name);
    //     if ($request->hasFile('image')) {
    //         if (File::exists(public_path('uploads/brands') . '/' . $brand->image)) {
    //             File::delete(public_path('uploads/brands') . '/' . $brand->image);
    //         }
    //         $image = $request->file('image');
    //         $file_extention = $request->file('image')->extension();
    //         $file_name = Carbon::now()->timestamp . '.' . $file_extention;
    //         $this->GenerateBrandThumbnails($image, $file_name);
    //         $brand->image = $file_name;
    //     }
    //     $brand->save();

    //     return redirect()->route('admin.brands')->with('success', 'Succesfully updated brand!');
    // }

    // public function GenerateBrandThumbnails($image, $imageName)
    // {
    //     $location = public_path('uploads/brands');
    //     $img = Image::read($image->path());
    //     $img->cover(124, 124, "top");
    //     $img->resize(124, 124, function ($constraint) {
    //         $constraint->aspectRatio();
    //     })->save($location . '/' . $imageName);
    // }

    // public function brand_destroy($id)
    // {
    //     $brand = Brand::find($id);
    //     if (File::exists(public_path('uploads/brands') . '/' . $brand->image)) {
    //         File::delete(public_path('uploads/brands') . '/' . $brand->image);
    //     }
    //     $brand->delete();
    //     return redirect()->route('admin.brands')->with('success', 'Succesfully updated brand!');
    // }

    public function categories()
    {
        $categories = Category::orderBy('id', 'DESC')->paginate(10);
        return view('admin.category.categories', compact('categories'));
    }

    public function category_create()
    {
        return view('admin.category.category_create');
    }

    public function category_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug',
            'image' => 'mimes:png,jpg,JPEG|max:2048',
        ], [
            'name.required' => 'Category name is requires',
            'slug.required' => 'Slug is required',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $image = $request->file('image');
        $file_extention = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extention;
        $this->GenerateCategoryThumbnails($image, $file_name);
        $category->image = $file_name;
        $category->save();

        return redirect()->route('admin.categories')->with('success', 'Succesfully added category!');
    }

    public function category_edit($id)
    {
        $category = Category::find($id);
        return view('admin.category.category_update', compact('category'));
    }

    public function category_update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $request->id,
            'image' => 'mimes:png, jpg, jpeg|max:2048',
        ]);

        $category = Category::find($request->id);
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        if ($request->hasFile('image')) {
            if (File::exists(public_path('uploads/categories') . '/' . $category->image)) {
                File::delete(public_path('uploads/categories') . '/' . $category->image);
            }
            $image = $request->file('image');
            $file_extention = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extention;
            $this->GenerateCategoryThumbnails($image, $file_name);
            $category->image = $file_name;
        }
        $category->save();

        return redirect()->route('admin.categories')->with('success', 'Succesfully updated category!');
    }

    public function GenerateCategoryThumbnails($image, $imageName)
    {
        $location = public_path('uploads/categories');
        $img = Image::read($image->path());
        $img->cover(124, 124, "top");
        $img->resize(124, 124, function ($constraint) {
            $constraint->aspectRatio();
        })->save($location . '/' . $imageName);
    }

    public function category_destroy($id)
    {
        $category = Category::find($id);
        if (File::exists(public_path('uploads/categories') . '/' . $category->image)) {
            File::delete(public_path('uploads/categories') . '/' . $category->image);
        }
        $category->delete();
        return redirect()->route('admin.categories')->with('success', 'Succesfully updated brand!');
    }

    public function products()
    {
        $products = Product::orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.product.products', compact('products'));
    }

    public function product_create()
    {
        $categories = Category::select('id', 'name')->orderby('name')->get();
        // $brands = Brand::select('id', 'name')->orderby('name')->get();
        return view('admin.product.product_create', compact('categories'));
    }

    public function product_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug',
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'required',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            'quantity' => 'required',
            'image' => 'required|mimes:png,jpg,JPEG|max:2048',
            'category_id' => 'required',
            // 'brand_id' => 'required'
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        // $product->brand_id = $request->brand_id;

        $current_timestamp = Carbon::now()->timestamp;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $current_timestamp . '.' . $image->extension();
            $this->GenerateProductThumbnails($image, $imageName);
            $product->image = $imageName;
        }

        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if ($request->hasFile('images')) {
            $allowedExtention = ['jpg', 'JPEG', 'png'];
            $files = $request->file('images');
            foreach ($files as $file) {
                $gextention = $file->getClientOriginalExtension();
                $gcheck = in_array($gextention, $allowedExtention);
                if ($gcheck) {
                    $gfileName = $current_timestamp . "-" . $counter . "." . $gextention;
                    $this->GenerateProductThumbnails($file, $gfileName);
                    array_push($gallery_arr, $gfileName);
                    $counter = $counter + 1;
                }
            }
            $gallery_images = implode(',', $gallery_arr);
        }
        $product->images = $gallery_images;
        $product->save();

        return redirect()->route('admin.products')->with('success', 'Succesfully added product');
    }

    public function product_edit($id)
    {
        $products = Product::find($id);
        $categories = Category::select('id', 'name')->orderby('name')->get();
        // $brands = Brand::select('id', 'name')->orderby('name')->get();
        return view('admin.product.product_update', compact('categories', 'products'));
    }

    public function product_update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug,' . $request->id,
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'required',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            'quantity' => 'required',
            'image' => 'mimes:png,jpg,JPEG|max:2048',
            'category_id' => 'required',
            // 'brand_id' => 'required'
        ]);

        $product = Product::find($request->id);
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        // $product->brand_id = $request->brand_id;

        $current_timestamp = Carbon::now()->timestamp;

        if ($request->hasFile('image')) {
            if (File::exists(public_path('uploads/products') . '/' . $product->image)) {
                File::delete(public_path('uploads/products') . '/' . $product->image);
            }
            if (File::exists(public_path('uploads/products/thumbnails') . '/' . $product->image)) {
                File::delete(public_path('uploads/products/thumbnails') . '/' . $product->image);
            }
            $image = $request->file('image');
            $imageName = $current_timestamp . '.' . $image->extension();
            $this->GenerateProductThumbnails($image, $imageName);
            $product->image = $imageName;
        }

        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if ($request->hasFile('images')) {
            foreach (explode(',', $product->images) as $oflie) {
                if (File::exists(public_path('uploads/products') . '/' . $oflie)) {
                    File::delete(public_path('uploads/products') . '/' . $oflie);
                }
                if (File::exists(public_path('uploads/products/thumbnails') . '/' . $oflie)) {
                    File::delete(public_path('uploads/products/thumbnails') . '/' . $oflie);
                }
            }
            $allowedExtention = ['jpg', 'JPEG', 'png'];
            $files = $request->file('images');
            foreach ($files as $file) {
                $gextention = $file->getClientOriginalExtension();
                $gcheck = in_array($gextention, $allowedExtention);
                if ($gcheck) {
                    $gfileName = $current_timestamp . "-" . $counter . "." . $gextention;
                    $this->GenerateProductThumbnails($file, $gfileName);
                    array_push($gallery_arr, $gfileName);
                    $counter = $counter + 1;
                }
            }
            $gallery_images = implode(',', $gallery_arr);
        }
        $product->images = $gallery_images;
        $product->save();

        return redirect()->route('admin.products')->with('success', 'Succesfully updated product!');
    }

    public function GenerateProductThumbnails($image, $imageName)
    {
        $thumbnailslocation = public_path('uploads/products/thumbnails');
        $location = public_path('uploads/products');
        $img = Image::read($image->path());

        $img->cover(540, 689, "top");
        $img->resize(540, 689, function ($constraint) {
            $constraint->aspectRatio();
        })->save($location . '/' . $imageName);

        $img->resize(104, 104, function ($constraint) {
            $constraint->aspectRatio();
        })->save($thumbnailslocation . '/' . $imageName);
    }

    public function product_destroy($id)
    {
        $product = Product::find($id);
        if (File::exists(public_path('uploads/products') . '/' . $product->image)) {
            File::delete(public_path('uploads/products') . '/' . $product->image);
        }
        if (File::exists(public_path('uploads/products/thumbnails') . '/' . $product->image)) {
            File::delete(public_path('uploads/products/thumbnails') . '/' . $product->image);
        }

        foreach (explode(',', $product->images) as $ofile) {
            if (File::exists(public_path('uploads/products') . '/' . $ofile)) {
                File::delete(public_path('uploads/products') . '/' . $ofile);
            }
            if (File::exists(public_path('uploads/products/thumbnails') . '/' . $ofile)) {
                File::delete(public_path('uploads/products/thumbnails') . '/' . $ofile);
            }
        }
        $product->delete();
        return redirect()->route('admin.products')->with('success', 'Succesfully deleted product!');
    }

    public function discounts()
    {
        $discounts = Discount::orderBy('expiry_date', 'DESC')->paginate('10');
        return view('admin.discount.discount', compact('discounts'));
    }

    public function discount_create()
    {
        return view('admin.discount.discount_create');
    }

    public function discount_store(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'type' => 'required',
            'value' => 'required|numeric',
            'cart_value' => 'required|numeric',
            'expiry_date' => 'required|date',
        ]);

        $discount = new Discount;
        $discount->code = $request->code;
        $discount->type = $request->type;
        $discount->value = $request->value;
        $discount->cart_value = $request->cart_value;
        $discount->expiry_date = $request->expiry_date;
        $discount->save();
        return redirect()->route('admin.discounts')->with('success', 'Succesfully added discount');
    }

    public function discount_edit($id)
    {
        $discount = Discount::find($id);
        return view('admin.discount.discount_update', compact('discount'));
    }

    public function discount_update(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'type' => 'required',
            'value' => 'required|numeric',
            'cart_value' => 'required|numeric',
            'expiry_date' => 'required|date',
        ]);

        $discount = Discount::find($request->id);
        $discount->code = $request->code;
        $discount->type = $request->type;
        $discount->value = $request->value;
        $discount->cart_value = $request->cart_value;
        $discount->expiry_date = $request->expiry_date;
        $discount->save();
        return redirect()->route('admin.discounts')->with('success', 'Succesfully updated discount');
    }

    public function discount_destroy($id)
    {
        $discount = Discount::find($id);
        $discount->delete();
        return redirect()->route('admin.categories')->with('success', 'Succesfully deleted discount!');
    }

    public function orders()
    {
        $orders = Order::orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.order.orders', compact('orders'));
    }

    public function orders_details($order_id)
    {
        $order = Order::find($order_id);
        $orderItems = OrderItem::where('order_id', $order_id)->orderBy('id')->paginate(10);
        $transaction = Transaction::where('order_id', $order_id)->first();
        return view('admin.order.details', compact('order', 'orderItems', 'transaction'));
    }

    public function update_status(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->status = $request->order_status;
        if ($request->order_status == 'delivered') {
            $order->delivered_date = Carbon::now();
        } elseif ($request->order_status == 'canceled') {
            $order->canceled_date = Carbon::now();
        }
        $order->save();

        if ($request->order_status == 'delivered') {
            $transaction = Transaction::where('order_id', $request->order_id)->first();
            $transaction->status = 'approved';
            $transaction->save();
        }
        return back()->with('success', 'Status change successfully!');
    }

    public function slides()
    {
        $slides = Slide::orderBy('id', 'DESC')->paginate(10);
        return view('admin.slide.slides', compact('slides'));
    }

    public function slide_create()
    {
        return view('admin.slide.create');
    }

    public function slide_store(Request $request)
    {
        $request->validate([
            'tagline' => 'required',
            'title' => 'required',
            'subtitle' => 'required',
            'link' => 'required',
            'image' => 'required|mimes:png,jpg,JPEG|max:2048',
            'status' => 'required'
        ]);

        $slide = new Slide();
        $slide->tagline = $request->tagline;
        $slide->title = $request->title;
        $slide->subtitle = $request->subtitle;
        $slide->link = $request->link;
        $slide->status = $request->status;

        $image = $request->file('image');
        $file_extention = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extention;
        $this->GenerateSlidesThumbnailsImage($image, $file_name);
        $slide->image = $file_name;
        $slide->save();
        return redirect()->route('admin.slides')->with('success', 'Succesfully added slider');
    }

    public function GenerateSlidesThumbnailsImage($image, $imageName)
    {
        $location = public_path('uploads/slides');
        $img = Image::read($image->path());
        $img->cover(400, 690, "top");
        $img->resize(400, 690, function ($constraint) {
            $constraint->aspectRatio();
        })->save($location . '/' . $imageName);
    }

    public function slide_edit($id)
    {
        $slide = Slide::find($id);
        return view('admin.slide.edit', compact('slide'));
    }

    public function slide_update(Request $request)
    {
        $request->validate([
            'tagline' => 'required',
            'title' => 'required',
            'subtitle' => 'required',
            'link' => 'required',
            'image' => 'mimes:png,jpg,JPEG|max:2048',
            'status' => 'required'
        ]);

        $slide = Slide::find($request->id);
        $slide->tagline = $request->tagline;
        $slide->title = $request->title;
        $slide->subtitle = $request->subtitle;
        $slide->link = $request->link;
        $slide->status = $request->status;

        if ($request->hasFile('image')) {
            if (File::exists(public_path('uploads/slides') . '/' . $slide->image)) {
                File::delete(public_path('uploads/slides') . '/' . $slide->image);
            }
            $image = $request->file('image');
            $file_extention = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extention;
            $this->GenerateSlidesThumbnailsImage($image, $file_name);
            $slide->image = $file_name;
        }
        $slide->save();
        return redirect()->route('admin.slides')->with('success', 'Succesfully updated slider');
    }

    public function slide_destroy($id)
    {
        $slide = Slide::find($id);
        if (File::exists(public_path('uploads/slides') . '/' . $slide->image)) {
            File::delete(public_path('uploads/slides') . '/' . $slide->image);
        }
        $slide->delete();
        return redirect()->route('admin.slides')->with('success', 'Succesfully deleted slider!');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $result = Product::where('name', 'LIKE', "%{$query}%")->get()->take(8);
        return response()->json($result);
    }

    public function users()
    {
        $users = User::get();
        return view('admin.user.user', compact('users'));
    }

    public function setting()
    {
        $user = Auth::user();
        // dd($user);
        return view('admin.user.setting', compact('user'));
    }

    public function setting_update(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah.']);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('admin.index')->with('success', 'Profil & Password berhasil diperbarui.');
    }
}

<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

    public function orders()
    {
        $orders = Order::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate(10);
        return view('user.order.orders', compact('orders'));
    }

    public function order_details($order_id)
    {
        $order = Order::where('user_id', Auth::user()->id)->where('id',  $order_id)->first();
        if ($order) {
            $orderItems = OrderItem::where('order_id', $order_id)->orderBy('id')->paginate(10);
            $transaction = Transaction::where('order_id', $order_id)->first();
            return view('user.order.details', compact('order', 'orderItems', 'transaction'));
        } else {
            return redirect()->route('login');
        }
    }

    public function order_cancel(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->status = 'canceled';
        $order->canceled_date = Carbon::now();
        $order->save();
        return back()->with('success', 'Order has been canceled!');
    }

    public function wishlist()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $wishlistItems = Cart::instance('wishlist')->content();

        // Ambil ID produk dari wishlist
        $productIds = $wishlistItems->pluck('id')->toArray();

        // Ambil produk berdasarkan ID yang ada di wishlist
        $products = Product::whereIn('id', $productIds)->orderBy('name', 'DESC')->get();

        return view('user.wishlist', compact('wishlistItems', 'products', 'categories'));
    }

    public function setting()
    {
        $user = Auth::user();
        // dd($user);
        return view('user.detail', compact('user'));
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

        return redirect()->route('user.index')->with('success', 'Profil & Password berhasil diperbarui.');
    }

    public function address()
    {
        $user_id = Auth::user()->id;
        $address = Address::where('user_id', $user_id)->get();
        return view('user.address.address', compact('address'));
    }

    public function address_create()
    {
        return view('user.address.add');
    }

    public function address_store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'phone' => 'required|numeric|digits:10',
            'zip' => 'required|numeric|digits:6',
            'state' => 'required',
            'city' => 'required',
            'address' => 'required',
            'locality' => 'required',
            'landmark' => 'required',
        ]);

        $address = new Address();
        $address->user_id = Auth::user()->id;
        $address->name = $request->name;
        $address->phone = $request->phone;
        $address->zip = $request->zip;
        $address->state = $request->state;
        $address->city = $request->city;
        $address->address = $request->address;
        $address->locality = $request->locality;
        $address->landmark = $request->landmark;
        $address->country = 'Indonesia';
        $address->is_default = true;

        $address->save();

        return redirect()->route('user.address')->with('success', 'Address successfully added!');
    }

    public function setDefault($id)
    {
        $userId = Auth::id();
        Address::where('user_id', $userId)->update(['is_default' => 0]);
        Address::where('id', $id)->where('user_id', $userId)->update(['is_default' => 1]);

        return redirect()->back()->with('success', 'Alamat default berhasil diubah.');
    }
}

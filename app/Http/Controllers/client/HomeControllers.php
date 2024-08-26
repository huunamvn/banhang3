<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Models\catelogue;
use App\Models\product;
use Illuminate\Http\Request;
use App\Models\Bill;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HomeControllers extends Controller
{
    // Hiển thị trang chính
    public function index()
    {
        $products = product::query()->latest('id')->paginate(8);
        $catelogue = catelogue::query()->get();
        return view('Client.dashboard', compact('products', 'catelogue'));
    }

    // Hiển thị danh sách sản phẩm của danh mục
    public function create(string $id = null)
    {
        $catelogue = catelogue::query()->get();
        $catelogues = catelogue::query()->findOrFail($id);
        return view('Client.users.CategorieProduct', compact('catelogues', 'catelogue'));
    }

    // Hiển thị chi tiết sản phẩm
    public function product(string $id)
    {
        $catelogue = catelogue::query()->get();
        $products = product::query()->findOrFail($id);
        return view('client.users.product-detal', compact('products', 'catelogue'));
    }

    // Hiển thị giỏ hàng
    public function listCart()
    {
        $cart = session()->get('cart', []);
        $catelogue = catelogue::query()->get();
        $customer = Auth::user(); // Hoặc cách lấy thông tin khách hàng phù hợp

        return view('client.users.cart', compact('catelogue', 'cart', 'customer'));
    }

    // Thêm sản phẩm vào giỏ hàng
    public function cart(Request $request)
    {
        if (!Auth::check()) {
            // Người dùng chưa đăng nhập, chuyển hướng đến trang đăng nhập hoặc thông báo lỗi
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng.');
        }
    
        $product_id = $request->input('id_product');
        $quantity = $request->input('quantity');
        $product = product::query()->findOrFail($product_id);
        
        $cart = session()->get('cart', []);
        if (isset($cart[$product_id])) {
            $cart[$product_id]['quantity'] += $quantity;
        } else {
            $cart[$product_id] = [
                'name' => $product->name,
                'quantity' => $quantity,
                'price_regular' => isset($product->price_sale) ? $product->price_sale : $product->price_regular,
                'img_thumbnail' => $product->img_thumbnail
            ];
        }
        session()->put('cart', $cart);
        return redirect()->route('client.listCart')->with('success', 'Thêm vào giỏ hàng thành công');
    }
    
// Xóa giỏ hàng
    public function destroy(string $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            return redirect()->route('client.listCart')->with('success', 'Sản phẩm đã được xóa thành công');
        }

        return redirect()->route('client.listCart')->with('error', 'Sản phẩm không tìm thấy trong giỏ hàng');
    }
// 
    public function checkout()
    {
        $cartItems = session()->get('cart', []);
        $subtotal = array_sum(array_map(function($item) {
            return $item['quantity'] * $item['price_regular'];
        }, $cartItems));
    
        // Get customer information
        $customer = auth()->user(); // Hoặc cách lấy thông tin khách hàng phù hợp
    
        return view('client.users.checkout', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'customer' => $customer
        ]);
    }

    // Xử lý thanh toán
    public function processCheckout(Request $request)
    {
        // Validate the request
        $request->validate([
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'shipping' => 'required|numeric',
        ]);
    
        // Get cart items from session
        $cartItems = session()->get('cart', []);
        $subtotal = array_sum(array_map(function($item) {
            return $item['quantity'] * $item['price_regular'];
        }, $cartItems));
    
        $shippingCost = $request->input('shipping');
        $total = $subtotal + $shippingCost;
    
        // Save the bill
        $bill = new Bill();
        $bill->user_id = auth()->id();
        $bill->name = $request->input('name');
        $bill->email = $request->input('email');
        $bill->phone = $request->input('phone');
        $bill->address = $request->input('address');
        $bill->subtotal = $subtotal;
        $bill->shipping_cost = $shippingCost;
        $bill->total = $total;
        $bill->items = json_encode($cartItems);
        $bill->save();
    
        // Clear the cart
        session()->forget('cart');
    
        // Redirect to checkout success page with bill ID
        return redirect()->route('client.checkout.success', ['bill_id' => $bill->id])->with('success', 'Thanh toán thành công!');
    }
    
    // Hiển thị hóa đơn thành công
    public function checkoutSuccess(Request $request)
    {
        $billId = $request->query('bill_id'); // Lấy ID hóa đơn từ query string
        $bill = Bill::find($billId);

        if (!$bill) {
            return redirect()->route('client.index')->with('error', 'Không tìm thấy hóa đơn.');
        }

        return view('client.users.checkout-success', compact('bill'));
    }

    // Hiển thị hóa đơn để in
    public function printBill()
    {
        // Lấy thông tin hóa đơn từ cơ sở dữ liệu
        $bill = Bill::where('user_id', Auth::id())->latest()->first();
    
        if (!$bill) {
            return redirect()->route('client.listCart')->with('error', 'Không tìm thấy hóa đơn!');
        }
    
        $cart = json_decode($bill->items, true);
    
        return view('client.users.print-bill', [
            'cart' => $cart,
            'subtotal' => $bill->subtotal,
            'shipping_cost' => $bill->shipping_cost,
            'total' => $bill->total
        ]);
    }
    public function showBill($billId)
{
    // Lấy thông tin hóa đơn từ cơ sở dữ liệu
    $bill = Bill::find($billId);

    if (!$bill) {
        return redirect()->route('client.index')->with('error', 'Hóa đơn không tìm thấy.');
    }

    $cart = json_decode($bill->items, true);

    return view('client.users.view-bill', [
        'bill' => $bill,
        'cart' => $cart
    ]);
}
public function showOrder()
{
    $orders = Bill::where('user_id', Auth::id())->get(); // Lấy tất cả đơn hàng của người dùng hiện tại

    return view('client.users.orders', compact('orders'));
}
public function showOrders()
    {
        // Lấy tất cả đơn hàng của người dùng hiện tại
        $orders = Bill::where('user_id', Auth::id())->get();

        // Trả về view với danh sách đơn hàng
        return view('client.users.orders', compact('orders'));
    }
    
    public function orderDetails($id)
    {
        // Tìm đơn hàng theo ID và đảm bảo nó thuộc về người dùng hiện tại
        $order = Bill::where('user_id', Auth::id())->where('id', $id)->first();

        if (!$order) {
            return redirect()->route('client.client.showOrders')->with('error', 'Đơn hàng không tìm thấy.');
        }

        // Trả về view với thông tin đơn hàng
        return view('client.users.order-details', compact('order'));
    }


}

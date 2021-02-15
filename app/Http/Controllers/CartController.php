<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\UserItem;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
        $this->user = Auth::user();
        return $next($request);
        });
    }
    public function index(Request $request) {
        $data = [];
        if ($request->session()->has('user_items')) {
        $user_items = UserItem::sessionValues($request);
        $items = Item::whereIn('id', array_keys($user_items))->get();
        $total_price = UserItem::calculateTotal($request);
        $data = [
        'user_items' => $user_items,
        'items' => $items,
        'total_price' => $total_price,
        ];
        }
        return view('cart.index', $data);
    }
    public function add(Request $request)
    {
        $item = Item::find($request->id);
        if ($item->id) UserItem::addCart($request, $this->user, $item);
        return redirect()->route('cart.index');
    }
    public function remove(Request $request)
    {
        $item = Item::find($request->id);
        UserItem::removeCart($request, $this->user, $item);
        return redirect()->route('cart.index');
    }
    public function clear(Request $request)
    {
        UserItem::clearCart($request);
        return redirect()->route('cart.index');
    }

    public function updates(Request $request)
    {
        UserItem::updatesCart($request, $this->user);
        return redirect()->route('cart.index');
    }

    public function confirm(Request $request){
        $data = [];
        return view('cart.comfirm', $data);
    }
   
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index() { return view('admin.coupons.index', ['coupons' => Coupon::latest()->paginate(20)]); }
    public function create() { return view('admin.coupons.create'); }
    public function store(Request $request)
    {
        $request->validate(['code' => 'required|unique:coupons', 'type' => 'required|in:percentage,fixed', 'value' => 'required|numeric|min:0']);
        Coupon::create($request->except('_token'));
        return redirect()->route('admin.coupons.index');
    }
    public function edit(Coupon $coupon) { return view('admin.coupons.edit', compact('coupon')); }
    public function update(Request $request, Coupon $coupon) { $coupon->update($request->except('_token', '_method')); return redirect()->route('admin.coupons.index'); }
    public function destroy(Coupon $coupon) { $coupon->delete(); return redirect()->route('admin.coupons.index'); }
}

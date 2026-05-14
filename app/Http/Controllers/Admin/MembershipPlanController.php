<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipPlan;
use Illuminate\Http\Request;

class MembershipPlanController extends Controller
{
    public function index() { return view('admin.membership.plans', ['plans' => MembershipPlan::orderBy('sort_order')->get()]); }
    public function create() { return view('admin.membership.create'); }
    public function store(Request $request)
    {
        MembershipPlan::create($request->except('_token'));
        return redirect()->route('admin.membership-plans.index');
    }
    public function edit(MembershipPlan $membershipPlan) { return view('admin.membership.edit', compact('membershipPlan')); }
    public function update(Request $request, MembershipPlan $membershipPlan) { $membershipPlan->update($request->except('_token', '_method')); return redirect()->route('admin.membership-plans.index'); }
    public function destroy(MembershipPlan $membershipPlan) { $membershipPlan->delete(); return redirect()->route('admin.membership-plans.index'); }
}

<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\AdImpression;
use Illuminate\Http\Request;

class AdController extends Controller
{
    public function click(Request $request, Ad $ad)
    {
        AdImpression::create([
            'ad_id'      => $ad->id,
            'is_click'   => true,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id'    => auth()->id(),
        ]);

        $ad->increment('clicks_count');

        return response()->json(['url' => $ad->click_url]);
    }
}

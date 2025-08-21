<?php
// app/Http/Controllers/Api/V1/VisualSearchController.php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\VisualMatchResource;
use App\Services\ImageSearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VisualSearchController extends Controller
{
    public function search(Request $request)
    {
        $t0 = microtime(true);
        $validated = $request->validate([
            'image'         => ['required','file','mimetypes:image/jpeg,image/png,image/webp','max:4096'],
            'top_k'         => ['sometimes','integer','min:1','max:50'],
            'max_distance'  => ['sometimes','integer','min:0','max:64'],
        ]);

        $topK        = (int) ($validated['top_k'] ?? 20);
        $maxDistance = (int) ($validated['max_distance'] ?? 18);

        // Read uploaded image and compute hash
        $uploadedPath = $request->file('image')->getRealPath();
        $queryHash    = app(ImageSearchService::class)->computeHashBits($uploadedPath);

        // Fetch candidate products (with images)
        $products = DB::table('product')
            ->select('productid','name','price','imageurl','farmerid','category','description','stockquantity')
            ->whereNotNull('imageurl')
            ->where('imageurl','!=','')
            ->orderByDesc('productid')
            ->get();

        // Score candidates
        $service  = app(ImageSearchService::class);
        $cands    = [];
        foreach ($products as $p) {
            $absPath = $service->publicPathFromImageUrl($p->imageurl);
            if (!$absPath || !is_file($absPath)) {
                continue; // skip missing files
            }
            $phash = $service->getProductHashBitsCached($p->productid, $absPath);
            if (!$phash) continue;

            $dist  = $service->hammingDistance($queryHash, $phash);
            if ($dist <= $maxDistance) {
                $cands[] = [
                    'product'   => $p,
                    'distance'  => $dist,
                    'similarity'=> max(0, 1 - ($dist / 64.0)),
                ];
            }
        }

        // Sort by distance ascending; take topK
        usort($cands, fn($a,$b) => $a['distance'] <=> $b['distance']);
        $best = array_slice($cands, 0, $topK);

        $took = (int) round((microtime(true) - $t0) * 1000);

        return VisualMatchResource::collection(collect($best))->additional([
            'meta' => [
                'ok'             => true,
                'query_bits'     => 64,
                'top_k'          => $topK,
                'max_distance'   => $maxDistance,
                'total_candidates'=> $products->count(),
                'returned'       => count($best),
                'took_ms'        => $took,
            ]
        ]);
    }
}

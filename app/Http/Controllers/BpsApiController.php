<?php

namespace App\Http\Controllers;

use App\Services\BpsApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BpsApiController extends Controller
{
    protected BpsApiService $bpsService;

    public function __construct(BpsApiService $bpsService)
    {
        $this->bpsService = $bpsService;
    }

    /**
     * Endpoint API publikasi BPS (mendukung pencarian, tahun, halaman)
     */
    public function getPublikasi(Request $request): JsonResponse
    {
        $page    = (int) $request->input('page', 1);
        $keyword = $request->input('keyword');
        $year    = $request->filled('year') ? (int) $request->input('year') : null;
        $lang    = $request->input('lang', 'ind');

        $result = $this->bpsService->getPublications($page, $lang, $keyword, $year);

        return response()->json($result);
    }

    /**
     * Endpoint API indikator strategis BPS Kabupaten Manggarai
     */
    public function getIndikator(): JsonResponse
    {
        $indicators = $this->bpsService->getStrategicIndicators();
        return response()->json([
            'status' => 'success',
            'data'   => $indicators,
        ]);
    }

    /**
     * Uji koneksi ke WebAPI resmi BPS
     */
    public function testConnection(Request $request): JsonResponse
    {
        $apiKey = $request->input('api_key') ?: $this->bpsService->getApiKey();
        $domain = $request->input('domain') ?: $this->bpsService->getDomain();

        if (!$apiKey) {
            return response()->json([
                'status'  => 'error',
                'message' => 'API Key BPS belum ditentukan. Silakan masukkan API Key Anda dari webapi.bps.go.id/developer.',
            ], 422);
        }

        try {
            $url = "https://webapi.bps.go.id/v1/api/list/model/publication/domain/{$domain}/page/1/lang/ind/key/{$apiKey}/";
            $response = Http::timeout(8)->get($url);

            if ($response->successful()) {
                $json = $response->json();
                $avail = $json['data-availability'] ?? 'not-available';

                return response()->json([
                    'status'    => 'success',
                    'message'   => 'Koneksi ke WebAPI BPS berhasil! Ketersediaan data: ' . $avail,
                    'domain'    => $domain,
                    'raw_stats' => $json['data'][0] ?? null,
                ]);
            }

            return response()->json([
                'status'  => 'warning',
                'message' => 'WebAPI BPS merespons dengan status HTTP ' . $response->status(),
            ], $response->status());
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menghubungi WebAPI BPS: ' . $e->getMessage(),
            ], 500);
        }
    }
}

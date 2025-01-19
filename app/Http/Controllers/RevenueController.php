<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowRevenueRequest;
use App\Http\Resources\RevenueResource;
use App\Models\Extraction;
use App\Models\Revenue;
use App\Traits\ConvertMoneyTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RevenueController extends Controller
{
    use ConvertMoneyTrait;

    /**
     * Display the specified resource.
     */
    public function show(ShowRevenueRequest $request): JsonResponse
    {
        $response = $this->response();

        try {
            $validated = $request->validated();

            $extraction = Extraction::latest()->firstOrFail();

            $billions = $validated['billions'] ?? null;
            $range = $validated['range'] ?? null;

            $revenue = Revenue::whereExtractionId($extraction->id)
                ->when($validated['rule'] == 'greater', function ($query) use ($billions) {
                    return $query->where('profit', '>=', $billions);
                })
                ->when($validated['rule'] == 'smaller', function ($query) use ($billions) {
                    return $query->where('profit', '<=', $billions);
                })
                ->when($validated['rule'] == 'between', function ($query) use ($range) {
                    $min = $this->convertToFloat(min($range));
                    $max = $this->convertToFloat(max($range));

                    return $query->whereBetween('profit', [$min, $max]);
                })
                ->get();

            $response->status = Response::HTTP_OK;
            $response->success = true;
            $response->data = RevenueResource::collection($revenue);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            $response->errors = $e->getMessage();
        } finally {
            return response()->json($response, $response->status, $this->headers());
        }
    }
}

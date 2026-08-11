<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class ReturnitemController extends Controller
{
    public function saveReturns(Request $request)
    {
        $validated = $request->validate([
            'return_no' => 'required|string',
            'invoiceNumber' => 'required|string',
            'serialNo' => 'required|string',
            'product_id' => 'required|integer',
            'product_name' => 'required|string',
            'created_by' => 'nullable|string',
            'remarks' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $id = DB::table('returnitems')->insertGetId([
            'return_no' => $validated['return_no'],
            'invoiceNumber' => $validated['invoiceNumber'],
            'serialNo' => $validated['serialNo'],
            'product_id' => $validated['product_id'],
            'product_name' => $validated['product_name'],
            'created_by' => $validated['created_by'] ?? (string) auth()->id(),
            'remarks' => $validated['remarks'] ?? '',
            'status' => $validated['status'] ?? 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['id' => $id], 201);
    }

    public function getReturns(Request $request)
    {
        $returns = DB::table('returnitems')->orderBy('id', 'DESC')->get();

        return response()->json($returns);
    }

    public function updateReturns(Request $request, $id)
    {
        $return = DB::table('returnitems')->where('id', $id)->first();
        if (! $return) {
            return response()->json(['error' => 'Return not found'], 404);
        }

        $data = $request->only(['remarks', 'status']);
        $newStatus = $data['status'] ?? null;
        $alreadyCompleted = $return->status === 'completed' || $return->completed_at;

        if ($newStatus === 'completed' && $alreadyCompleted) {
            return response()->json(['error' => 'Return already completed'], 422);
        }

        $becomingCompleted = $newStatus === 'completed' && ! $alreadyCompleted;

        try {
            DB::transaction(function () use ($id, $return, $data, $becomingCompleted) {
                if ($becomingCompleted) {
                    if ($return->completed_at) {
                        abort(422, 'Return already completed');
                    }

                    $product = DB::table('products')->where('id', $return->product_id)->lockForUpdate()->first();
                    if ($product) {
                        DB::table('products')->where('id', $return->product_id)->update([
                            'product_quantity' => (int) $product->product_quantity + 1,
                            'updated_at' => now(),
                        ]);
                    }

                    if ($return->serialNo) {
                        DB::table('serialnumbers')
                            ->where('serial_number', $return->serialNo)
                            ->where('product_id', $return->product_id)
                            ->update([
                                'status' => 'available',
                                'sold_date' => null,
                                'updated_at' => now(),
                            ]);
                    }

                    $data['completed_at'] = now();
                }

                $data['updated_at'] = now();
                DB::table('returnitems')->where('id', $id)->update($data);
            });
        } catch (HttpException $e) {
            if ($e->getStatusCode() === 422) {
                return response()->json(['error' => $e->getMessage()], 422);
            }

            throw $e;
        } catch (Throwable $e) {
            if (str_contains($e->getMessage(), 'already completed')) {
                return response()->json(['error' => $e->getMessage()], 422);
            }

            throw $e;
        }

        return response()->json(['ok' => true]);
    }
}

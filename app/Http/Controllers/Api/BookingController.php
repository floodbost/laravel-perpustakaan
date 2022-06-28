<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Booking",
 *     description="Api untuk menampilan daftar buku yg telah di book"
 * )
 *
 */
class BookingController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/booking",
     *     summary="Melihat buku yang telah di pinjam",
     *     description="elihat buku yang telah di pinjam. api ini khusus untuk role user",
     *     tags={"Booking"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $booking = Booking::with('user')
            ->with('book')
            ->paginate();
        return response()->json($booking);
    }


}

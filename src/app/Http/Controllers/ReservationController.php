<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;

class ReservationController extends Controller
{
    // 予約登録
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'shop_id' => 'required|exists:shops,id',
            'reservation_time' => 'required|date_format:Y-m-d H:i:s',
            'reservation_number' => 'required|integer|min:1',
        ]);

        $reservation = Reservation::create($validatedData);

        return response()->json(['message' => 'Reservation created successfully', 'reservation' => $reservation], 201);
    }

    // 予約削除
    public function destroy($id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        $reservation->delete();

        return response()->json(['message' => 'Reservation deleted successfully'], 200);
    }
}

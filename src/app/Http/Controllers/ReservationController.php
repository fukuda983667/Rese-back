<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Carbon\Carbon;

class ReservationController extends Controller
{
    // vendorユーザが予約情報を取得する
    public function getReservationsByShop(Request $request, $shop_id)
    {
        $date = Carbon::parse($request->query('date', Carbon::now()->toDateString()))->toDateString(); // 日付部分のみを取得

        $reservations = Reservation::where('shop_id', $shop_id)
            ->whereDate('reservation_time', $date) // 日付でフィルタリング
            ->orderBy('reservation_time', 'asc') // 予約時間を早い順に並び替え
            ->get();

        return response()->json(['reservations' => $reservations], 200);
    }


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

    // 予約内容変更
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'shop_id' => 'required|exists:shops,id',
            'reservation_time' => 'required|date_format:Y-m-d H:i:s',
            'reservation_number' => 'required|integer|min:1',
        ]);

        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        $reservation->update($validatedData);

        return response()->json(['message' => 'Reservation updated successfully'], 200);
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

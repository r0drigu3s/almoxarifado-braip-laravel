<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Equipment;
use Illuminate\Http\Request;

class BookingController extends Controller
{
	// REGRAS DE NEGÓCIO
	// realizar transação apenas se o Equipment estiver com status 'Disponível'
	public function transaction(Request $request)
	{
		$user = auth()->user();
		$equipment = Equipment::findOrFail($request->id);

		if ($equipment->status == 'Disponível') {
			$equipment->status = 'Indisponível';

			$booking = new Booking();
			$booking->user_id = $user->id;
			$booking->user_name = $user->name;
			$booking->equipment_id = $equipment->id;
			$booking->equipment_type = $equipment->type;
			$booking->bookingDate = now();
			$booking->transaction = 'Reserva';

			echo $equipment->type;
			$equipment->save();
			$booking->save();
		} else {
			return;
		}
	}

	// alterar status do Equipment para 'Disponível' quando reserva for cancelada
	public function cancelBooking(Request $request)
	{
		$user = auth()->user();
		$equipment = Equipment::findOrFail($request->id);

		if ($equipment->status == 'Indisponível') {
			$equipment->status = 'Disponível';

			$booking = new Booking();
			$booking->user_id = $user->id;
			$booking->user_name = $user->name;
			$booking->equipment_id = $equipment->id;
			$booking->equipment_type = $equipment->type;
			$booking->bookingDate = now();
			$booking->transaction = 'Devolução';

			echo $equipment->type;
			$equipment->save();
			$booking->save();
		} else {
			return;
		}
	}
	//======================================================================

	// CRUD
	/**
	 * Mostra todas as reservas.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$bookings = Booking::all();

		return response()->json($bookings);
	}

	/**
	 * Mostra uma reserva específica.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$booking = Booking::findOrFail($id);

		return response()->json($booking);
	}

	/**
	 * Salva uma nova reserva.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$booking = new Booking();
		$booking->user_id = $request->user_id;
		$booking->equipment_id = $request->equipment_id;
		$booking->bookingDate = $request->bookingDate;
		$booking->transaction = $request->transaction; // Entrada ou Saída
		$booking->save();

		return response()->json($booking);
	}

	/**
	 * Atualiza uma reserva específica.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$booking = Booking::findOrFail($id);
		$booking->user_id = $request->user_id;
		$booking->equipment_id = $request->equipment_id;
		$booking->date = $request->date;
		$booking->transaction = $request->transaction; // Entrada ou Saída
		$booking->save();

		return response()->json($booking);
	}

	/**
	 * Deleta uma reserva específica.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$booking = Booking::findOrFail($id);
		$booking->delete();

		return response()->json($booking);
	}
}

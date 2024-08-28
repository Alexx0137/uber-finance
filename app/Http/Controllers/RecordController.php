<?php

namespace App\Http\Controllers;

use App\Models\Record;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class RecordController extends Controller
{
    public function index()
    {
        $records = Record::all();
        $weeklyTotals = $this->getWeeklyTotals();

        return view('index', [
            'records' => $records,
            'record' => null,
            'weeklyTotals' => $weeklyTotals
        ]);
    }



    public function create()
    {
        // Pasar todos los registros a la vista y una variable para crear un nuevo registro
        return view('index', [
            'records' => Record::all(),
            'record' => null // Pasar un valor null para indicar que estamos creando un nuevo registro
        ]);
    }


    // Almacenar un nuevo registro
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'mileage' => 'nullable|numeric',
            'fuel_cost' => 'nullable|numeric',
            'driving_hours' => 'nullable|numeric',
            'total_income' => 'nullable|numeric',
            'cash_income' => 'nullable|numeric',
            'nequi_income' => 'nullable|numeric',
        ]);

        $record = new Record($request->all());
//        $record->date = now(); // Asignar la fecha actual
        $record->hourly_rate = $request->total_income / max($request->driving_hours, 1); // Evitar división por cero
        $record->save();

        return redirect()->route('index')->with('success', 'Registro creado con éxito.');
    }

    // Mostrar el formulario para editar un registro
    public function edit($id)
    {
        $record = Record::findOrFail($id);
        $weeklyTotals = $this->getWeeklyTotals();

        return view('index', [
            'records' => Record::all(),
            'record' => $record,
            'weeklyTotals' => $weeklyTotals
        ]);
    }


    // Actualizar un registro
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'mileage' => 'required|numeric',
            'fuel_cost' => 'required|numeric',
            'driving_hours' => 'required|numeric',
            'total_income' => 'required|numeric',
            'cash_income' => 'required|numeric',
            'nequi_income' => 'required|numeric',
        ]);

        $record = Record::findOrFail($id);
        $record->update($request->all());
        $record->hourly_rate = $request->total_income / max($request->driving_hours, 1); // Evitar división por cero
        $record->save();

        return redirect()->route('index')->with('success', 'Registro actualizado con éxito.');
    }

    // Eliminar un registro
    public function destroy($id): RedirectResponse
    {
        $record = Record::findOrFail($id);
        $record->delete();

        return redirect()->route('index')->with('success', 'Registro eliminado con éxito.');
    }

    private function getWeeklyTotals()
    {
        return DB::table('records')
            ->select(DB::raw('DATE_TRUNC(\'week\', created_at) as week,
                MIN(created_at) as start_date,
                MAX(created_at) as end_date,
                SUM(total_income) as total_facturado,
                SUM(cash_income) as efectivo,
                SUM(nequi_income) as nequi,
                SUM(total_income) - SUM(cash_income) - SUM(nequi_income) as uber,
                SUM(total_income) * 0.1 as carro_10,
                SUM(total_income) - SUM(fuel_cost) - (SUM(total_income) * 0.1) as conductor'))
            ->groupBy(DB::raw('DATE_TRUNC(\'week\', created_at)'))
            ->get();
    }
}

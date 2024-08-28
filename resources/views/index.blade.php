<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de gastos en Uber</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1>Gestión de gastos en Uber</h1>

        <!-- Mostrar mensajes de éxito -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Formulario para crear/editar registros -->
        <form action="{{ $record ? route('records.update', $record->id) : route('records.store') }}" method="post" class="form">
            @csrf
            @if($record)
                @method('PUT')
                <h2>Editar Registro</h2>
            @else
                <h2>Agregar Registro</h2>
            @endif

            <div class="form-row">
                <div class="form-group">
                    <label for="kilometros">Kilómetros recorridos</label>
                    <input type="number" id="kilometros" name="mileage" value="{{ old('mileage', $record->mileage ?? '') }}" placeholder="Kilómetros recorridos"  min="0">
                </div>

                <div class="form-group">
                    <label for="combustible">Valor combustible</label>
                    <input type="number" id="combustible" name="fuel_cost" value="{{ old('fuel_cost', $record->fuel_cost ?? '') }}" placeholder="Valor combustible"  min="0">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="horas">Horas conducidas</label>
                    <input type="number" id="horas" name="driving_hours" value="{{ old('driving_hours', $record->driving_hours ?? '') }}" placeholder="Horas conducidas"  min="0">
                </div>

                <div class="form-group">
                    <label for="total-ingresos">Total ingresos</label>
                    <input type="number" id="total-ingresos" name="total_income" value="{{ old('total_income', $record->total_income ?? '') }}" placeholder="Total ingresos"  min="0">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="ingresos-efectivo">Ingresos en efectivo</label>
                    <input type="number" id="ingresos-efectivo" name="cash_income" value="{{ old('cash_income', $record->cash_income ?? '') }}" placeholder="Ingresos en efectivo"  min="0">
                </div>

                <div class="form-group">
                    <label for="ingresos-nequi">Ingresos en Nequi</label>
                    <input type="number" id="ingresos-nequi" name="nequi_income" value="{{ old('nequi_income', $record->nequi_income ?? '') }}" placeholder="Ingresos en Nequi"  min="0">
                </div>
            </div>

            <button type="submit" class="btn-form">{{ $record ? 'Actualizar Registro' : 'Agregar Registro' }}</button>
        </form>


        <h2>Totales Semanales (Martes a Lunes)</h2>
        <table>
            <thead>
            <tr>
                <th>Semana</th>
                <th>Total Facturado</th>
                <th>Efectivo</th>
                <th>Nequi</th>
                <th>Uber</th>
                <th>Carro 10%</th>
                <th>Conductor</th>
            </tr>
            </thead>
            <tbody>
            @foreach($weeklyTotals as $week)
                <tr>
                    <td style="text-align: center">Semana del {{ \Carbon\Carbon::parse($week->start_date)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($week->end_date)->format('d/m/Y') }}</td>
                    <td>${{ number_format($week->total_facturado, 0, ',', '.') }}</td>
                    <td>${{ number_format($week->efectivo, 0, ',', '.') }}</td>
                    <td>${{ number_format($week->nequi, 0, ',', '.') }}</td>
                    <td>${{ number_format($week->uber, 0, ',', '.') }}</td>
                    <td>${{ number_format($week->carro_10, 0, ',', '.') }}</td>
                    <td>${{ number_format($week->conductor, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Tabla de registros -->
        <h2>Resumen</h2>
        <table>
            <thead>
            <tr>
                <th>Fecha</th>
                <th>KM</th>
                <th>Horas</th>
                <th>Combustible</th>
                <th>Valor Km</th>
                <th>Valor hr</th>
                <th>Total Facturado</th>
                <th>Efectivo</th>
                <th>Nequi</th>
                <th>Uber</th>
                <th>Carro 10%</th>
                <th>Conductor</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($records as $record)
                @php
                    $carSave = $record->total_income * 0.1;
                    $driverIncome = $record->total_income - $record->fuel_cost - $carSave;
                    $uber_income = $record->total_income - $record->cash_income - $record->nequi_income;
                    $kilometer_rate = $record->total_income / $record->mileage;
                @endphp
                <tr>
                    <td>{{ $record->created_at ? $record->created_at->format('d-m') : 'No disponible' }}</td>
                    <td>{{ number_format($record->mileage, 0, ',', '.') }}</td>
                    <td>{{ number_format($record->driving_hours, 0, ',', '.') }}</td>
                    <td>${{ number_format($record->fuel_cost, 0, ',', '.') }}</td>
                    <td>${{ number_format($kilometer_rate, 0, ',', '.') }}</td>
                    <td>${{ number_format($record->hourly_rate, 0, ',', '.') }}</td>
                    <td>${{ number_format($record->total_income, 0, ',', '.') }}</td>
                    <td>${{ number_format($record->cash_income, 0, ',', '.') }}</td>
                    <td>${{ number_format($record->nequi_income, 0, ',', '.') }}</td>
                    <td>${{ number_format($uber_income, 0, ',', '.') }}</td>
                    <td>${{ number_format($carSave, 0, ',', '.') }}</td>
                    <td>${{ number_format($driverIncome, 0, ',', '.') }}</td>
                    <td>
                        <!-- Contenedor para los botones -->
                        <div class="action-buttons">
                            <!-- Botón de editar con ícono -->
                            <a href="{{ route('records.edit', $record->id) }}" class="btn btn-edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <!-- Botón de eliminar con ícono -->
                            <form action="{{ route('records.destroy', $record->id) }}" method="POST" class="btn-delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>

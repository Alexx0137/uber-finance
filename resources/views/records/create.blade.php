@extends('layouts.app')

@section('title', 'Registro de Gastos')

@section('header', 'Registro de Kilometraje y Combustible')

@section('content')
    <form action="{{ route('records.store') }}" method="POST">
        @csrf

        <label for="kilometros">Kilómetros recorridos</label>
        <input type="number" id="kilometros" name="mileage" placeholder="Kilómetros recorridos" required min="0">

        <label for="combustible">Valor del Combustible</label>
        <input type="number" id="combustible" name="fuel_cost" placeholder="Valor del combustible" required min="0">

        <label for="horas">Horas conducidas</label>
        <input type="number" id="horas" name="driving_hours" placeholder="Horas conducidas" required min="0">

        <label for="total-ingresos">Total de Ingresos</label>
        <input type="number" id="total-ingresos" name="total_income" placeholder="Total de ingresos" required min="0">

        <label for="ingresos-efectivo">Ingresos en Efectivo</label>
        <input type="number" id="ingresos-efectivo" name="cash_income" placeholder="Ingresos en efectivo" required min="0">

        <label for="ingresos-nequi">Ingresos en Nequi</label>
        <input type="number" id="ingresos-nequi" name="nequi_income" placeholder="Ingresos en Nequi" required min="0">

        <button type="submit">Agregar Registro</button>
    </form>
@endsection

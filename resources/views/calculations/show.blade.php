@extends('layout')

@section('content')

<div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Расчет "{{$calculation->name }}"</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
        	<h4>Машины:</h4>
            <ul>
            @foreach($calculation->our_machines_pivot()->get() as $our_machine)
                <li>{{$our_machine->machine_type->name}} (№{{$our_machine->tabnum}}, {{$our_machine->driver->name}})</li>
            @endforeach
            </ul>
            <p>Уровень риска, при котором следует проводить замену: <strong>{{$calculation->risk_level->name}}</strong></p>
        </div>
    </div>      
    <hr>
    <div class="row">
        <div class="col-lg-12">
            <h4>Этапов расчета: {{count($calculation->calculation_stages()->get())}}</h4>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-lg-12">
            <h4>Добавить этап</h4>
            <form method="POST" action="/calculations/{{$calculation->id}}/stages">
                {{ csrf_field() }}
                <table class="table table-bordered">
                    <thead>
                        <tr><th>Машина</th><th>Коэффициент использования по времени</th><th>Коэффициент режима работы</th><th>% времени в движении</th><th>% времени во вращении</th></tr>
                    </thead>
                    <tbody>
                    @foreach($calculation->our_machines_pivot()->get() as $our_machine)
                    <tr>
                        <td>{{$our_machine->machine_type->name}} (№{{$our_machine->tabnum}})</td>
                        <td><input class="form-control" name="kiv[{{$our_machine->id}}]" value="0.85"></td>
                        <td><input class="form-control" name="kmode[{{$our_machine->id}}]" value="0.5"></td>
                        <td><input class="form-control" name="moving_perc[{{$our_machine->id}}]" value="10"></td>
                        <td><input class="form-control" name="rotation_perc[{{$our_machine->id}}]" value="50"></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                <input type="submit" value="Добавить этап" class="btn btn-primary">
            </form>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-lg-12">
           <p> <a href="/calculations/{{$calculation->id}}/predict" class="btn btn-success">Прогноз</a> </p>
        </div>
    </div>

@endsection
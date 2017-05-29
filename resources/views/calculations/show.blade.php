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
            <table class="table table-bordered">
                <thead>
                    <tr>
                    <td rowspan="2">Этап/Показатели</td>
                     @foreach($calculation->our_machines_pivot()->get() as $our_machine)
                        <td colspan="3">{{$our_machine->machine_type->name}} ({{$our_machine->tabnum}})</td>
                     @endforeach
                     </tr>
                     <tr>
                         @foreach($calculation->our_machines_pivot()->get() as $our_machine)
                            <td>Kiv</td><td>Kr_mov</td><td>Kr_rot</td>
                         @endforeach
                     </tr>
                     @foreach($calculation->calculation_stages()->get() as $stage)
                     <tr>
                         <td>{{$stage->stage_num}}</td>
                         @foreach($stage->our_machines_pivot()->get() as $our_machine)
                            <td>{{$our_machine->pivot->kiv}}</td>
                            <td>{{$our_machine->pivot->moving_kmode}}</td>
                            <td>{{$our_machine->pivot->rotation_kmode}}</td>
                         @endforeach
                     </tr>
                     @endforeach
                </thead>
            </table>
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
                        <tr><th>Машина</th><th>Коэффициент использования по времени</th><th>Коэффициент режима работы механизма передвижения</th><th>Коэффициент режима работы механизма поворота</th></tr>
                    </thead>
                    <tbody>
                    @foreach($calculation->our_machines_pivot()->get() as $our_machine)
                        @php
                            $mov = rand(1,3) / 10;
                            $rot = rand(2,6) / 10;
                            $rot += $mov;
                        @endphp
                    <tr>
                        <td>{{$our_machine->machine_type->name}} (№{{$our_machine->tabnum}})</td>
                        <td><input class="form-control" name="kiv[{{$our_machine->id}}]" value="0.85"></td>
                        <td><input class="form-control" name="moving_kmode[{{$our_machine->id}}]" value="{{$mov}}"></td>
                        <td><input class="form-control" name="rotation_kmode[{{$our_machine->id}}]" value="{{$rot}}"></td>
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
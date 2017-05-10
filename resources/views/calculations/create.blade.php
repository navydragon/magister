@extends ('layout')

@section ('content')
	<div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Создать новый расчет</h1>
        </div>
    </div>
    <form method="POST" action="/calculations">
        {{ csrf_field() }}
        <div class="row">
        	<div class="col-lg-12">
        		<h4>Выберите машины, участвующие в расчете:</h4>
        		<ul>
        			@foreach($our_machines as $our_machine)
                        <div class="checkbox">
                        <label>
        				<input type="checkbox" checked name="our_machines[]" value="{{$our_machine->id}}">{{$our_machine->machine_type->name}} (№{{$our_machine->tabnum}}, {{$our_machine->driver->name}})
                        </label>
                        </div>
        			@endforeach
        		</ul>
        	</div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-12">
                <h4>Уровень риска, при котором следует проводить замену:</h4>
                <select name="risk_level">
                    @foreach($risk_levels as $risk_level)
                        <option value="{{$risk_level->id}}">{{$risk_level->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <hr>
        <div class="row">
        <input type="submit" class="btn btn-primary"value="Создать">
        </div>
    </form>
@endsection
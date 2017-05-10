@extends('layout')

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<h1>Прогноз потребности запасных частей ({{$calculation->name}})</h1>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<h4>Таблицы показателей</h4>
				@php
					$times = array();	$act_time = array();	
				@endphp
			@foreach ($calculation->our_machines_pivot()->get() as $our_machine)
				<h5><strong>{{$our_machine->machine_type->name}} (№{{$our_machine->tabnum}})</strong></h5>
			
			<table class="table table-bordered">
				<thead>
					<tr>
					<th rowspan="2">Этапы/Агрегаты</th>
						@foreach ($our_machine->parts()->get() as $part)
						<td colspan="4"><b>{{$part->machine_part->name}}</b></td>
						@endforeach	
					</tr>
					<tr>
						@foreach ($our_machine->parts()->get() as $part)
						@php
							$times[$part->id][0] = $part->init_time; 
							$act_time[$part->id] = $part->init_time;
						@endphp
						<td>Наработка</td><td>КПД</td><td>Куст</td><td>Замена?</td>
						@endforeach	
					</tr>
				</thead>
				<tbody>
						<tr>
						<td>Начало</td>
						@foreach ($our_machine->parts()->get() as $part)
								<td>{{$times[$part->id][0]}}</td>
								<td></td>
								<td></td>
								<td></td>
							@endforeach
						</tr>
						@foreach($calculation->calculation_stages()->get() as $stage)
							<tr>
							<td>{{$stage->stage_num}}</td>
						
							@foreach ($our_machine->parts()->get() as $part)
								@php
							//наработка
									$nar = $stage->narabotka($our_machine,$part);
									$act_time[$part->id] += $nar;
									$times[$part->id][$stage->stage_num] = $act_time[$part->id]; 
							//КПД
									$eff = $part->efficiency($act_time[$part->id]);
							//КУСТ
									$kyst = $part->kyst($eff,$nar,$act_time[$part->id]);
									$bgcolor = $part->bgcolor($kyst);

								@endphp
								<td>{{$act_time[$part->id]}} (+{{$nar}})</td>
								<td>{{round($eff,3)}}</td>
								<td bgcolor="{{$bgcolor}}">{{round($kyst,3)}}</td>
							
							@php
							//ЗАМЕНЫ
								$change = "";
								if ($kyst < $calculation->risk_level->kyst_border)
								{$change = "да"; $act_time[$part->id] = 0;}
							@endphp
								<td>{{$change}}</td>
							@endforeach
						
							</tr>
						@endforeach
						<tr>
							<td><strong>ИТОГО:</strong></td>
						</tr>
				</tbody>
			</table>

			@endforeach
		</div>
	</div>
@endsection
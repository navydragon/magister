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
					$times = array();	$act_time = array();	$eff_a = array();
					$change_on_machines = array(); $change_on_parts = array(); $change_on_stages = array()
				@endphp
			@foreach ($calculation->our_machines_pivot()->get() as $our_machine)
				<h5><strong>{{$our_machine->machine_type->name}} (№{{$our_machine->tabnum}})</strong></h5>
			
			<table class="table table-bordered">
				<thead>
					<tr>
					<th rowspan="2">Этапы/Агрегаты</th>
						@foreach ($our_machine->parts()->get() as $part)
						<td colspan="5"><b>{{$part->machine_part->name}}</b></td>
						@endforeach	
					</tr>
					<tr>
						@foreach ($our_machine->parts()->get() as $part)
						@php
							$times[$part->id][0] = $part->init_time; 
							$act_time[$part->id] = $part->init_time;
						@endphp
						<td>Наработка</td><td>Ур. оптимизма</td><td>КПД</td><td>Куст</td><td>Замена?</td>
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
								<td>~{{round($part->first_efficiency($part->init_time),2)}}</td>
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
									$old_time = $act_time[$part->id];
									$act_time[$part->id] += $nar;
									$times[$part->id][$stage->stage_num] = $act_time[$part->id]; 
							//оптимизм
									$optimism = $part->optimism($stage);
							//КПД
									if ($eff_a[$part->id][$stored_stage] == 0) 
									{
										$eff = $part->first_efficiency($part->init_time);
									}else{
										$eff = $part->efficiency($eff_a[$part->id][$stored_stage], $old_time, $act_time[$part->id],$stage);
									}
									
									$eff_a[$part->id][$stage->stage_num] = $eff;

									
							//КУСТ
									$kyst = $part->kyst($eff,$nar,$act_time[$part->id],$stage);
									$bgcolor = $part->bgcolor($kyst);

								@endphp
								<td>{{$act_time[$part->id]}} (+{{$nar}})</td>
								<td>{{$optimism}}</td>
								<td>{{round($eff,3)}}</td>
								<td bgcolor="{{$bgcolor}}">{{$kyst}}</td>
							
							@php
							//ЗАМЕНЫ
								$change = "";
								if ($kyst < $calculation->risk_level->kyst_border)
								{
									$change = "да"; 
									$act_time[$part->id] = 0;
									$change_on_parts[$part->id] += 1;
									$change_on_stages[$stage->id] += 1;
									$change_on_machines[$our_machine->id] += 1;
									$eff_a[$part->id][$stage->stage_num] = 0.95;
								}
							@endphp
								<td>{{$change}}</td>
							@endforeach
						
							</tr>
							@php
								$stored_stage = $stage->stage_num;
							@endphp
						@endforeach
				</tbody>
			</table>
			@endforeach
			<div class="col-md-6">
			<h4>Итоговая таблица замен по машинам и их агрегатам</h4>
				<table class="table table-bordered">
					<thead><tr><th>Машина</th><th>Количество замен</th></tr></thead>
					<tbody>
						@foreach ($calculation->our_machines_pivot()->get() as $our_machine)
							<tr class="bg-info">
								<td ><strong>{{$our_machine->machine_type->name}}({{$our_machine->tabnum}})</strong></td>
								<td><strong>{{$change_on_machines[$our_machine->id]}}</strong></td>
							</tr>
							@foreach ($our_machine->parts()->get() as $part)
							<tr>
							<td>{{$part->machine_part->name}}</td>
							<td>{{$change_on_parts[$part->id]}}</td>
							</tr>
							@endforeach	
						@endforeach
						<tr>
							<td><strong>ИТОГО:</strong></td>
							<td>{{array_sum($change_on_parts)}}</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-6">
			<h4>Итоговая таблица замен по этапам расчета</h4>
				<table class="table table-bordered">
					<thead><tr><th>№ Этапа</th><th>Количество замен</th></tr></thead>
					<tbody>
						@foreach($calculation->calculation_stages()->get() as $stage)
							<tr>
								<td>{{$stage->stage_num}}</td>
								<td>{{$change_on_stages[$stage->id]}}</td>
							</tr>
							
						@endforeach
						<tr>
							<td><strong>ИТОГО:</strong></td>
							<td>{{array_sum($change_on_stages)}}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection
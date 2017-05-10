@extends('layout')

@section('content')

<div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Машина типа "{{$machine_type->name }}"</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
        	<h4>Описание:</h4>
        	<p>
        		{{ $machine_type->description }}
        	</p>
        </div>
    </div>      

    <div class="row">
        <div class="col-lg-12">
        	<h4>Комплектующие:</h4>
        	<ul>
        		@foreach ($machine_type->machine_parts as $machine_part)
        			<li>{{ $machine_part->name }}</li>
        		@endforeach
        	</ul>
        </div>
    </div>      

@endsection
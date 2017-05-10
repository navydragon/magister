@extends ('layout')

@section ('content')
	<div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Расчеты</h1>
        </div>
    </div>
    <div class="row">
    	<div class="col-lg-12">
    		<h4>Текущие расчеты:</h4>
    		<ul>
    			@foreach($calculations as $calculation)
    				<li><a href="/calculations/{{$calculation->id}}">{{$calculation->name}}</a></li>
    			@endforeach
    		</ul>
    	</div>
    </div>
    <hr>
    <div class="row">
    <a href="/calculations/create" class="btn btn-primary">Добавить новый расчет</a>
    </div>
@endsection
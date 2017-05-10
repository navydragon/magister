@extends ('layout')

@section ('content')
	<div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Модели машин</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
           <ul class="list-group">
           		@foreach ($machine_types as $machine_type)
           			<li class="list-group-item"><a href="/machine_types/{{  $machine_type->id }}">{{ $machine_type->name }}</a></li>
           		@endforeach
           </ul>
        </div>
    </div>         


                <!-- /.row -->
@endsection
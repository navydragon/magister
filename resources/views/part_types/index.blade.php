@extends ('layout')

@section ('content')
	<div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Типы оборудования</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
           <ul class="list-group">
              @foreach ($part_types as $part_type)
                <li class="list-group-item">{{ $part_type->name }}</li>
              @endforeach
           </ul>
           <hr>
           <h4>Добавить новый тип оборудования</h4>		
           <form method="POST" action="/part_types">
              {{ csrf_field() }}
               <div class="form-group">
                <label for="name">Наименование</label>
                <input  class="form-control" id="name" name="name" >
                <br>
                <button type="submit" class="btn btn-primary">Добавить</button>
              </div>
           </form>
        </div>
    </div>         


                <!-- /.row -->
@endsection
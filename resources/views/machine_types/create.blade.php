@extends ('layout')

@section ('content')
	<div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Добавить новый тип машин</h1>
        </div>
    </div>
    <div class="row">
    	<div class="col-lg-12">
            <form method="POST" action="/machine_types">
              {{ csrf_field() }}
			  <div class="form-group">
			    <label for="name">Название</label>
			    <input  class="form-control" id="name" name="name" >
			  </div>
			  <div class="form-group">
			    <label for="description">Описание</label>
			    <textarea class="form-control" id="description" name="description"></textarea>
			  </div>

			  <button type="submit" class="btn btn-default">Добавить</button>
			</form>
        </div>
    </div>
@endsection
@extends ('layout')

@section ('content')
	<div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Модели оборудования</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
          <table class="table table-bordered">
            <thead>
              <tr><th>Наименование</th><th>Тип</th><th>Гарантийная наработка, ч</th><th>Редактировать</th></tr>
            </thead>
            @foreach ($parts as $part)
                <tr>
                  <td>{{$part->name}}</td>
                  <td>{{$part->part_type->name}}</td>
                  <td>{{$part->mtbf}}</td>
                  <td><a href="/parts/{{$part->id}}/edit"><span class="glyphicon glyphicon-edit btn-lg" style="padding:0px;" aria-hidden="true"></span></a></td>
                </tr>
            @endforeach
          </table>
          <hr>
           <h4>Добавить новую модель оборудования</h4>		
           <form method="POST" action="/parts">
              {{ csrf_field() }}
               <div class="form-group">
                <label for="name">Наименование</label>
                <input  class="form-control" id="name" name="name" >
                <label for="part_type">Тип оборудования</label>
                <select  class="form-control" id="part_type" name="part_type" >
                    @foreach ($part_types as $part_type)
                      <option value="{{$part_type->id}}">{{$part_type->name}}</option>
                    @endforeach
                </select>
                <label for="mtbf">Гарантийная наработка, ч</label>
                <input  class="form-control" id="mtbf" name="mtbf" >
                <br>
                <button type="submit" class="btn btn-primary">Добавить</button>
              </div>
           </form>
        </div>
    </div>         


                <!-- /.row -->
@endsection
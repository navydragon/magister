@extends ('layout')

@section ('content')
	<div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Машинисты</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
          <table class="table table-bordered">
            <thead>
              <tr><th>ФИО</th><th>Образование</th><th>Дата начала работы</th><th>Уровень</th><th>Редактировать</th></tr>
            </thead>
            @foreach ($drivers as $driver)
                <tr>
                  <td>{{$driver->name}}</td>
                  <td>{{$driver->graduate}}</td>
                  <td>{{$driver->standing}}</td>
                  <td>{{$driver->staff()}}</td>
                  <td><a href="/drivers/{{$driver->id}}/edit"><span class="glyphicon glyphicon-edit btn-lg" style="padding:0px;" aria-hidden="true"></span></a></td>
                </tr>
            @endforeach
          </table>
          <hr>
           <h4>Добавить нового машиниста</h4>		
           <form method="POST" action="/drivers">
              {{ csrf_field() }}
               <div class="form-group">
                <label for="name">ФИО</label>
                <input  class="form-control" id="name" name="name" >
                <label for="graduate">Образование</label>
                <select  class="form-control" id="graduate" name="graduate" >
                <option value="высшее">Высшее</option>
                <option value="средне-специальное">средне-специальное</option>
                </select>
                <label for="graduate">Дата начала работы (ГГГГ-ММ-ДД)</label>
                <input  class="form-control" id="standing" name="standing" >
                <br>
                <button type="submit" class="btn btn-primary">Добавить</button>
              </div>
           </form>
        </div>
    </div>         


                <!-- /.row -->
@endsection
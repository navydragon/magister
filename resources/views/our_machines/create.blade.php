@extends ('layout')

@section ('content')
	<div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Добавить машину модели "{{$machine_type->name}}"</h1>
        </div>
  </div>

  <div class="col-lg-12">
    <form method="POST" action="/our_machines">
      {{ csrf_field() }}
      <input type="hidden" name="machine_type" value="{{$machine_type->id}}">
      <label for="tabnum">Табельный номер</label>
      <input  name="tabnum" class="form-control">
      <label for="driver">Машинист</label>
      <select class="form-control" name="driver">
        @foreach ($drivers as $driver)
          <option value="{{$driver->id}}">{{$driver->name}}</option>
        @endforeach
      </select>
      <div class="col-md-10">
        <table class="table table-bordered">
          <thead><tr><th>Комплектующие</th><th>Тип</th><th>Модель</th><th>Начальная наработка</th></tr></thead>
          <tbody>
            @foreach ($machine_type->machine_parts()->get() as $machine_part)
              <tr>
                <td>{{$machine_part->name}} <input type="hidden" name="machine_part[]" value="{{$machine_part->id}}"/></td>
                <td>{{$machine_part->part_type()->first()->name}}</td>
                <td>
                  <select class="form-control" name="part[]">
                    @foreach ($machine_part->part_type->first()->parts()->get() as $part)
                      <option value="{{$part->id}}">{{$part->name}}</option>
                    @endforeach
                  </select>
                </td>
                <td>
                  <input class="form-control" name="init_time[]" value="0">
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        <input type="submit" class="btn btn-primary" value="Добавить">
      </div> 
    </form>
  </div>



    
@endsection
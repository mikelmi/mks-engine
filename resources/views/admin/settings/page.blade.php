@extends ('admin.settings.index')

@section('form')
    <div class="form-group row">
        <label class="col-sm-2 col-form-label form-control-label">@lang('Homepage')</label>
        <div class="col-sm-10">
            <mks-link-select url="{{route('admin::routes')}}" params-url="{{route('admin::route.params')}}"
                             field-route="home[route]"
                             field-params="home[params]"
                             route="{{$model->get('home.route')}}"
                             params="{{$model->get('home.params')}}"
                             data-title="{{$model->get('home.params')}}"
            >
            </mks-link-select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label form-control-label">404</label>
        <div class="col-sm-10">
            <select name="404" class="form-control" mks-select>
                <option value=""> - </option>
                @foreach($model->get('pages') as $id => $title)
                    <option value="{{$id}}" @if($id == $model->get('404')) selected @endif>{{$title}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label form-control-label">@lang('a.Error page')</label>
        <div class="col-sm-10">
            <select name="500" class="form-control" mks-select>
                <option value=""> - </option>
                @foreach($model->get('pages') as $id => $title)
                    <option value="{{$id}}" @if($id == $model->get('500')) selected @endif>{{$title}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label form-control-label">@lang('a.Offline page')</label>
        <div class="col-sm-10">
            <select name="503" class="form-control" mks-select>
                <option value=""> - </option>
                @foreach($model->get('pages') as $id => $title)
                    <option value="{{$id}}" @if($id == $model->get('503')) selected @endif>{{$title}}</option>
                @endforeach
            </select>
        </div>
    </div>
@endsection
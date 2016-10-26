@extends('layouts.main')

@section('content')
    <h1 class="page-title">@lang('user.Profile')</h1>

    <div>

        <div class="form-group row">
            <label class="col-form-label col-sm-2 text-sm-right text-muted">@lang('general.Name')</label>
            <div class="col-sm-10">
                <p class="form-control-static">{{$user->name}}</p>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-form-label col-sm-2 text-sm-right text-muted">E-mail</label>
            <div class="col-sm-10">
                <p class="form-control-static">{{$user->email}}</p>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label text-sm-right text-muted"> @lang('general.Created at') </label>
            <div class="col-sm-10">
                <p class="form-control-static">{{$user->created_at}}</p>
            </div>
        </div>

        @if ($canEdit)
            <div class="form-group row">
                <div class="col-sm-10 offset-sm-2">
                    <a class="btn btn-secondary" href="{{route('user.edit')}}">
                        <i class="fa fa-pencil"></i> @lang('general.Edit')
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
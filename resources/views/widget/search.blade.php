@extends('widget.' . $template)

@section('widget_content')
    <form class="form" action="{{ route('search') }}" method="get">
        <div>
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="@lang('general.Search')..." required="required">
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                </span>
            </div>
        </div>
    </form>
@overwrite
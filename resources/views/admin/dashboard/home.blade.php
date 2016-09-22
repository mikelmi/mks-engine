@extends('admin::page')

@section('title')
    @lang('a.Dashboard')
@endsection

@section('content')
    <div class="row">

        <div class="col-md-6">
            <dashboard-notifications url="{{route('admin::dashboard.notifications')}}"></dashboard-notifications>
        </div>

        <div class="col-md-6">
            <dashboard-statistics url="{{route('admin::dashboard.statistics')}}"></dashboard-statistics>
        </div>
    </div>
@endsection
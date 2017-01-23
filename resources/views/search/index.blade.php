@extends('layouts.main')

@section('content')
    <h1 class="page-title">
        @lang('general.Search')
    </h1>

    <script>
        {!! config('services.sce.script') !!}
    </script>
    <gcse:search @if ($domain) as_sitesearch="{{$domain}}" @endif autoCompleteMaxCompletions="3" autoCompleteMatchType='any'></gcse:search>
@endsection

@section('js')
    @parent
    <script>
        $('#siteSearchMainForm input:checkbox').on('change', function() {
            if ($.trim($('#siteSearchMainForm input[name="q"]').val())) {
                $('#siteSearchMainForm').submit();
            }
        });
    </script>
@endsection
@extends('admin::data-grid')

@section('content')
    @parent()

    @can('admin.lang.create')
        <!-- Modal -->
        @component('admin.components.modal', ['id'=>'addLangModal','title' => __('general.Add Language')])
            <form class="container-fluid" method="post" action="{{route('admin::language.store')}}" mks-form id="addLangForm">
                <div class="form-group row">
                    <label class="col-2 col-form-label">@lang('general.Language')</label>
                    <div class="col-10">
                        <select name="language" class="form-control" mks-select
                                data-url="{{route('admin::language.all')}}"
                                data-lang-icon="{{route('lang.icon')}}"
                        >
                        </select>
                    </div>
                </div>
            </form>
            @slot('buttons')
                <button type="button" class="btn btn-primary" mks-submit="#addLangForm">@lang('admin::messages.Add')</button>
            @endslot
        @endcomponent
    @endcan
@endsection
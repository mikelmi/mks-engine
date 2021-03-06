<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <h4 class="modal-title">@yield('title')</h4>
</div>
<div class="modal-body">
    @yield('body')
</div>
<div class="modal-footer">
    @section('footer')
        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('general.Cancel')</button>
    @show
</div>
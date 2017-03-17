<div class="modal fade" id="{{$id or 'myModal'}}" role="dialog" aria-labelledby="{{$id or 'myModal'}}Label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            @if(isset($title))
                <div class="modal-header">
                    <h5 class="modal-title" id="{{$id or 'myModal'}}Label">{{$title}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="@lang('admin::messages.Cancel')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="modal-body">
                {{$slot}}
            </div>
            @if (isset($buttons))
            <div class="modal-footer">
                {{$buttons}}
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('admin::messages.Cancel')</button>
            </div>
            @endif
        </div>
    </div>
</div>
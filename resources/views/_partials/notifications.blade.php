<div class="notifications">
    @if (Session::has('message'))
        <div class="alert {{ session('alert-class', 'alert-info') }} alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ session('message') }}
        </div>
    @endif
</div>
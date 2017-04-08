<div>

    <div class="form-group row">
        <label class="col-sm-3 col-form-label text-sm-right"> ID </label>
        <div class="col-sm-9">
            <p class="form-control-static">{{$user->id}}</p>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-3 col-form-label text-sm-right"> @lang('general.Name') </label>
        <div class="col-sm-9">
            <p class="form-control-static">{{$user->name}}</p>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-3 col-form-label text-sm-right"> E-mail </label>
        <div class="col-sm-9">
            <p class="form-control-static">{{$user->email}}</p>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-3 col-form-label text-sm-right"> @lang('admin::messages.Created at') </label>
        <div class="col-sm-9">
            <p class="form-control-static">{{$user->created_at}}</p>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-3 col-form-label text-sm-right"> @lang('admin::messages.Updated at') </label>
        <div class="col-sm-9">
            <p class="form-control-static">{{$user->updated_at}}</p>
        </div>
    </div>

    @can('admin.users.edit')
        <div class="form-group row">
            <div class="col-sm-9 offset-sm-3">
                <a href="#/user/edit/{{$user->id}}" class="btn btn-primary">
                    <i class="fa fa-pencil"></i>
                    @lang('admin::messages.Edit')
                </a>
            </div>
        </div>
    @endcan

</div>
<form name="menuForm" method="post" action="{{route('admin::menu.save')}}" class="form-horizontal" novalidate ng-submit="saveMenu()">
    <input type="hidden" ng-value="menuModel.id">

    <div class="form-group row" ng-class="{'has-danger': menuForm.name.$dirty && menuForm.name.$invalid}">
        <label class="col-sm-2 col-form-label form-control-label"> @lang('a.Title') </label>
        <div class="col-sm-10">
            <input type="text" name="name" class="form-control" ng-model="menuModel.name" required />
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label form-control-label"> @lang('a.Position') </label>
        <div class="col-sm-10">
            <input type="text" name="position" class="form-control" ng-model="menuModel.position" />
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label form-control-label">@lang('admin::messages.Status')</label>
        <div class="col-sm-10">
            <div class="btn-group">
                <label class="btn btn-outline-success" ng-class="{'active':menuModel.active}" ng-click="menuModel.active=true">
                    @lang('admin::messages.Active')
                </label>
                <label class="btn btn-outline-danger" ng-class="{'active':!menuModel.active}" ng-click="menuModel.active=false">
                    @lang('admin::messages.Inactive')
                </label>
            </div>
        </div>
    </div>
</form>
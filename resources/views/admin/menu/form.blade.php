<form name="menuForm" method="post" action="{{route('admin::menu.save')}}" class="form-horizontal" novalidate ng-submit="$event.preventDefault(); saveMenu()">
    <input type="hidden" ng-value="menuModel.id">

    <div class="form-group row" ng-class="{'has-danger': menuForm.name.$dirty && menuForm.name.$invalid}">
        <label class="col-sm-2 col-form-label form-control-label"> @lang('a.Title') </label>
        <div class="col-sm-10">
            <input type="text" name="name" class="form-control" ng-model="menuModel.name" required />
        </div>
    </div>

</form>
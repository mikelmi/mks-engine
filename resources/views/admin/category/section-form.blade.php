<form name="sectionForm" method="post" action="{{route('admin::category.save.section')}}" class="form-horizontal" novalidate ng-submit="$event.preventDefault(); saveSection()">
    <input type="hidden" ng-value="sectionModel.id">

    <div class="form-group row" ng-class="{'has-danger': sectionForm.type.$dirty && sectionForm.type.$invalid}">
        <label class="col-sm-2 col-form-label text-sm-right"> @lang('general.Type') </label>
        <div class="col-sm-10">
            <select name="type" class="form-control" ng-model="sectionModel.type" required>
                @foreach($types as $k => $v)
                    <option value="{{$k}}">{{$v}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row" ng-class="{'has-danger': sectionForm.title.$dirty && sectionForm.title.$invalid}">
        <label class="col-sm-2 col-form-label text-sm-right"> @lang('general.Title') </label>
        <div class="col-sm-10">
            <input type="text" name="title" class="form-control" ng-model="sectionModel.title" required />
        </div>
    </div>

</form>
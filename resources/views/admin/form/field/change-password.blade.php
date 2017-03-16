<div class="input-group">
    <span class="input-group-addon">
        <input type="checkbox" ng-model="psw_checked" class="form-check-input-2" />
    </span>
    <input type="password" name="{{$field->getName()}}" class="form-control" ng-disabled="!psw_checked" placeholder="@lang('general.Password')" /><br />
    <input type="password" name="{{$field->getName()}}_confirmation" class="form-control" ng-disabled="!psw_checked" placeholder="@lang('general.Confirm Password')" />
</div>
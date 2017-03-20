<div class="form-group" @if($field->getNameSce()) ng-class="{'has-danger':page.errors.{{$field->getNameSce()}}}" @endif>
    <label for="{{$field->getId()}}" class="form-control-label @if($field->isRequired()) required @endif">{{$field->getLabel()}}</label>
    {!! $field->renderInput() !!}
    @include('admin::form.field-error-info')
</div>

<div class="form-group row" ng-hide="!{{$field->getNgModel()}} || {{$field->getNgModel()}}==1">
    {!! $field->renderSelectRoles() !!}
</div>
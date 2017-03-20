<div class="input-group" mv-checked-input>
    <span class="input-group-addon">
        <input type="checkbox" ng-model="{{$field->getNgModel()}}"> {!! $field->getAddon() !!}
    </span>
    {!! $input !!}
</div>
<div class="form-group" {!! html_attr($field->getRowAttributes()) !!}>
    <label for="{{$field->getId()}}" class="form-control-label @if($field->isRequired()) required @endif">{{$field->getLabel()}}</label>
    {!! $field->renderField() !!}
    @include('admin.form.field.size-error')
</div>
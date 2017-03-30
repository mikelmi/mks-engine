<div class="form-group row" {!! html_attr($field->getRowAttributes()) !!}>
    <label for="{{$field->getId()}}" class="col-md-2 col-form-label text-md-right @if($field->isRequired()) required @endif">{{$field->getLabel()}}</label>
    <div class="col-md-10">
        {!! $field->renderField() !!}
        @include('admin.form.field.size-error')
    </div>
</div>
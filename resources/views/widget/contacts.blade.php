@component($template, ['title' => $title, 'attr' => $attr])

{!! $model->content !!}

@if($showFeedbackForm)
    <form role="form" method="post" action="{{ route('contacts.send') }}" class="ajax-form">
        {{ csrf_field() }}
        <input type="hidden" name="widget_id" value="{{$model->id}}" />

        <div class="form-group row{{ $errors->has('name') ? ' has-error' : '' }}">
            <label class="col-md-2 col-form-label">@lang('general.Name')</label>

            <div class="col-md-6">
                <input type="text" class="form-control" name="name" required value="{{ old('name', $name) }}" maxlength="100">
                @if ($errors->has('name'))
                    <div class="form-control-feedback">{{ $errors->first('name') }}</div>
                @endif
            </div>
        </div>

        <div class="form-group row{{ $errors->has('email') ? ' has-danger' : '' }}">
            <label class="col-md-2 col-form-label">E-mail</label>

            <div class="col-md-6">
                <input type="email" class="form-control" name="email" value="{{ old('email', $email) }}" required maxlength="100">
                @if ($errors->has('email'))
                    <div class="form-control-feedback">{{ $errors->first('email') }}</div>
                @endif
            </div>
        </div>

        <div class="form-group row{{ $errors->has('message') ? ' has-error' : '' }}">
            <label class="col-md-2 col-form-label">@lang('general.Message')</label>

            <div class="col-md-6">
                <textarea class="form-control" name="message" maxlength="2000" required>{{ old('message') }}</textarea>
                @if ($errors->has('message'))
                    <div class="form-control-feedback">{{ $errors->first('message') }}</div>
                @endif
            </div>
        </div>

        @if (captcha_enabled())
            <div class="form-group row{{ $errors->has(captcha_field_name()) ? ' has-danger' : '' }}">
                <label class="col-sm-2 col-form-label text-sm-right">
                    @if (captcha_has_input())
                        @lang('messages.Captcha')
                    @endif
                </label>
                <div class="col-sm-6">
                    {!! captcha_display(true) !!}
                    @if ($errors->has(captcha_field_name()))
                        <div class="form-control-feedback">{{ $errors->first(captcha_field_name()) }}</div>
                    @endif
                </div>
            </div>
        @endif


        <div class="form-group row">
            <div class="offset-md-2 col-md-6">
                <button type="submit" class="btn btn-primary">@lang('general.Send')</button>
            </div>
        </div>
    </form>
@endif

@endcomponent
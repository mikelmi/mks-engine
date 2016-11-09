@if($attr)
    <div{!! htmlspecialchars(html_attr($attr), ENT_NOQUOTES) !!}>
@endif
@yield('widget_title', !$model->param('hide_title') ? $model->title : '')
@yield('widget_content')
@if ($attr)
    </div>
@endif
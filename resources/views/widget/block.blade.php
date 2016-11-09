<div {!! htmlspecialchars(html_attr($attr), ENT_NOQUOTES) !!}>
    @yield('widget_title', !$model->param('hide_title') ? '<div class="card-header widget-title">' . $model->title . '</div>' : '')

    <div class="card-block widget-body">
        @yield('widget_content')
    </div>
</div>
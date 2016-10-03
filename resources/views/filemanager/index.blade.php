<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-ng-app="FileManagerApp">

<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>@lang('filemanager.page_title')</title>

    <link rel="icon" href="{{ asset('filemanager/favicon.ico') }}">

    <link rel="stylesheet" href="{{ asset('filemanager/css/app.css') }}">
</head>

<body class="ng-cloak">

    <angular-filemanager></angular-filemanager>

    <script type="text/ng-template" id="src/templates/main-icons.html">
        @include('filemanager.templates.main-icons')
    </script>

    <script type="text/javascript">
        var handler_url = '{{ route('filemanager.handler') }}';

        window.FM_CONFIG = {
            appName: '@lang('filemanager.app_name')',
            listUrl: handler_url,
            createFolderUrl: handler_url,
            renameUrl: handler_url,
            moveUrl: handler_url,
            copyUrl: handler_url,
            removeUrl: handler_url,
            permissionsUrl: handler_url,
            compressUrl: handler_url,
            extractUrl: handler_url,
            uploadUrl: '{{ route('filemanager.upload') }}',
            getContentUrl: handler_url,
            editUrl: handler_url,
            downloadFileUrl: '{{ route('filemanager.download') }}',
            downloadMultipleUrl: '{{ route('filemanager.downloadMulti') }}',
            multipleDownloadFileName: 'files.zip',
            pickCallback: function (item) {
                var msg = 'Picked %s "%s" for external use'
                        .replace('%s', item.type)
                        .replace('%s', item.fullPath());
                window.alert(msg);
            },
        };

        window.FM_ACTIONS = {
            pickFiles: true,
            pickFolders: false
        }
    </script>

    <script src="{{ asset('filemanager/js/app.js') }}"></script>
</body>
</html>

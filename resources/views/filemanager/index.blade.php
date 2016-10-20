<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-ng-app="FileManagerApp">

<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>@lang('filemanager.page_title')</title>

    <link rel="icon" href="{{ asset('filemanager/favicon.ico') }}">

    <link rel="stylesheet" href="{{ asset('filemanager/css/app.css') }}">
</head>

<body class="ng-cloak light-theme">

    <angular-filemanager></angular-filemanager>

    <script type="text/ng-template" id="src/templates/main.html">
        @include('filemanager.templates.main')
    </script>

    <script type="text/ng-template" id="src/templates/main-icons.html">
        @include('filemanager.templates.main-icons')
    </script>

    <script type="text/ng-template" id="src/templates/navbar.html">
        @include('filemanager.templates.navbar')
    </script>

    <script type="text/ng-template" id="src/templates/item-context-menu.html">
        @include('filemanager.templates.item-context-menu')
    </script>

    <script type="text/javascript">
        function getUrlParam( paramName ) {
            var reParam = new RegExp( '(?:[\?&]|&)' + paramName + '=([^&]+)', 'i' );
            var match = window.location.search.match( reParam );

            return ( match && match.length > 1 ) ? match[1] : null;
        }

        if (window.localStorage) {
            window.localStorage.setItem('language', '{{ app()->getLocale() }}');
        }

        var handler_url = '{{ route('filemanager.handler', $params) }}';

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
            uploadUrl: '{{ route('filemanager.upload', $params) }}',
            getContentUrl: handler_url,
            editUrl: handler_url,
            downloadFileUrl: '{{ route('filemanager.download') }}',
            downloadMultipleUrl: '{{ route('filemanager.downloadMulti') }}',
            multipleDownloadFileName: 'files.zip',
            pickCallback: function (item) {
                if (!window.opener) {
                    return;
                }

                if (typeof window.opener.CKEDITOR != 'undefined') {
                    var ckCallback = getUrlParam('CKEditorFuncNum');
                    if (ckCallback) {
                        window.opener.CKEDITOR.tools.callFunction(ckCallback, item.url);
                        window.close();

                        return;
                    }
                }

                var callback = getUrlParam('callback');
                if (callback && typeof window.opener[callback] == 'function') {
                    window.opener[callback]([item]);
                }

                window.close();
            }
        };

        if (window.opener && getUrlParam('multiple')) {
            window.FM_CONFIG.pickMultipleCallback = function(items) {
                var callback = getUrlParam('callback');
                if (callback && typeof window.opener[callback] == 'function') {
                    var models = [];
                    for (var i = 0; i < items.length; i++) {
                        models.push(items[i].model);
                    }

                    window.opener[callback](models);
                }

                window.close();
            };
        }

        window.FM_ACTIONS = {
            pickFiles: typeof window.opener != 'undefined' && window.opener !== null,
            pickFolders: false,
            compress: getUrlParam('type') != 'images'
        }
    </script>

    <script src="{{ asset('filemanager/js/app.js') }}"></script>
</body>
</html>

(function() {
    angular.module('FileManagerApp').config(['fileManagerConfigProvider', '$provide', '$translateProvider',
        function (config, $provide, $translateProvider) {

            $provide.decorator('item', [
                '$delegate',
                function itemDecorator($delegate) {

                    var oConstruct = $delegate.prototype.constructor;

                    var oldProto = $delegate.prototype;
                    $delegate = function(model, path) {
                        oConstruct.apply(this, arguments);
                        if (model && this.model) {
                            this.model.thumbnail = model.thumbnail;
                            this.model.url = model.url;
                        }
                    };
                    $delegate.prototype = oldProto;

                    return $delegate;
                }
            ]);

            var settings = {};
            var defaults = config.$get();

            if (typeof window.FM_CONFIG != 'undefined' && angular.isObject(window.FM_CONFIG)) {
                settings = window.FM_CONFIG;
            }

            if (typeof window.FM_ACTIONS != 'undefined' && angular.isObject(window.FM_ACTIONS)) {
                if (window.FM_ACTIONS_CLEAR) {
                    settings.allowedActions = window.FM_ACTIONS;
                } else {
                    settings.allowedActions = angular.extend(defaults.allowedActions, window.FM_ACTIONS);
                }
            }

            config.set(settings);

            //fix translations
            $translateProvider.translations('uk', {
                filemanager: 'Файловий менеджер',
                confirm: 'Підтвердити',
                cancel: 'Відмінити',
                close: 'Закрити',
                upload_files: 'Завантаження файлів',
                files_will_uploaded_to: 'Файли будуть завантажені у: ',
                select_files: 'Виберіть файли',
                uploading: 'Завантаження',
                permissions: 'Дозволи',
                select_destination_folder: 'Виберіть папку призначення',
                source: 'Джерело',
                destination: 'Ціль',
                copy_file: 'Скопіювати файл',
                sure_to_delete: 'Дійсно видалити?',
                change_name_move: 'Перейменувати / перемістити',
                enter_new_name_for: 'Нове ім\'я для',
                extract_item: 'Розпакувати',
                extraction_started: 'Розпакування почато',
                compression_started: 'Архівацію почато',
                enter_folder_name_for_extraction: 'Розпакувати в зазначену папку',
                enter_file_name_for_compression: 'Введіть ім\'я архіва',
                toggle_fullscreen: 'На весь екран',
                edit_file: 'Редагувати',
                file_content: 'Вміст файлу',
                loading: 'Завантаження',
                search: 'Пошук',
                create_folder: 'Створити папку',
                create: 'Створити',
                folder_name: 'Ім\'я  папки',
                upload: 'Завантажити',
                change_permissions: 'Змінити дозволи',
                change: 'Редагувати',
                details: 'Властивості',
                icons: 'Іконки',
                list: 'Список',
                name: 'Ім\'я',
                size: 'Розмір',
                actions: 'Дії',
                date: 'Дата',
                no_files_in_folder: 'Порожня папка',
                no_folders_in_folder: 'Порожня папка',
                select_this: 'Вибрати',
                go_back: 'Назад',
                wait: 'Зачекайте',
                move: 'Перемістити',
                download: 'Завантажити',
                view_item: 'Показати вміст',
                remove: 'Видалити',
                edit: 'Редагувати',
                copy: 'Копіювати',
                rename: 'Переіменувати',
                extract: 'Розархівувати',
                compress: 'Архівувати',
                error_invalid_filename: 'Ім\'я невірне або вже існує, виберіть інше',
                error_modifying: 'Виникла помилка при редагуванні файлу',
                error_deleting: 'Виникла помилка при видаленні',
                error_renaming: 'Виникла помилка при зміні імені файлу',
                error_copying: 'Виникла помилка при коміюванні файлу',
                error_compressing: 'Виникла помилка при стисненні',
                error_extracting: 'Виникла помилка при розархівації',
                error_creating_folder: 'Виникла помилка при створенні папки',
                error_getting_content: 'Виникла помилка при отриманні вмісту',
                error_changing_perms: 'Виникла помилка при зміні дозволів',
                error_uploading_files: 'Виникла помилка при завантаженні',
                sure_to_start_compression_with: 'Дійсно стиснути',
                owner: 'Власник',
                group: 'Група',
                others: 'Інші',
                read: 'Читання',
                write: 'Запис',
                exec: 'Виконання',
                original: 'За замовчуванням',
                changes: 'Зміни',
                recursive: 'Рекурсивно',
                preview: 'Перегляд',
                open: 'Відкрити',
                these_elements: 'усього {{total}} елементів',
                new_folder: 'Нова папка',
                download_as_zip: 'Завантажити як ZIP'
            });
        }
    ]);
})(window.angular);

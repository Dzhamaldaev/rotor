<?php

return [
    'antimat' => [
        'text' => '
        Все слова из списка будут заменяться на ***<br>
        Чтобы удалить слово нажмите на него, добавить слово можно в форме ниже<br>',
        'words'         => 'Список слов',
        'total_words'   => 'Всего слов',
        'confirm_clear' => 'Вы уверены что хотите удалить все слова?',
        'empty_words'   => 'Слов еще нет!',
        'enter_word'    => 'Введите слово',
    ],

    'backup' => [
        'create_backup'   => 'Создать бэкап',
        'total_backups'   => 'Всего бэкапов',
        'empty_backups'   => 'Бэкапов еще нет!',
        'total_tables'    => 'Всего таблиц',
        'records'         => 'Записей',
        'size'            => 'Размер',
        'compress_method' => 'Метод сжатия',
        'not_compress'    => 'Не сжимать',
        'compress_ratio'  => 'Степень сжатия',
        'empty_tables'    => 'Нет таблиц для бэкапа!',
    ],

    'banhists' => [
        'history'       => 'История',
        'search_user'   => 'Поиск по пользователю',
        'empty_history' => 'Истории банов еще нет!',
        'view_history'  => 'Просмотр истории',
    ],

    'bans' => [
        'login_hint'    => 'Введите логин пользователя который необходимо отредактировать',
        'user_ban'      => 'Бан пользователя',
        'change_ban'    => 'Изменение бана',
        'time_ban'      => 'Время бана',
        'banned'        => 'Забанить',
        'ban_hint'      => 'Внимание! Постарайтесь как можно подробнее описать причину бана',
        'confirm_unban' => 'Вы действительно хотите разбанить пользователя?',
    ],

    'blacklists' => [
        'email'      => 'Email',
        'logins'     => 'Логины',
        'domains'    => 'Домены',
        'empty_list' => 'Список еще пуст!',
    ],

    'caches' => [
        'files'        => 'Файлы',
        'images'       => 'Изображения',
        'clear'        => 'Очистить кэш',
        'total_files'  => 'Всего файлов',
        'total_images' => 'Всего изображений',
        'empty_files'  => 'Файлов еще нет!',
        'empty_images' => 'Изображений еще нет!',
    ],

    'chat' => [
        'clear'         => 'Очистить чат',
        'confirm_clear' => 'Вы действительно хотите очистить админ-чат?',
        'edit_message'  => 'Редактирование сообщения',
    ],

    'checkers' => [
        'new_files'          => 'Новые файлы и новые параметры файлов',
        'old_files'          => 'Удаленные файлы и старые параметры файлов',
        'empty_changes'      => 'Нет изменений!',
        'initial_scan'       => 'Необходимо провести начальное сканирование!',
        'information_scan'   => 'Сканирование системы позволяет узнать какие файлы или папки менялись в течение определенного времени',
        'invalid_extensions' => 'Внимание, сервис не учитывает некоторые расширения файлов',
        'scan'               => 'Сканировать',
    ],

    'delivery' => [
        'online' => 'В онлайне',
        'active' => 'Активным',
        'admins' => 'Администрации',
        'users'  => 'Всем пользователям',
    ],

    'delusers' => [
        'condition'         => 'Удалить пользователей которые не посещали сайт',
        'minimum_asset'     => 'Минимум актива',
        'deleted_condition' => 'Будут удалены пользователи не посещавшие сайт более',
        'asset_condition'   => 'И имеющие в своем активе не более',
        'deleted_users'     => 'Будет удалено пользователей',
        'delete_users'      => 'Удалить пользователей',
    ],

    'errors' => [
        'hint' => 'Внимание! Запись логов выключена в настройках!',
    ],

    'files' => [
        'confirm_delete_dir'  => 'Вы действительно хотите удалить эту директорию?',
        'confirm_delete_file' => 'Вы действительно хотите удалить этот файл?',
        'objects'             => 'Объектов',
        'lines'               => 'Строк',
        'changed'             => 'Изменен',
        'empty_objects'       => 'Объектов нет!',
        'create_object'       => 'Создание нового объекта',
        'directory_name'      => 'Название директории',
        'create_directory'    => 'Создать директорию',
        'file_name'           => 'Название файла (без расширения)',
        'create_file'         => 'Создать файл',
        'create_hint'         => 'Разрешены латинские символы и цифры, а также знаки дефис и нижнее подчеркивание',
        'file_editing'        => 'Редактирование файла',
        'edit_hint'           => 'Нажмите Ctrl+Enter для перевода строки, Shift+Enter для вставки линии',
    ],
];

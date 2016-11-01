<?php
App::view($config['themes'].'/index');

if (isset($_GET['act'])) {
    $act = check($_GET['act']);
} else {
    $act = 'index';
}
if (isset($_GET['start'])) {
    $start = abs(intval($_GET['start']));
} else {
    $start = 0;
}

if (is_admin(array(101, 102))) {
    show_title('Правила сайта');

    switch ($act):
    ############################################################################################
    ##                                    Главная страница                                    ##
    ############################################################################################
        case 'index':

            $rules = DB::run() -> queryFetch("SELECT * FROM `rules`;");

            if (!empty($rules)) {
                $rules['rules_text'] = str_replace(array('%SITENAME%', '%MAXBAN%'), array($config['title'], round($config['maxbantime'] / 1440)), $rules['rules_text']);

                echo bb_code($rules['rules_text']).'<hr />';

                echo 'Последнее изменение: '.date_fixed($rules['rules_time']).'<br /><br />';
            } else {
                show_error('Правила сайта еще не установлены!');
            }

            echo '<img src="/assets/img/images/edit.gif" alt="image" /> <a href="/admin/rules?act=edit">Редактировать</a><br />';
        break;

        ############################################################################################
        ##                                   Редактирование                                       ##
        ############################################################################################
        case 'edit':

            $rules = DB::run() -> queryFetch("SELECT * FROM `rules`;");

            echo '<div class="form">';
            echo '<form action="/admin/rules?act=change&amp;uid='.$_SESSION['token'].'" method="post">';

            echo '<textarea id="markItUp" cols="35" rows="20" name="msg">'.$rules['rules_text'].'</textarea><br />';
            echo '<input type="submit" value="Изменить" /></form></div><br />';

            echo '<b>Внутренние переменные:</b><br />';
            echo '%SITENAME% - Название сайта<br />';
            echo '%MAXBAN% - Максимальное время бана<br /><br />';

            echo '<i class="fa fa-arrow-circle-left"></i> <a href="/admin/rules">Вернуться</a><br />';
        break;

        ############################################################################################
        ##                                     Изменение                                          ##
        ############################################################################################
        case 'change':

            $uid = check($_GET['uid']);
            $msg = check($_POST['msg']);

            if ($uid == $_SESSION['token']) {
                if (utf_strlen($msg) > 0) {
                    $msg = str_replace('&#37;', '%', $msg);

                    DB::run() -> query("REPLACE INTO `rules` (`rules_id`, `rules_text`, `rules_time`) VALUES (?,?,?);", array(1, $msg, SITETIME));

                    $_SESSION['note'] = 'Правила успешно изменены!';
                    redirect("/admin/rules");
                } else {
                    show_error('Ошибка! Вы не ввели текст с правилами сайта!');
                }
            } else {
                show_error('Ошибка! Неверный идентификатор сессии, повторите действие!');
            }

            echo '<i class="fa fa-arrow-circle-left"></i> <a href="/admin/rules?act=edit">Вернуться</a><br />';
            echo '<i class="fa fa-arrow-circle-up"></i> <a href="/admin/rules">К правилам</a><br />';
        break;

    endswitch;

    echo '<i class="fa fa-wrench"></i> <a href="/admin">В админку</a><br />';

} else {
    redirect('/');
}

App::view($config['themes'].'/foot');
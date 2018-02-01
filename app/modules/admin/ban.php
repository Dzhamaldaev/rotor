<?php
view(setting('themes').'/index');

if (isset($_GET['act'])) {
    $act = check($_GET['act']);
} else {
    $act = 'index';
}
if (isset($_POST['uz'])) {
    $uz = check($_POST['uz']);
} elseif (isset($_GET['uz'])) {
    $uz = check($_GET['uz']);
} else {
    $uz = '';
}

if (isAdmin([101, 102, 103])) {
    //show_title('Бан/Разбан');

    switch ($action):


        ############################################################################################
        ##                                   Редактирование бана                                  ##
        ############################################################################################
        case 'editban':

            $user = DB::run() -> queryFetch("SELECT * FROM `users` WHERE `login`=? LIMIT 1;", [$uz]);
            if (!empty($user)) {
                echo userGender($user['login']).' <b>Профиль '.profile($user['login']).'</b> <br><br>';

                if ($user['level'] < 101 || $user['level'] > 105) {
                    if ($user['level'] == User::BANNED && $user['timeban'] > SITETIME) {
                        if (!empty($user['timelastban'])) {
                            echo 'Последний бан: '.dateFixed($user['timelastban'], 'j F Y / H:i').'<br>';
                            echo 'Забанил: '.profile($user['loginsendban']).'<br>';
                        }
                        echo 'До окончания бана: '.formatTime($user['timeban'] - SITETIME).'<br><br>';

                        if ($user['timeban'] - SITETIME >= 86400) {
                            $type = 'sut';
                            $file_time = round(((($user['timeban'] - SITETIME) / 60) / 60) / 24, 1);
                        } elseif (
                            $user['timeban'] - SITETIME >= 3600) {
                            $type = 'chas';
                            $file_time = round((($user['timeban'] - SITETIME) / 60) / 60, 1);
                        } else {
                            $type = 'min';
                            $file_time = round(($user['timeban'] - SITETIME) / 60);
                        }

                        echo '<div class="form">';
                        echo '<form method="post" action="/admin/ban?act=changeban&amp;uz='.$uz.'&amp;uid='.$_SESSION['token'].'">';
                        echo 'Время бана:<br><input name="bantime" value="'.$file_time.'"><br>';

                        $checked = ($type == 'min') ? ' checked' : '';
                        echo '<input name="bantype" type="radio" value="min"'.$checked.'> Минут<br>';
                        $checked = ($type == 'chas') ? ' checked' : '';
                        echo '<input name="bantype" type="radio" value="chas"'.$checked.'> Часов<br>';
                        $checked = ($type == 'sut') ? ' checked' : '';
                        echo '<input name="bantype" type="radio" value="sut"'.$checked.'> Суток<br>';

                        echo 'Причина бана:<br>';
                        echo '<textarea name="reasonban" cols="25" rows="5">'.$user['reasonban'].'</textarea><br>';

                        echo '<input value="Изменить" type="submit"></form></div><br>';
                    } else {
                        showError('Ошибка! Данный пользователь не забанен!');
                    }
                } else {
                    showError('Ошибка! Запрещено банить админов и модеров сайта!');
                }
            } else {
                showError('Ошибка! Пользователя с данным логином не существует!');
            }

            echo '<i class="fa fa-arrow-circle-left"></i> <a href="/admin/ban?act=edit&amp;uz='.$uz.'">Вернуться</a><br>';
        break;

        ############################################################################################
        ##                                     Изменение бана                                     ##
        ############################################################################################
        case 'changeban':

            $uid = check($_GET['uid']);
            $bantime = abs(round($_POST['bantime'], 1));
            $bantype = check($_POST['bantype']);
            $reasonban = check($_POST['reasonban']);
            $note = check($_POST['note']);

            if ($uid == $_SESSION['token']) {
                $user = DB::run() -> queryFetch("SELECT * FROM `users` WHERE `login`=? LIMIT 1;", [$uz]);

                if (!empty($user)) {
                    if ($user['level'] == User::BANNED && $user['timeban'] > SITETIME) {
                        if ($user['level'] < 101 || $user['level'] > 105) {
                            if ($bantype == 'min') {
                                $bantotaltime = $bantime;
                            }
                            if ($bantype == 'chas') {
                                $bantotaltime = round($bantime * 60);
                            }
                            if ($bantype == 'sut') {
                                $bantotaltime = round($bantime * 1440);
                            }

                            if ($bantotaltime > 0) {
                                if ($bantotaltime <= setting('maxbantime')) {
                                    if (utfStrlen($reasonban) >= 5 && utfStrlen($reasonban) <= 1000) {
                                        if (utfStrlen($note) <= 1000) {

                                            DB::update("UPDATE `users` SET `ban`=?, `timeban`=?, `reasonban`=?, `loginsendban`=? WHERE `login`=? LIMIT 1;", [1, SITETIME + ($bantotaltime * 60), $reasonban, getUser('login'), $uz]);

                                            DB::insert("INSERT INTO `banhist` (`user`, `send`, `type`, `reason`, `term`, `time`) VALUES (?, ?, ?, ?, ?, ?);", [$uz, getUser('login'), 2, $reasonban, $bantotaltime * 60, SITETIME]);

                                            setFlash('success', 'Данные успешно изменены!');
                                            redirect("/admin/ban?act=edit&uz=$uz");
                                        } else {
                                            showError('Ошибка! Слишком большая заметка, не более 1000 символов!');
                                        }
                                    } else {
                                        showError('Ошибка! Слишком длинная или короткая причина бана!');
                                    }
                                } else {
                                    showError('Ошибка! Максимальное время бана '.round(setting('maxbantime') / 1440).' суток!');
                                }
                            } else {
                                showError('Ошибка! Вы не указали время бана!');
                            }
                        } else {
                            showError('Ошибка! Запрещено банить админов и модеров сайта!');
                        }
                    } else {
                        showError('Ошибка! Данный пользователь не забанен!');
                    }
                } else {
                    showError('Ошибка! Пользователя с данным логином не существует!');
                }
            } else {
                showError('Ошибка! Неверный идентификатор сессии, повторите действие!');
            }

            echo '<i class="fa fa-arrow-circle-left"></i> <a href="/admin/ban?act=editban&amp;uz='.$uz.'">Вернуться</a><br>';
        break;


        ############################################################################################
        ##                                    Разбан пользователя                                 ##
        ############################################################################################
        case 'razban':

            $uid = check($_GET['uid']);

            if ($uid == $_SESSION['token']) {
                $user = DB::run() -> queryFetch("SELECT * FROM `users` WHERE `login`=? LIMIT 1;", [$uz]);

                if (!empty($user)) {
                    if ($user['level'] == User::BANNED) {
                        if ($user['totalban'] > 0 && $user['timeban'] > SITETIME + 43200) {
                            $bancount = 1;
                        } else {
                            $bancount = 0;
                        }

                        DB::update("UPDATE `users` SET `ban`=?, `timeban`=?, `totalban`=`totalban`-?, `explainban`=? WHERE `login`=? LIMIT 1;", [0, 0, $bancount, 0, $uz]);

                        DB::insert("INSERT INTO `banhist` (`user`, `send`, `time`) VALUES (?, ?, ?);", [$uz, getUser('login'), SITETIME]);

                        setFlash('success', 'Аккаунт успешно разблокирован!');
                        redirect("/admin/ban?act=edit&uz=$uz");
                    } else {
                        showError('Ошибка! Данный аккаунт уже разблокирован!');
                    }
                } else {
                    showError('Ошибка! Пользователя с данным логином не существует!');
                }
            } else {
                showError('Ошибка! Неверный идентификатор сессии, повторите действие!');
            }

            echo '<i class="fa fa-arrow-circle-left"></i> <a href="/admin/ban?act=edit&amp;uz='.$uz.'">Вернуться</a><br>';
        break;

        ############################################################################################
        ##                                   Удаление пользователя                                ##
        ############################################################################################
        case 'deluser':

            $uid = check($_GET['uid']);

            if ($uid == $_SESSION['token']) {
                $user = DB::run() -> queryFetch("SELECT * FROM `users` WHERE `login`=? LIMIT 1;", [$uz]);

                if (!empty($user)) {
                    if ($user['totalban'] >= 5) {
                        if ($user['level'] < 101 || $user['level'] > 105) {

                            $blackmail = DB::run() -> querySingle("SELECT `id` FROM `blacklist` WHERE `type`=? AND `value`=? LIMIT 1;", [1, $user['email']]);
                            if (empty($blackmail) && !empty($user['email'])) {
                                DB::insert("INSERT INTO `blacklist` (`type`, `value`, `user`, `time`) VALUES (?, ?, ?, ?);", [1, $user['email'], getUser('login'), SITETIME]);
                            }

                            $blacklogin = DB::run() -> querySingle("SELECT `id` FROM `blacklist` WHERE `type`=? AND `value`=? LIMIT 1;", [2, strtolower($user['login'])]);
                            if (empty($blacklogin)) {
                                DB::insert("INSERT INTO `blacklist` (`type`, `value`, `user`, `time`) VALUES (?, ?, ?, ?);", [2, $user['login'], getUser('login'), SITETIME]);
                            }

                            deleteAlbum($uz);
                            deleteUser($uz);

                            echo 'Данные занесены в черный список!<br>';
                            echo '<i class="fa fa-check"></i> <b>Профиль пользователя успешно удален!</b><br><br>';
                        } else {
                            showError('Ошибка! Запрещено банить админов и модеров сайта!');
                        }
                    } else {
                        showError('Ошибка! У пользователя менее 5 нарушений, удаление невозможно!');
                    }
                } else {
                    showError('Ошибка! Пользователя с данным логином не существует!');
                }
            } else {
                showError('Ошибка! Неверный идентификатор сессии, повторите действие!');
            }

            echo'<i class="fa fa-arrow-circle-left"></i> <a href="/admin/ban">Вернуться</a><br>';
        break;

    endswitch;

    echo '<i class="fa fa-wrench"></i> <a href="/admin">В админку</a><br>';

} else {
    redirect("/");
}

view(setting('themes').'/foot');

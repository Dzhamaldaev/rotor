<?php
#---------------------------------------------#
#      ********* RotorCMS *********           #
#           Author  :  Vantuz                 #
#            Email  :  visavi.net@mail.ru     #
#             Site  :  http://visavi.net      #
#              ICQ  :  36-44-66               #
#            Skype  :  vantuzilla             #
#---------------------------------------------#
require_once ('includes/start.php');
require_once ('includes/functions.php');
require_once ('includes/header.php');
include_once ('themes/header.php');

include_once (DATADIR.'/main/index_head.dat');

render ('index');

include_once (DATADIR.'/main/index_foot.dat');

include_once ('themes/footer.php');
?>

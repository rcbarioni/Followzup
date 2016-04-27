<?php

# ========================================================================================
#
#   FOLLOWZUP PROJECT
#   RICARDO BARIONI - MARÇO 2016
#
# ========================================================================================
#
#   Copyright (C) 2016 Ricardo Camargo Barioni
#
#   This program is free software: you can redistribute it and/or modify it under
#   the terms of the GNU General Public License as published by the Free Software
#   Foundation, either version 3 of the License, or any later version.
#
#   This program is distributed in the hope that it will be useful, but WITHOUT 
#   ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS 
#   FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with this program.  If not, see <http://www.gnu.org/licenses/>
#
# ========================================================================================

    $wquery = "insert into log (dateincl, idagent, ipuser, iduser, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', 1101)";
    $wres   = mysql_query($wquery,$wsystem_dbid);

    include('mod/head.php');

    echo    '<div style="background-color: #2a3670; text-align: center; color: #fff; font-size: 20px; padding: 10px;">Bem vindo ao Followzup</div>';

    echo        '<div style="background: linear-gradient(to bottom, black 70%, #333 100%); text-align: center; padding: 150px 220px 0px 200px; ">';
    echo            '<img alt="followzup" src="img/followzup_logo3.jpg" width="100%">';
    echo            '<span style="font-family: verdana;font-size: 32px;">Comunicação criptografada para a Internet das coisas.<br><br><br></span>';
    echo            '<a href="http://github.com/rcbarioni/Followzup" target="_blank" style="font-family: verdana; font-size: 14px; color: #aaa;">';
    echo                '<img class="roundcorner2" alt="github" src="img/GitHub_Logo.png" width="15%" style="background: white; padding: 5px 10px;">';
    echo                '<span><br>Fique tranquilo, Followzup é software livre.<br>Acesse e conheça o projeto.<br><br><br><br></span>';
    echo            '</a>';
    echo        '</div>';

    echo    '</div>';

    include('mod/tail.php');

?>

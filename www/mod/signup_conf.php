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

    if ( $widuser != "" )
    {
        session_destroy();
        $wquery  = "insert into log (dateincl, idagent, ipuser, iduser, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', 1208)";
        $wres    = mysql_query($wquery,$wsystem_dbid);
        $widuser = "";
    }

    $wkey   = "";
    $wframe = "";

    if ( isset($_GET["key"]) )   $wkey   = cleanText($_GET["key"]);
    if ( isset($_GET["frame"]) ) $wframe = cleanText($_GET["frame"]);

    $wquery  = "select pkpri from pkeys where idkey = 'keyweb'";
    $wres    = mysql_query($wquery,$wsystem_dbid);
    $wnumreg = mysql_num_rows($wres);

    if ( $wnumreg == 0 ) $wdecrypted = "";

    else
    {
        $wvetor = mysql_fetch_row($wres);
        $wpri64 = $wvetor[0];
        $wdecrypted = datadecrypt($wframe,$wkey,$wpri64);
    }

    if ( $wdecrypted == "" )
    {
        header("Location: .");
        exit;
    }

    $wdata = explode(" ",$wdecrypted);

    $wnewiduser = $wdata[0];
    $winclusao  = $wdata[1] . " " . $wdata[2];

    $wquery1  = "select regstatus, email from users where iduser = '$wnewiduser' and dateincl = '$winclusao'";

    $wres1    = mysql_query($wquery1,$wsystem_dbid);
    $wnumreg1 = intval(mysql_num_rows($wres1));

    if ( $wnumreg1 == 0 )
    {
        header("Location: .");
        exit;
    }

    $wvetor1    = mysql_fetch_row($wres1);
    $wregstatus = $wvetor1[0];
    $wemail     = $wvetor1[1];

    if ( $wregstatus != "n" )
    {
        header("Location: .");
        exit;
    }

    $wquery = "update users set regstatus = 'a' where iduser = '$wnewiduser'";
    $wres   = mysql_query($wquery,$wsystem_dbid);

    $wquery = "insert into log (dateincl, idagent, ipuser, iduser, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$wnewiduser', 1310)";
    $wres   = mysql_query($wquery,$wsystem_dbid);

    include("mod/head.php");

    echo    '<div style="background-color: #2a3670; text-align: center; color: #fff; font-size: 20px; padding: 10px;">Nova Conta</div><br><br>';

    echo    '<div class="box">';

    echo        '<br><span class="msg1">Inclusão da nova conta realizada com sucesso.<br><br>O novo USER-ID é:&nbsp;&nbsp;' . $wnewiduser . '</span>';

    echo    '</div>';

    $wposx = 0;
    $wposy = 0;

    include("mod/tail.php");

?>

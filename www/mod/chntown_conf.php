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

    $wdata      = explode(" ",$wdecrypted);
    $widchannel = $wdata[0];
    $wnewid     = $wdata[1];
    $winclusao  = $wdata[2] . " " . $wdata[3];

    $wquery1  = "select maxchannels, email from users where iduser = '$wnewid' and regstatus = 'a'";

    $wres1    = mysql_query($wquery1,$wsystem_dbid);
    $wnumreg1 = intval(mysql_num_rows($wres1));

    if ( $wnumreg1 == 0 )
    {
        header("Location: .");
        exit;
    }

    $wvetor1   = mysql_fetch_row($wres1);
    $wmaxchns  = $wvetor1[0];
    $wnewemail = $wvetor1[1];

    $wquery1  = "select regstatus, tag from channels where idchannel = '$widchannel' and newiduser = '$wnewid' and datetransf = '$winclusao'";

    $wres1    = mysql_query($wquery1,$wsystem_dbid);
    $wnumreg1 = intval(mysql_num_rows($wres1));

    $wsucesso = "Transferência de propriedade<br>concluída com sucesso.";;

    if ( $wnumreg1 == 0 )
    {

        $wsucesso = "Solicitação de Transferência de propriedade<br>fora do prazo ou já realizada.";

        include("mod/head.php");

        echo    '<div style="background-color: #762A50; text-align: center; color: #fff; font-size: 20px; padding: 10px;">Canais (desenvolvedores)</div><br><br>';
        echo    '<div class="box">';
        echo        '<br><span class="msg1">Transferência de propriedade<br>concluída com sucesso.</span>';
        echo    '</div>';

        $wposx = 0;
        $wposy = 0;

        include("mod/tail.php");

        exit;

    }

    $wvetor1    = mysql_fetch_row($wres1);
    $wregstatus = $wvetor1[0];
    $wtag       = $wvetor1[1];

    if ( $wregstatus == "d" )
    {
        header("Location: .");
        exit;
    }

    $wquery1  = "select count(*) from channels where iduser = '$wnewid' and regstatus != 'd'";

    $wres1    = mysql_query($wquery1,$wsystem_dbid);
    $wvetor1  = mysql_fetch_row($wres1);
    $wqtdchns = $wvetor1[0];

    if ( $wqtdchns >= $wmaxchns )
    {

        $wsucesso = "Número máximo de canais<br>excedido para o usuário \"$wnewemail\".<br><br>A Transferência de propriedade<br>foi cancelada.";

        include("mod/head.php");

        echo    '<div style="background-color: #762A50; text-align: center; color: #fff; font-size: 20px; padding: 10px;">Canais (desenvolvedores)</div><br><br>';
        echo    '<div class="box">';
        echo        '<br><span class="msg1">' . $wsucesso . '</span>';
        echo    '</div>';

        $wposx = 0;
        $wposy = 0;

        include("mod/tail.php");

        exit;

    }

    $wquery = "update channels set iduser = '$wnewid', newiduser = '', datetransf = '0000-00-00 00:00:00' where idchannel = '$widchannel'";
    $wres   = mysql_query($wquery,$wsystem_dbid);

    $wquery = "insert into log (dateincl, idagent, ipuser, iduser, idchannel, operation, param) values (utc_timestamp(), '$widagent', '$wipuser', '$wnewid', '$widchannel', 2805, '$wtag')";
    $wres   = mysql_query($wquery,$wsystem_dbid);

    include("mod/head.php");

    echo    '<div style="background-color: #762A50; text-align: center; color: #fff; font-size: 20px; padding: 10px;">Canais (desenvolvedores)</div><br><br>';
    echo    '<div class="box">';
    echo        '<br><span class="msg1">' . $wsucesso . '</span>';
    echo    '</div>';

    $wposx = 0;
    $wposy = 0;

    include("mod/tail.php");

?>

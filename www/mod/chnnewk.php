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

    if ( $widuser == "" )
    {
        header("Location: .");
        exit;
    }

    if ( isset($_GET["chn"]) ) $_SESSION["idchannel"] = preg_replace('/[^a-z0-9]/',"",$_GET["chn"]);
    
    elseif ( !isset($_SESSION["idchannel"]) or !isset($_POST["chnnewk_go"]) )
    {
        header("Location: .");
        exit;
    }

    $widchannel = $_SESSION["idchannel"];

    $wquery1 = "select tag
                  from channels
                 where idchannel = '$widchannel' and iduser = '$widuser' and regstatus != 'd'";

    $wres1    = mysql_query($wquery1,$wsystem_dbid);
    $wnumreg1 = mysql_num_rows($wres1);

    if ( $wnumreg1 == 0 )
    {
        header("Location: chns");
        exit;
    }

    $wvetor1 = mysql_fetch_row($wres1);
    $wtag    = $wvetor1[0];

    if ( isset($_POST["chnnewk_go"]) )
    {
        
        $wform_idchannel = cleanText($_POST["chnnewk_idchannel"]);

        if ( strtolower($wform_idchannel) != $widchannel )
        {

            $wmsg = "Confirme o CHANNEL-ID para proceder<br>a renovação das chaves RSA.<br>Tag do Canal: $wtag";

            $wquery = "insert into log (dateincl, idagent, ipuser, iduser, idchannel, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', '$widchannel', 2602)";
            $wres   = mysql_query($wquery,$wsystem_dbid);

        }

        else
        {        

        $widkey = newkey($widchannel);

            $wquery2 = "update channels set idkey = '$widkey' where idchannel = '$widchannel'";
            $wres2   = mysql_query($wquery2,$wsystem_dbid);

            $wquery = "insert into log (dateincl, idagent, ipuser, iduser, idchannel, operation, param) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', '$widchannel', 2603, '$widkey')";
            $wres   = mysql_query($wquery,$wsystem_dbid);

            header("Location: chns");
            exit;

        }

    }

    else
    {

        $wquery = "insert into log (dateincl, idagent, ipuser, iduser, idchannel, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', '$widchannel', 2601)";
        $wres   = mysql_query($wquery,$wsystem_dbid);
        
    }

    include("mod/head.php");

    echo    '<div style="background-color: #762A50; text-align: center; color: #fff; font-size: 20px; padding: 10px;">Canais (desenvolvedores)</div>';

    echo    '<div class="box">';

    echo        '<span class="msg1">Renovar chaves assimétricas (RSA)</span>';

    if ( $wmsg == "" ) echo '<span class="msg1">Confirme o CHANNEL-ID para proceder<br>a renovação das chaves RSA.<br>Tag do Canal: ' . $wtag . '</span>';
    else               echo '<span class="msg1" style="color: red;">' . $wmsg . '</span>';

    echo        '<form method="post" action="." name="chnnewk_form" id="chnnewk_form" onsubmit="savePosition(this)">';

    echo            '<input type="hidden" name="oper" id="oper" value="chnnewk_go">';
    echo            '<input type="hidden" name="posx" id="posx" value="0">';
    echo            '<input type="hidden" name="posy" id="posy" value="0">';

    echo            '<div>';
    echo                '<input class="input1" style="margin: 0 auto; background-color: #ddddff; width: 150px;" name="chnnewk_idchannel" type="text" maxlength="12" aria-required="true" placeholder="ID" spellcheck="false" value="">';
    echo            '</div>';

    echo            '<div>';
    echo                '<button class="button1" type="submit" name="chnnewk_go">Confirmar</button>';
    echo            '</div>';

    echo            '<div>';
    echo                '<a class="button4" href="chns">Cancelar</a>';
    echo            '</div>';

    echo        '</form>';

    echo    '</div>';

    include("mod/tail.php");

?>

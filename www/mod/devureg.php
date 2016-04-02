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

    if ( isset($_POST["devureg_go"]) )
    {
        
        $week = gmdate("Y-m-d H:i:s", strtotime("-168 hours"));

        $wquery  = "select iddevice
                      from devices
                     where iduser = '$widuser' and lastact < '$week' and regstatus = 'a'";

        $wres    = mysql_query($wquery,$wsystem_dbid);

        while ( $wvetor = mysql_fetch_row($wres) )
        {

            $widdevice = $wvetor[0];

            $wquery2 = "update devices set devicetag = concat('.',iddevice,'.',devicetag), regstatus = 'd' where iddevice = '$widdevice' and iduser = '$widuser'";
            $wres2   = mysql_query($wquery2,$wsystem_dbid);

            $wquery2 = "insert into log (dateincl, idagent, ipuser, iduser, iddevice, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', '$widdevice', 3604)";
            $wres2   = mysql_query($wquery2,$wsystem_dbid);

        }

        header("Location: devs");
        exit;

    }

    else
    {

        $wquery = "insert into log (dateincl, idagent, ipuser, iduser, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', 3603)";
        $wres   = mysql_query($wquery,$wsystem_dbid);
        
    }

    include("mod/head.php");

    echo    '<div style="background-color: #2a3670; text-align: center; color: #fff; font-size: 20px; padding: 10px;">Dispositivos</div>';

    echo    '<div class="box">';

    echo        '<span class="msg1">Excluir dispositivos<br><br>Confirme a exclusão de todos os dispositivos<br>sem atividade nos últimos 7 dias</span>';

    echo        '<form method="post" action="." name="devureg_form" id="devureg_form" onsubmit="savePosition(this)">';

    echo            '<input type="hidden" name="oper" id="oper" value="devureg_go">';
    echo            '<input type="hidden" name="posx" id="posx" value="0">';
    echo            '<input type="hidden" name="posy" id="posy" value="0">';

    echo            '<div>';
    echo                '<button class="button1" type="submit" name="devureg_go">Confirmar</button><br>';
    echo            '</div>';

    echo            '<div>';
    echo                '<a class="button4" href="devs">Cancelar</a>';
    echo            '</div>';

    echo        '</form>';

    echo    '</div>';

    include("mod/tail.php");

?>

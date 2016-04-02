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

    if ( isset($_GET["tag"]) )
    {

        $wform_tag = cleanTag($_GET["tag"]);

        if ( $wform_tag == "" or $wform_tag != $_GET["tag"] )
        {
            header("Location: .");
            exit;
        }

        $wquery  = "select iddevice
                      from devices
                     where devicetag = BINARY '$wform_tag' and iduser = '$widuser' and regstatus = 'a'";

        $wres    = mysql_query($wquery,$wsystem_dbid);
        $wnumreg = mysql_num_rows($wres);

        if ( $wnumreg == 0 )
        {
            header("Location: devs");
            exit;
        }

        $wvetor = mysql_fetch_row($wres);

        $_SESSION["iddevice"]  = $wvetor[0];
        $_SESSION["devicetag"] = $wform_tag;

    }
    
    elseif ( !isset($_SESSION["iddevice"]) or !isset($_SESSION["devicetag"]) or !isset($_POST["devexcl_go"]) )
    {
        header("Location: .");
        exit;
    }

    if ( isset($_POST["devexcl_go"]) )
    {
        
        $widdevice = $_SESSION["iddevice"];

        $wquery  = "select iddevice
                      from devices
                     where iddevice = '$widdevice' and iduser = '$widuser' and regstatus = 'a'";

        $wres    = mysql_query($wquery,$wsystem_dbid);
        $wnumreg = mysql_num_rows($wres);

        if ( $wnumreg == 0 )
        {
            header("Location: devs");
            exit;
        }

        $wquery2 = "update devices set devicetag = concat('.',iddevice,'.',devicetag), regstatus = 'd' where iddevice = '$widdevice' and iduser = '$widuser'";
        $wres2   = mysql_query($wquery2,$wsystem_dbid);

        $wquery = "insert into log (dateincl, idagent, ipuser, iduser, iddevice, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', '$widdevice', 3602)";
        $wres   = mysql_query($wquery,$wsystem_dbid);

        header("Location: devs");
        exit;

    }

    else
    {

        $widdevice = $_SESSION["iddevice"];

        $wquery = "insert into log (dateincl, idagent, ipuser, iduser, iddevice, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', '$widdevice', 3601)";
        $wres   = mysql_query($wquery,$wsystem_dbid);
        
    }

    include("mod/head.php");

    echo    '<div style="background-color: #2a3670; text-align: center; color: #fff; font-size: 20px; padding: 10px;">Dispositivos</div>';

    echo    '<div class="box">';

    echo        '<span class="msg1">Excluir dispositivo<br><br>Confirme para excluir o dispositivo abaixo:<br><span style="color: blue;">' . $wform_tag . '</span></span>';

    echo        '<form method="post" action="." name="devexcl_form" id="devexcl_form" onsubmit="savePosition(this)">';

    echo            '<input type="hidden" name="oper" id="oper" value="devexcl_go">';
    echo            '<input type="hidden" name="posx" id="posx" value="0">';
    echo            '<input type="hidden" name="posy" id="posy" value="0">';

    echo            '<div>';
    echo                '<button class="button1" type="submit" name="devexcl_go">Confirmar</button><br>';
    echo            '</div>';

    echo            '<div>';
    echo                '<a class="button4" href="devs">Cancelar</a>';
    echo            '</div>';

    echo        '</form>';

    echo    '</div>';

    include("mod/tail.php");

?>

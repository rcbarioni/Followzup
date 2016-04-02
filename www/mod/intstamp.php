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

    if ( $widuser != "z00000000000" )
    {
        header("Location: .");
        exit;
    }

    if ( isset($_GET["int"]) ) $_SESSION["idinterface"] = preg_replace('/[^a-z0-9]/',"",$_GET["int"]);
    
    elseif ( !isset($_SESSION["idinterface"]) )
    {
        header("Location: .");
        exit;
    }

    $widinterface = $_SESSION["idinterface"];

    $wquery1 = "select stamp
                  from interfaces
                 where idinterface = '$widinterface' and iduser = '$widuser' and regstatus != 'd'";

    $wres1    = mysql_query($wquery1,$wsystem_dbid);
    $wnumreg1 = mysql_num_rows($wres1);

    if ( $wnumreg1 == 0 )
    {
        header("Location: ints");
        exit;
    }

    $wvetor1 = mysql_fetch_row($wres1);
    $wstamp  = $wvetor1[0];

    $wquery = "insert into log (dateincl, idagent, ipuser, iduser, operation, param) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', 5401, '$widinterface')";
    $wres   = mysql_query($wquery,$wsystem_dbid);
        
    $wquery  = "select pkmod, pkpux from pkeys where idkey = 'keydev'";
    $wres    = mysql_query($wquery,$wsystem_dbid);
    $wnumreg = mysql_num_rows($wres);

    $wmod = "null";
    $wpux = "null";

    if ( $wnumreg > 0 )
    {
        $wvetor = mysql_fetch_row($wres);
        $wmod   = $wvetor[0];
        $wpux   = $wvetor[1];
    }

    include("mod/head.php");

    echo    '<div style="background-color: #762A50; text-align: center; color: #fff; font-size: 20px; padding: 10px;">Interfaces (desenvolvedores)</div>';

    echo    '<div class="box">';

    echo        '<span class="msg1">Interface-ID: <b>' . $widinterface . '</b></span><span class="msg1">Stamp/Pux/Mod:</span>';

    echo        '<div style="text-align: center; padding:">';
    echo            '<textarea readonly class="text1" rows="4" style="padding: 10px; font-family: monospace;">' . $wstamp . '</textarea>';
    echo        '</div>';

    echo        '<div style="text-align: center; padding:">';
    echo            '<textarea readonly class="text1" rows="1" style="padding: 10px; font-family: monospace;">' . $wpux . '</textarea>';
    echo        '</div>';

    echo        '<div style="text-align: center; padding:">';
    echo            '<textarea readonly class="text1" rows="13" style="padding: 10px; font-family: monospace;">' . $wmod . '</textarea>';
    echo        '</div>';

    echo        '<div>';
    echo            '<a class="button4" href="ints">Retornar</a>';
    echo        '</div>';

    echo    '</div>';

    include("mod/tail.php");

?>

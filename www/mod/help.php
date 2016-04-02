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

    $wnewch = "";

    $wtabhelp = array ( "in" => "1. Bem-vindo ao Followzup",
                        "aa" => "2. Adaptar Aplicações",
                        "cs" => "3. Canais e Assinaturas",
                        "sm" => "4. Enviar Mensagens (smsg)",
                        "us" => "5. Verificar Usuários (chck)",
                        "tu" => "6. Termos de Uso e Privacidade" );

    if ( isset($_GET["ch"]) ) $wnewch = preg_replace('/[^a-z]/',"",$_GET["ch"]);

    if ( !isset($wtabhelp[$wnewch]) ) $wnewch = $whelp;

    $whelp = $wnewch;

    $_SESSION["help"] = $whelp;

    $wquery = "insert into log (dateincl, idagent, ipuser, iduser, operation, param) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', 1102, '$whelp')";
    $wres   = mysql_query($wquery,$wsystem_dbid);

    include('mod/head.php');

    echo    '<table>';
    echo        '<tr><td colspan=2 style="background-color: #2a3670; text-align: center; color: #fff; font-size: 20px; padding: 10px;">Ajuda</td></tr>';
    echo    '</table>';

    echo    '<div style="position: fixed; width: 240px; z-index: 1000;">';

    echo        '<table>';
    echo            '<tr>';
    echo                '<td style="padding: 10px; padding-top: 20px; width: 1px; white-space: nowrap;">';

    echo                    '<table>';

    foreach ( $wtabhelp as $wkey => $warray )
    {

        if ( $wkey == $whelp )
        {
            echo                '<tr>';
            echo                    '<td style="padding: 8px; border-bottom: 1px solid #ddd; background-color: #eee;">';
            echo                        '<span style="font-family: arial, sans-serif; font-size: 13px; color: black;">' . $warray . '</span>';
            echo                    '</td>';
            echo                '</tr>';
        }

        else
        {
            echo                '<tr onmouseover="style.backgroundColor=\'#FFFFFF\';" onmouseout="style.backgroundColor=\'transparent\';">';
            echo                    '<td style="padding: 8px; border-bottom: 1px solid #ddd; background-color: white;">';
            echo                        '<a href="help_' . $wkey . '">';
            echo                            '<span style="font-family: arial, sans-serif; font-size: 13px; color: #666;">' . $warray . '</span>';
            echo                        '</a>';
            echo                    '</td>';
            echo                '</tr>';
        }

    }

    echo                        '<tr><td style="min-width: 200px;">&nbsp;</td></tr>';

    echo                    '</table>';
    echo                '</td>';
    echo            '</tr>';
    echo        '</table>';
    echo    '</div>';


    echo    '<div style="margin-left: 240px;">';
    echo        '<table>';
    echo            '<tr>';
    echo                '<td style="padding: 60px; padding-top: 10px;">';

    $whow = "help_$whelp.php";

    include ("mod/$whow");

    echo                '</td>';
    echo            '</tr>';
    echo        '</table>';
    echo    '</div>';

    include('mod/tail.php');

?>

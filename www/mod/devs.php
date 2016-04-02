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

    $wquery = "insert into log (dateincl, idagent, ipuser, iduser, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', 3501)";
    $wres   = mysql_query($wquery,$wsystem_dbid);

    $week = gmdate("Y-m-d H:i:s", strtotime("-168 hours"));

    include("mod/head.php");

    echo    '<div style="background-color: #2a3670; text-align: center; color: #fff; font-size: 20px; padding: 10px;">Dispositivos</div>';

    echo    '<div class="box" style="width: 40%;">';

    echo        '<table>';

    echo            '<tr>';
    echo                '<td colspan="5" style="text-align: center; padding-bottom: 30px;"><span style="font-size: 22px; font-weight: bold;">Lista de Dispositivos Móveis</span></td>';
    echo            '</tr>';

    echo            '<tr>';

    echo                '<td style="text-align: left;   padding: 10px; border: 1px solid #666; background-color: #bbb;  color: white;             font-weight: bold; vertical-align: middle;">';
    echo                    '<span style="font-size: 16px;">Nome&nbsp;do&nbsp;dispositivo</span>';
    echo                '</td>';

    echo                '<td style="text-align: center; padding: 10px; border: 1px solid #666; background-color: white; color: white; width: 1px; font-weight: bold; vertical-align: middle;">';
    echo                    '<img alt="blank" src="img/icon_blank.png" width="24" height="24">';
    echo                '</td>';
    echo            '</tr>';

    $wquery1 = "select devices.iddevice, devices.devicetag, devices.dateincl, devices.lastact
                  from devices
                 where devices.regstatus = 'a' and devices.iduser = '$widuser'
                 order by 4 desc, 1";

    $wres1    = mysql_query($wquery1,$wsystem_dbid);
    $wnumreg1 = mysql_num_rows($wres1);

    $wflag = "no";

    if ( $wnumreg1 == 0 )
    {
        echo        '<tr>';
        echo            '<td colspan="3" style="border: 1px solid #666; padding: 30px; text-align: center;"><span>Não existem dispositivos registrados para esse usuário</td>';
        echo        '</tr>';
    }

    else
    {

        while ( $wvetor1 = mysql_fetch_row($wres1) )
        {

            $widdevice  = $wvetor1[0];
            $wdevicetag = $wvetor1[1];
            $wdateincl  = $wvetor1[2];
            $wlastact   = $wvetor1[3];

            echo    '<tr>';

            if ( $wlastact < $week )
            {
                $wflag = "yes";
                echo    '<td style="border: 1px solid #666; padding: 15px; text-align: left; vertical-align: middle; background-color: yellow;"><span><b>' . $wdevicetag . '</b></span></td>';
            }

            else echo   '<td style="border: 1px solid #666; padding: 15px; text-align: left; vertical-align: middle; background-color: white;"><span><b>' . $wdevicetag . '</b></span></td>';

            echo        '<td style="text-align: center; padding: 15px; border: 1px solid #666; background-color: white; color: white; width: 1px; font-weight: bold; vertical-align: middle;">';
            echo            '<table><tr>';
            echo                '<td style="padding: 0px;"><a href="devexcl_' . $wdevicetag . '" title="excluir"><img alt="delete" src="img/icon_delete.png" width="24" height="24"></a></td>';
            echo                '<td style="padding: 0px;">&nbsp;&nbsp;</td>';
            echo                '<td style="padding: 0px;"><a href="devedit_' . $wdevicetag . '" title="editar"><img alt="edit" src="img/icon_edit.png" width="24" height="24"></a></td>';
            echo            '</tr></table>';
            echo        '</td>';

            echo    '</tr>';

        }

    }

    echo        '</table>';

    if ( $wnumreg1 > 0 and $wflag == "yes" )
    {
        echo    '<br><br>';
        echo    '<table>';
        echo        '<tr>';
        echo            '<td>&nbsp;</td>';
        echo            '<td style="border: 1px solid #666; background-color: yellow; width: 1px; white-space: nowrap; text-align: center; font-size: 16px; padding: 10px;">';
        echo                'Dispositivos sem atividade nos últimos 7 dias<br>[&nbsp;<a href="devureg" style="color: #666;"><b><u>excluir todos</u></b></a>&nbsp;]';
        echo            '</td>';
        echo            '<td>&nbsp;</td>';
        echo        '</tr>';
        echo    '</table>';
    }

    echo    '</div>';

    include("mod/tail.php");

?>

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

    if ( $wop == "intnew" )
    {

        $wcount = $wcount + 1;
        $widinterface = idgenerator('int');
        $wstamp = md5(rand()) . md5(rand()) . md5(rand()) . md5(rand());

        $wquery2   = "insert into interfaces (idinterface, iduser, stamp, dateincl, regstatus) 
                                      values ('$widinterface', '$widuser', '$wstamp', '$wagora', 't')"; 

        while ( !mysql_query($wquery2,$wsystem_dbid) )
        {

            $wquery9 = "insert into log (dateincl, idagent, ipuser, iduser, operation, param) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', 1001, '$widinterface')";
            $wres9   = mysql_query($wquery9,$wsystem_dbid);

            $widinterface = idgenerator('int');
            $wquery2   = "insert into interfaces (idinterface, iduser, stamp, dateincl, regstatus) 
                                          values ('$widinterface', '$widuser', '$wstamp', '$wagora', 't')"; 

        }

        $wquery = "insert into log (dateincl, idagent, ipuser, iduser, operation, param) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', 5201, '$widinterface')";
        $wres   = mysql_query($wquery,$wsystem_dbid);

    }

    else
    {

        $wquery = "insert into log (dateincl, idagent, ipuser, iduser, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', 5101)";
        $wres   = mysql_query($wquery,$wsystem_dbid);

    }

    include("mod/head.php");

    echo    '<div style="background-color: #762A50; text-align: center; color: #fff; font-size: 20px; padding: 10px;">Interfaces (desenvolvedores)</div>';

    echo    '<div class="box" style="width: 70%;">';

    echo        '<table>';

    echo            '<tr>';
    echo                '<td colspan="5" style="text-align: center; padding-bottom: 30px;"><span style="font-size: 22px; font-weight: bold;">Lista de Interfaces</span></td>';
    echo            '</tr>';

    echo            '<tr>';
    echo                '<td style="text-align: left;   padding: 10px; border: 1px solid #666; background-color: #bbb; color: white; width: 1px; font-weight: bold; vertical-align: middle;"><span style="font-size: 16px;">Interface&nbsp;ID</span></td>';
    echo                '<td style="text-align: center; padding: 10px; border: 1px solid #666; background-color: #bbb; color: white;             font-weight: bold; vertical-align: middle;"><span style="font-size: 16px;">Usuários&nbsp;Registrados</span></td>';
    echo                '<td style="text-align: center; padding: 10px; border: 1px solid #666; background-color: #bbb; color: white;             font-weight: bold; vertical-align: middle;"><span style="font-size: 16px;">Dispositivos&nbsp;Registrados</span></td>';
    echo                '<td style="text-align: left;   padding: 10px; border: 1px solid #666; background-color: #bbb; color: white; width: 1px; font-weight: bold; vertical-align: middle;"><span style="font-size: 16px;">Status</span></td>';

    echo                '<td style="padding: 10px 15px; border: 1px solid #666; background-color: white; width: 1px; vertical-align: middle;">';
    echo                    '<a href="intnew" title="add interface"><img alt="plus" src="img/icon_plus.png" width="24" height="24"></a>';
    echo                '</td>';

    echo            '</tr>';

    $wquery1 = "select idinterface, regstatus,

                       (select count(distinct devices.iduser) 
                          from devices, users 
                         where devices.idinterface = interfaces.idinterface and 
                               devices.iduser = users.iduser and users.regstatus = 'a'),

                       (select count(*) 
                          from devices, users 
                         where devices.idinterface = interfaces.idinterface and devices.regstatus = 'a' and
                               devices.iduser = users.iduser and users.regstatus = 'a')

                          from interfaces
                         where regstatus != 'd' and iduser = '$widuser'
                         order by idinterface";

    $wres1    = mysql_query($wquery1,$wsystem_dbid);
    $wnumreg1 = mysql_num_rows($wres1);

    if ( $wnumreg1 == 0 )
    {
        
        echo        '<tr>';
        echo            '<td colspan="7" style="border: 1px solid #666; padding: 30px; text-align: center;">Não existem interfaces disponíveis</td>';
        echo        '</tr>';

    }

    else
    {

        while ( $wvetor1 = mysql_fetch_row($wres1) )
        {

            $widinterface = $wvetor1[0];
            $wstatus      = $wvetor1[1];
            $wtotusers    = $wvetor1[2];
            $wtotdevices  = $wvetor1[3];

            if ( $wtotusers == "0" )   $wtotusers   = "&nbsp;";
            if ( $wtotdevices == "0" ) $wtotdevices = "&nbsp;";

            if ( $wstatus == "a" )      $wstatus  = "Ativa";
            else                        $wstatus  = "Teste";

            echo    '<tr>';

            echo        '<td style="border: 1px solid #666; padding: 15px; text-align: left; vertical-align: middle;"><span style="font-family: monospace;">' . $widinterface . '</span></td>';

            echo        '<td style="border: 1px solid #666; padding: 15px; text-align: center; white-space: nowrap; vertical-align: middle;"><span>' . $wtotusers . '</span></td>';

            echo        '<td style="border: 1px solid #666; padding: 15px; text-align: center; white-space: nowrap; vertical-align: middle;"><span>' . $wtotdevices . '</span></td>';

            if ( $wstatus == "Ativa" )

               echo     '<td style="border: 1px solid #666; padding: 15px; text-align: left; vertical-align: middle;"><span>' . $wstatus . '</span></td>';

            else echo   '<td style="border: 1px solid #666; padding: 15px; text-align: left; vertical-align: middle; background-color: #ffdddd;"><span>' . $wstatus . '</span></td>';

            echo        '<td style="border: 1px solid #666; padding: 15px; vertical-align: middle; width: 1px; text-align: left;">';
            echo            '<div class="fmenu">';
            echo                '<ul>';
            echo                    '<li class="fmenu2"><img alt="action" src="img/icon_action.png" style="width: 24px; height: 24px; border: 0px;">';
            echo                        '<ul>';
            echo                            '<li><a href="intstamp_' . $widinterface . '">&nbsp;&nbsp;Mostrar Stamp</a></li>';
            echo                            '<li><a href="intexcl_'  . $widinterface . '">&nbsp;&nbsp;Excluir interface</a></li>';
            echo                        '</ul>';
            echo                    '</li>';
            echo                '</ul>';
            echo            '</div>';
            echo        '</td>';

            echo    '</tr>';

        }

    }

    echo        '</table>';

    echo        '<table>';
    echo            '<tr>';
    echo                '<td style="padding-top: 30px; text-align: center;">Clique no ícone azul (+) para incluir uma nova Interface</td>';
    echo            '</tr>';
    echo        '</table>';

    echo    '</div>';

    include("mod/tail.php");

?>

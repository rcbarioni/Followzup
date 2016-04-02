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

    $wquery = "insert into log (dateincl, idagent, ipuser, iduser, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', 2101)";
    $wres   = mysql_query($wquery,$wsystem_dbid);

    $wquery = "select count(*) from channels where iduser = '$widuser' and regstatus != 'd'";
    $wres   = mysql_query($wquery,$wsystem_dbid);

    $wvetor = mysql_fetch_row($wres);
    $wcount = $wvetor[0];

    include("mod/head.php");

    echo    '<div style="background-color: #762A50; text-align: center; color: #fff; font-size: 20px; padding: 10px;">Canais (desenvolvedores)</div>';

    echo    '<div class="box" style="width: 70%;">';

    echo        '<table>';

    echo            '<tr>';
    echo                '<td colspan="6" style="text-align: center; padding-bottom: 30px;"><span style="font-size: 22px; font-weight: bold;">Lista de Canais</span></td>';
    echo            '</tr>';

    echo            '<tr>';
    echo                '<td style="text-align: left;   padding: 10px; border: 1px solid #666; background-color: #bbb; color: white; width: 1px; font-weight: bold; vertical-align: middle;"><span style="font-size: 16px;">Ícone</span></td>';
    echo                '<td style="text-align: left;   padding: 10px; border: 1px solid #666; background-color: #bbb; color: white;             font-weight: bold; vertical-align: middle;"><span style="font-size: 16px;">Tag&nbsp;do&nbsp;Canal</span></td>';
    echo                '<td style="text-align: left;   padding: 10px; border: 1px solid #666; background-color: #bbb; color: white; width: 1px; font-weight: bold; vertical-align: middle;"><span style="font-size: 16px;">Channel&nbsp;ID</span></td>';
    echo                '<td style="text-align: center; padding: 10px; border: 1px solid #666; background-color: #bbb; color: white; width: 1px; font-weight: bold; vertical-align: middle;"><span style="font-size: 16px;">Tipo</span></td>';
    echo                '<td style="text-align: center; padding: 10px; border: 1px solid #666; background-color: #bbb; color: white; width: 1px; font-weight: bold; vertical-align: middle;"><span style="font-size: 16px;">Status</span></td>';

    if ( $wcount < $wmaxchns )
    {
        echo            '<td style="padding: 10px 15px; border: 1px solid #666; background-color: white; width: 1px; vertical-align: middle;">';
        echo                '<a href="chnedit_new" title="adicional canal"><img alt="plus" src="img/icon_plus.png" width="24" height="24"></a>';
        echo            '</td>';
    }

    else
    {
        echo      '<td style="padding: 10px 15px; border: 1px solid #666; background-color: white; width: 1px; vertical-align: middle;">';
        echo         '<img alt="blank" src="img/icon_blank.png" width="24" height="24">';
        echo      '</td>';
    }

    echo       '</tr>';

    $wquery1 = "select idchannel, tag, briefing, welcome, channelicon, channeltype, regstatus, channelseq, newiduser, 0 as order1,

                       (select count(*) 
                          from subscriptions,users 
                         where subscriptions.idchannel = channels.idchannel and subscriptions.regstatus = 'a' and
                               subscriptions.iduser = users.iduser and users.regstatus = 'a')

                          from channels
                         where regstatus != 'd' and iduser = '$widuser'
                         order by order1, tag";

    $wres1    = mysql_query($wquery1,$wsystem_dbid);
    $wnumreg1 = mysql_num_rows($wres1);

    if ( $wnumreg1 == 0 )
    {        
        echo   '<tr>';
        echo      '<td colspan="6" style="border: 1px solid #666; padding: 30px; text-align: center;">Não existem canais disponíveis</td>';
        echo   '</tr>';
    }

    else
    {

        while ( $wvetor1 = mysql_fetch_row($wres1) )
        {

            $widchannel   = $wvetor1[0];
            $wtag         = $wvetor1[1];
            $wbriefing    = $wvetor1[2];
            $wwelcome     = $wvetor1[3];
            $wchannelicon = $wvetor1[4];
            $wchanneltype = $wvetor1[5];
            $wstatus      = $wvetor1[6];
            $wchannelseq  = $wvetor1[7];
            $wnewiduser   = $wvetor1[8];
            $worder       = $wvetor1[9];
            $wtotsubs     = $wvetor1[10];

            if ( $wtotsubs == "0" ) $wtotsubs = "&nbsp;";

            if ( $wchanneltype == "u" ) $wchanneltype = "Público";
            else                        $wchanneltype = "Privado";

            if ( $wstatus == "a" )      $wstatusx = "Suspender";
            else                        $wstatusx = "Ativar";

            if ( $wstatus == "a" )      $wstatus  = "Ativo";
            else                        $wstatus  = "Suspenso";

            echo   '<tr>';

            echo      '<td style="border: 1px solid #666; padding: 15px; vertical-align: middle;"><img alt="icon" src="data:image/jpeg;base64,' . $wchannelicon . '" width="36" height="36"></td>';

            if ( $wnewiduser == "" )
                echo  '<td style="border: 1px solid #666; padding: 15px; text-align: left; white-space: nowrap; vertical-align: middle;"><span><b>' . $wtag . '</b></span></td>';

            else
                echo  '<td style="border: 1px solid #666; padding: 15px; text-align: left; white-space: nowrap; vertical-align: middle; background-color: #ddffdd;"><span><b>' . $wtag . '</b></span></td>';

            echo      '<td style="border: 1px solid #666; padding: 15px; text-align: left; vertical-align: middle;"><span style="font-family: monospace;">' . $widchannel . '</span></td>';

            if ( $wchanneltype == "Público" )
               echo   '<td style="border: 1px solid #666; padding: 15px; text-align: center; white-space: nowrap; vertical-align: middle;"><span>' . $wchanneltype . '</span></td>';

            else
               echo   '<td style="border: 1px solid #666; padding: 15px; text-align: center; white-space: nowrap; vertical-align: middle; background-color: #ddddff;"><span>' . $wchanneltype . '</span></td>';

            if ( $wstatus == "Ativo" )
               echo   '<td style="border: 1px solid #666; padding: 15px; text-align: center; vertical-align: middle;"><span>' . $wstatus . '</span></td>';

            else
               echo   '<td style="border: 1px solid #666; padding: 15px; text-align: center; vertical-align: middle; background-color: #ffdddd;"><span>' . $wstatus . '</span></td>';

            echo      '<td style="border: 1px solid #666; padding: 15px; vertical-align: middle; width: 1px; text-align: left;">';
            echo         '<div class="fmenu">';
            echo            '<ul>';
            echo               '<li class="fmenu2"><img alt="action" src="img/icon_action.png" style="width: 24px; height: 24px; border: 0px;">';
            echo                  '<ul>';
            echo                     '<li><a href="chnedit_' . $widchannel . '">&nbsp;&nbsp;Editar canal</a></li>';
            echo                     '<li><a href="chnswap_' . $widchannel . '">&nbsp;&nbsp;' . $wstatusx .' canal</a></li>';
            echo                     '<li><a href="chnupld_' . $widchannel . '">&nbsp;&nbsp;Upload ícone</a></li>';
            echo                     '<li><a href="chndowk_' . $widchannel . '_php">&nbsp;&nbsp;Download API PHP</a></li>';
            echo                     '<li><a href="chndowk_' . $widchannel . '_java">&nbsp;&nbsp;Download API JAVA</a></li>';
            echo                     '<li><a href="chnsend_' . $widchannel . '">&nbsp;&nbsp;Utilitário SMSG</a></li>';
            echo                     '<li><a href="chnnewk_' . $widchannel . '">&nbsp;&nbsp;Renovar chaves</a></li>';
            echo                     '<li><a href="chntown_' . $widchannel . '">&nbsp;&nbsp;Transferir propriedade</a></li>';
            echo                     '<li><a href="chnexcl_' . $widchannel . '">&nbsp;&nbsp;Excluir canal</a></li>';
            echo                  '</ul>';
            echo               '</li>';
            echo            '</ul>';
            echo         '</div>';
            echo      '</td>';
            echo   '</tr>';

        }

    }

    echo    '</table>';

    if ( $wcount < $wmaxchns )
    {
       echo '<table>';
       echo    '<tr>';
       echo       '<td style="padding-top: 30px; text-align: center;">Clique no ícone azul (+) para criar um novo Canal</td>';
       echo    '</tr>';
       echo '</table>';
    }

    echo '</div>';

    include('mod/tail.php');

?>

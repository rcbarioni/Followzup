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

    echo    '<!DOCTYPE HTML>';

    echo    '<html>';

    echo        '<head>';

    echo            '<title>Followzup - IOT messenger</title>';
    echo            '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
    echo            '<meta name="description" content="Followzup - IOT messenger">';
    echo            '<meta name="author" content="Followzup Team">';
    echo            '<meta name="keywords" content="b2p, p2p, message, messaging, communication, batch, bulk, IOT, open source, sms">';
    echo            '<link rel="shortcut icon" href="img/favicon.gif">';
    echo            '<link rel="stylesheet" href="css/normalize.css">';
    echo            '<link rel="stylesheet" href="css/followzup.css?version=1">';
    echo            '<script src="css/prefixfree.min.js"></script>';
    echo            '<script src="css/savepos.js"></script>';
    echo            '<script type="text/javascript" src="followzup.js"></script>';

    echo        '</head>';

    echo        '<body>';

    echo            '<div id="header">';

    echo                '<table>';

    echo                    '<tr>';

    echo                        '<td style="padding: 0px;">';
    echo                            '<a href=".">';
    echo                                '<img alt="followzup" src="img/followzup_banner_215.png" style="padding: 5px; background-color: #fff;">';
    echo                            '</a>';
    echo                        '</td>';

    echo                        '<td style="width: 1px; padding-top: 0px; padding-left: 40px;">';

    if ( $wop == "home" ) echo      '<div class="menu1"><a href=".">início</a></div>';
    else                  echo      '<div class="menu2"><a href=".">início</a></div>';

    echo                        '</td>';

    echo                        '<td style="width: 1px; padding-top: 0px;">';

    if ( $wop == "help" ) echo      '<div class="menu1"><a href="help_' . $whelp . '">ajuda</a></div>';
    else                  echo      '<div class="menu2"><a href="help_' . $whelp . '">ajuda</a></div>';

    echo                        '</td>';

    if ( $widuser != "" )
    {

        echo                    '<td style="width: 1px; padding-top: 0px;">';

        if ( substr($wop,0,3) == "dev" ) echo '<div class="menu1"><a href="devs">dispositivos</a></div>';
        else                             echo '<div class="menu2"><a href="devs">dispositivos</a></div>';

        echo                    '</td>';

        echo                    '<td style="width: 1px; padding-top: 0px;">';

        if ( substr($wop,0,3) == "chn" or substr($wop,0,3) == "pol" ) echo '<div class="menu1"><a href="chns">canais</a></div>';
        else                                                          echo '<div class="menu3"><a href="chns">canais</a></div>';

        echo                    '</td>';

        if ( $widuser == "z00000000000" )
        {

            echo                '<td style="width: 1px; padding-top: 0px;">';

            if ( substr($wop,0,3) == "int" ) echo '<div class="menu1"><a href="ints">interfaces</a></div>';
            else                             echo '<div class="menu3"><a href="ints">interfaces</a></div>';

            echo                '</td>';

        }

    }

    echo                        '<td style="width: 1px; padding-top: 0px;">';

    if ( $wop == "contact" or $wop == "contact_go" ) echo '<div class="menu1"><a href="contact">contato</a></div>';
    else                                             echo '<div class="menu2"><a href="contact">contato</a></div>';

    echo                        '</td>';

    if ( $widuser == "" )
    {

        echo                    '<td style="width: 1px; padding-top: 0px;">';

        if ( $wop == "signup" or $wop == "signup_go" ) echo '<div class="menu1"><a href="signup">nova&nbsp;conta</a></div>';
        else                                           echo '<div class="menu2"><a href="signup">nova&nbsp;conta</a></div>';

        echo                    '</td>';

        echo                    '<td style="width: 1px; padding-top: 0px;">';

        if ( $wop == "login" or $wop == "login_go" ) echo '<div class="menu1"><a href="login">entrar</a></div>';
        else                                         echo '<div class="menu2"><a href="login">entrar</a></div>';

        echo                    '</td>';

    }

    else
    {

        echo                    '<td style="width: 1px; padding-top: 0px;">';

        if ( $wop == "profile" or $wop == "profile_go" ) echo '<div class="menu1"><a href="profile">perfil</a></div>';
        else                                             echo '<div class="menu2"><a href="profile">perfil</a></div>';

        echo                    '</td>';

        echo                    '<td style="width: 1px; padding-top: 0px;">';
        echo                        '<div class="menu2"><a href="logout">sair</a></div>';
        echo                    '</td>';

    }

    if ( $widuser == "z00000000000" )
    {
        echo                    '<td style="width: 1px; padding-top: 0px;">';
        echo                        '<div class="menu3"><a href="admin">log</a></div>';
        echo                    '</td>';
    }

    echo                    '</tr>';
    echo                '</table>';

    echo            '</div>';

    echo            '<div id="content">';

?>

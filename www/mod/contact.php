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

    $wmsg = "";
    $wform_email = $wemail;
    $wform_name  = $wname;
    $wform_msg   = "";

    if ( $wop == "contact_go" )
    {

        if ( isset($_POST["contact_email"]) ) $wform_email = cleanText($_POST["contact_email"]);
        if ( isset($_POST["contact_name"]) )  $wform_name  = cleanText($_POST["contact_name"]);
        if ( isset($_POST["contact_msg"]) )   $wform_msg   = cleanText($_POST["contact_msg"]);

        if ( $wform_name == "" )
        {
            $wmsg = "Informe seu nome";
            $wquery = "insert into log (dateincl, idagent, ipuser, iduser, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', 1502)";
            $wres   = mysql_query($wquery,$wsystem_dbid);
        }

        elseif ( $wform_email == "" or !validaEmail($wform_email) )
        {           
            $wmsg = "Informe seu e-mail";
            $wquery = "insert into log (dateincl, idagent, ipuser, iduser, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', 1503)";
            $wres   = mysql_query($wquery,$wsystem_dbid);
        }

        elseif ( $wform_msg == "" )
        {
            $wmsg   = "informe a mensagem";;
            $wquery = "insert into log (dateincl, idagent, ipuser, iduser, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', 1504)";
            $wres   = mysql_query($wquery,$wsystem_dbid);
        }

        else
        {

            $wquery = "insert into contacts (dateincl, name, email, message) values ('$wagora', '$wform_name', '$wform_email', '$wform_msg')";
            $wres   = mysql_query($wquery,$wsystem_dbid);

            $wquery = "insert into log (dateincl, idagent, ipuser, iduser, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', 1505)";
            $wres   = mysql_query($wquery,$wsystem_dbid);
                
            include("mod/head.php");

            echo '<div style="background-color: #2a3670; text-align: center; color: #fff; font-size: 20px; padding: 10px;">Contato</div><br><br>';

            echo '<div class="box">';

            echo    '<br><span class="msg1">Mensagem enviada com sucesso.</span>';

            echo '</div>';

            $wposx = 0;
            $wposy = 0;

            include("mod/tail.php");

            exit;

        }

    }

    else
    {
        $wquery = "insert into log (dateincl, idagent, ipuser, iduser, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', 1501)";
        $wres   = mysql_query($wquery,$wsystem_dbid);
    }

    $wform_name  = stripslashes($wform_name);

    include("mod/head.php");

    echo    '<div style="background-color: #2a3670; text-align: center; color: #fff; font-size: 20px; padding: 10px;">Contato</div>';

    echo    '<div class="box">';

    if ( $wmsg == "" ) echo '<span class="msg1">Deixe sua mensagem:</span>';
    else               echo '<span class="msg1" style="color: red;">' . $wmsg . '</span>';

    echo        '<form method="post" action="." name="contact_form" id="contact_form" onsubmit="savePosition(this)">';

    echo            '<input type="hidden" name="oper" id="oper" value="contact_go">';
    echo            '<input type="hidden" name="posx" id="posx" value="0">';
    echo            '<input type="hidden" name="posy" id="posy" value="0">';

    echo            '<div style="text-align: left; padding-left: 24px;">';
    echo                '<span class="label1">Nome:</span>';
    echo                '<input class="input1" name="contact_name" type="text" maxlength="40" aria-required="true" spellcheck="false" value="' . $wform_name . '">';
    echo            '</div>';

    echo            '<div style="text-align: left; padding-left: 24px;">';
    echo                '<span class="label1">E-mail:</span>';
    echo                '<input class="input1" name="contact_email" type="text" maxlength="255" aria-required="true" spellcheck="false" value="' . $wform_email . '">';
    echo            '</div>';

    echo            '<div style="text-align: left; padding-left: 24px;">';
    echo                '<span class="label1">Mensagem:</span>';
    echo                '<textarea class="text1" maxlength="400" name="contact_msg" rows="5" aria-required="true" spellcheck="false">' . $wform_msg . '</textarea>';
    echo            '</div>';

    echo            '<div>';
    echo                '<button class="button1" type="submit" name="contact_go">Enviar</button><br>';
    echo            '</div>';

    echo        '</form>';

    echo    '</div>';

    include('mod/tail.php');

?>

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

    $wmsg = "";

    $wform_name = $wname;
;
    if ( $wop == "profile_go" )
    {

        $wform_name  = cleanText($_POST["profile_name"]);
        $wform_pass1 = cleanText($_POST["profile_pass1"]);
        $wform_pass2 = cleanText($_POST["profile_pass2"]);

        if ( $wform_name == "" )
        {
            $wmsg   = "Informe o nome";
            $wquery = "insert into log (dateincl, idagent, ipuser, iduser, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', 1602)";
            $wres   = mysql_query($wquery,$wsystem_dbid);
        }

        elseif ( ( $wform_pass1 != "" or $wform_pass2 != "" ) and ( $wform_pass1 != $wform_pass2 or strlen($wform_pass1) < 6 ) )
        {           
            $wmsg   = "Senha inválida";
            $wquery = "insert into log (dateincl, idagent, ipuser, iduser, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', 1603)";
            $wres   = mysql_query($wquery,$wsystem_dbid);
        }

        else
        {

            if ( $wname != $wform_name )
            {

                $wname = $wform_name;

                $wquery = "update users set name = '$wname' where iduser = '$widuser'";

                $wres   = mysql_query($wquery,$wsystem_dbid);

                $_SESSION["name"] = $wname;

            }

            if ( $wform_pass1 != "" )
            {

                $wpassmd5  = md5($wform_pass1) . md5(strrev($wform_pass1));

                $wquery = "update users set pass = '$wpassmd5' where iduser = '$widuser'";
                $wres   = mysql_query($wquery,$wsystem_dbid);

                $_SESSION["passmd5"] = $wpassmd5;

            }

            $wquery = "insert into log (dateincl, idagent, ipuser, iduser, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', 1604)";
            $wres   = mysql_query($wquery,$wsystem_dbid);
                
            include("mod/head.php");

            echo    '<div style="background-color: #2a3670; text-align: center; color: #fff; font-size: 20px; padding: 10px;">Perfil</div><br><br>';
            echo    '<div class="box">';
            echo        '<br><span class="msg1">Alterações realizadas com sucesso</span>';
            echo    '</div>';

            $wposx = 0;
            $wposy = 0;

            session_write_close();

            include("mod/tail.php");

            exit;

        }

    }

    else
    {
        $wquery = "insert into log (dateincl, idagent, ipuser, iduser, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', 1601)";
        $wres   = mysql_query($wquery,$wsystem_dbid);
    }

    include("mod/head.php");

    echo    '<div style="background-color: #2a3670; text-align: center; color: #fff; font-size: 20px; padding: 10px;">Perfil</div>';

    echo    '<div class="box">';

    if ( $wmsg == "" ) echo '<span class="msg1">Atualização do perfil:</span>';
    else               echo '<span class="msg1" style="color: red;">' . $wmsg . '</span>';

    echo        '<form method="post" action="." name="profile_form" id="profile_form" onsubmit="savePosition(this)">';

    echo            '<input type="hidden" name="oper" id="oper" value="profile_go">';
    echo            '<input type="hidden" name="posx" id="posx" value="0">';
    echo            '<input type="hidden" name="posy" id="posy" value="0">';

    echo            '<div style="text-align: left; padding-left: 24px;">';
    echo                '<span class="label1">Nome:</span>';
    echo                '<input class="input1" name="profile_name" type="text" maxlength="40" aria-required="true" spellcheck="false" value="' . $wform_name . '">';
    echo            '</div>';

    echo            '<div style="text-align: left; padding-left: 24px;">';
    echo                '<span class="label1">Nova senha:</span>';
    echo                '<input class="input1" name="profile_pass1" type="password" maxlength="40" aria-required="true" spellcheck="false" value="">';
    echo            '</div>';

    echo            '<div style="text-align: left; padding-left: 24px;">';
    echo                '<span class="label1">Confirme a nova senha:</span>';
    echo                '<input class="input1" name="profile_pass2" type="password" maxlength="40" aria-required="true" spellcheck="false" value="">';
    echo            '</div>';

    echo            '<div>';
    echo                '<button class="button1" type="submit" name="profile_go">Salvar</button><br>';
    echo            '</div>';

    echo        '</form>';

    echo    '</div>';

    include('mod/tail.php');

?>

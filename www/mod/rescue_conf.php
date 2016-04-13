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

    if ( $widuser != "" )
    {
        header("Location: .");
        exit;
    }

    $wmsg = "";

    if ( $wop == "rescue_conf_go" )
    {

        $wsucesso = "Senha alterada com sucesso.";

        $xiduser = $_SESSION["xiduser"];

        if ( $xiduser == "" )
        {
            header("Location: .");
            exit;
        }

        $wquery1  = "select iduser from users where iduser = '$xiduser' and regstatus = 'a'";
        $wres1    = mysql_query($wquery1,$wsystem_dbid);
        $wnumreg1 = intval(mysql_num_rows($wres1));

        if ( $wnumreg1 == 0 )
        {
            header("Location: .");
            exit;
        }

        $wform_pass1 = cleanText($_POST["rescue_pass1"]);
        $wform_pass2 = cleanText($_POST["rescue_pass2"]);

        if ( $wform_pass1 == "" or $wform_pass1 != $wform_pass2 or strlen($wform_pass1) < 6 )
        {
            $wmsg   = "Senha inválida";
            $wquery = "insert into log (dateincl, idagent, ipuser, iduser, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$xiduser', 1405)";
            $wres   = mysql_query($wquery,$wsystem_dbid);
        }

        else
        {

            $wpassmd5  = md5($wform_pass1) . md5(strrev($wform_pass1));

            $wquery = "update users set pass = '$wpassmd5', daterescue = '$wagora' where iduser = '$xiduser'";
            $wres   = mysql_query($wquery,$wsystem_dbid);

            $wquery = "insert into log (dateincl, idagent, ipuser, iduser, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$xiduser', 1406)";
            $wres   = mysql_query($wquery,$wsystem_dbid);
                
            $_SESSION["xiduser"] = "";

            include("mod/head.php");

            echo    '<div style="background-color: #2a3670; text-align: center; color: #fff; font-size: 20px; padding: 10px;">Recuperar Senha</div><br><br>';
            echo    '<div class="box">';
            echo        '<br><span class="msg1">Senha alterada com sucesso.</span>';
            echo    '</div>';

            $wposx = 0;
            $wposy = 0;

            include("mod/tail.php");

            exit;

        }

    }

    else
    {

        $wkey   = "";
        $wframe = "";

        if ( isset($_GET["key"]) )   $wkey   = cleanText($_GET["key"]);
        if ( isset($_GET["frame"]) ) $wframe = cleanText($_GET["frame"]);

        $wquery  = "select pkpri from pkeys where idkey = 'keyweb'";
        $wres    = mysql_query($wquery,$wsystem_dbid);
        $wnumreg = mysql_num_rows($wres);

        if ( $wnumreg == 0 ) $wdecrypted = "";

        else
        {
            $wvetor = mysql_fetch_row($wres);
            $wpri64 = $wvetor[0];
            $wdecrypted = datadecrypt($wframe,$wkey,$wpri64);
        }

        if ( $wdecrypted == "" )
        {
            header("Location: .");
            exit;
        }

        $wdata      = explode(" ",$wdecrypted);

        $xiduser    = $wdata[0];
        $winclusao  = $wdata[1] . " " . $wdata[2];

        $wquery1  = "select regstatus, email from users where iduser = '$xiduser' and daterescue = '$winclusao'";

        $wres1    = mysql_query($wquery1,$wsystem_dbid);
        $wnumreg1 = intval(mysql_num_rows($wres1));

        if ( $wnumreg1 == 0 )
        {
            header("Location: .");
            exit;
        }

        $wvetor1    = mysql_fetch_row($wres1);
        $wregstatus = $wvetor1[0];
        $wemail     = $wvetor1[1];

        if ( $wregstatus != "a" )
        {
            header("Location: .");
            exit;
        }

        $wquery = "insert into log (dateincl, idagent, ipuser, iduser, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$xiduser', 1403)";
        $wres   = mysql_query($wquery,$wsystem_dbid);

        $_SESSION["xiduser"] = $xiduser;

    }

    include("mod/head.php");

    echo    '<div style="background-color: #2a3670; text-align: center; color: #fff; font-size: 20px; padding: 10px;">Recuperar Senha</div>';

    echo    '<div class="box">';

    if ( $wmsg == "" ) echo '<span class="msg1">Informe e confirme a nova senha:</span>';
    else               echo '<span class="msg1" style="color: red;">' . $wmsg . '</span>';

    echo        '<form method="post" action="." name="rescue_form" id="rescue_form" onsubmit="savePosition(this)">';

    echo            '<input type="hidden" name="oper" id="oper" value="rescue_conf_go">';
    echo            '<input type="hidden" name="posx" id="posx" value="0">';
    echo            '<input type="hidden" name="posy" id="posy" value="0">';

    echo            '<div style="text-align: left; padding-left: 24px;">';
    echo                '<span class="label1">Nova senha:</span>';
    echo                '<input class="input1" name="rescue_pass1" type="password" maxlength="60" aria-required="true" value="">';
    echo            '</div>';

    echo            '<div style="text-align: left; padding-left: 24px;">';
    echo                '<span class="label1">Confirme a nova senha:</span>';
    echo                '<input class="input1" name="rescue_pass2" type="password" maxlength="60" aria-required="true" value="">';
    echo            '</div>';

    echo            '<div style="margin-top: 0;">';
    echo                '<button class="button1" type="submit" name="rescue_conf_go">Enviar</button>';
    echo            '</div>';

    echo        '</form>';

    echo    '</div>';

    include("mod/tail.php");

?>

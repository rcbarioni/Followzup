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
    $wplace = "";

    $wform_email = "";
    $wform_pass  = "";

    if ( $wop == "login_go" )
    {

        if ( isset($_POST["login_email"]) ) $wform_email = cleanText($_POST["login_email"]);
        if ( isset($_POST["login_pass"]) )  $wform_pass  = cleanText($_POST["login_pass"]);

        $wminute = gmdate("Hi");

        if ( $wform_email == "" or !validaEmail($wform_email) )
        {           
            $wmsg   = "Informe o e-mail";
            $wquery = "insert into log (dateincl, idagent, ipuser, operation) values (utc_timestamp(), '$widagent', '$wipuser', 1202)";
            $wres   = mysql_query($wquery,$wsystem_dbid);
            sleep(1);
        }

        elseif ( $wform_pass == "" )
        {
            $wmsg   = "Informe a senha";
            $wquery = "insert into log (dateincl, idagent, ipuser, operation, param) values (utc_timestamp(), '$widagent', '$wipuser', 1203, '$wform_email')";
            $wres   = mysql_query($wquery,$wsystem_dbid);
            sleep(1);
        }

        else
        {

            $wquery1  = "select iduser, name, pass, regstatus, maxchannels, datetry from users where email = '$wform_email' and regstatus = 'a'";
            $wres1    = mysql_query($wquery1,$wsystem_dbid);
            $wnumreg1 = intval(mysql_num_rows($wres1));

            if ( $wnumreg1 == 0 )
            {
                $wmsg   = "Informe o e-mail";
                $wquery = "insert into log (dateincl, idagent, ipuser, operation, param) values (utc_timestamp(), '$widagent', '$wipuser', 1202, '$wform_email')";
                $wres   = mysql_query($wquery,$wsystem_dbid);
            }

            else
            {

                $wvetor1    = mysql_fetch_row($wres1);
                $xiduser    = $wvetor1[0];
                $wname      = $wvetor1[1];
                $wpass      = $wvetor1[2];
                $wregstatus = $wvetor1[3];
                $wmaxchns   = $wvetor1[4];
                $wdatetry   = $wvetor1[5];

                $xemail     = $wform_email;
                $xpassmd5   = md5($wform_pass) . md5(strrev($wform_pass));

                if ( $wagora < $wdatetry )
                {

                    $wmsg   = "Senha incorreta";
                    $wquery = "insert into log (dateincl, idagent, ipuser, iduser, operation, param) values (utc_timestamp(), '$widagent', '$wipuser', '$xiduser', 1207, '$wform_pass')";
                    $wres   = mysql_query($wquery,$wsystem_dbid);

                }

                elseif ( $wpass != $xpassmd5 )
                {

                    $wdatetry = gmdate("Y-m-d H:i:s", strtotime("+5 seconds"));

                    $wquery = "update users set datetry = '$wdatetry' where iduser = '$xiduser'";
                    $wres   = mysql_query($wquery,$wsystem_dbid);

                    if ( $wform_pass == strtoupper($wform_pass) ) $wplace = "(verifique o caps-lock)";

                    $wmsg   = "Senha incorreta";
                    $wquery = "insert into log (dateincl, idagent, ipuser, iduser, operation, param) values (utc_timestamp(), '$widagent', '$wipuser', '$xiduser', 1204, '$wform_pass')";
                    $wres   = mysql_query($wquery,$wsystem_dbid);

                }

                else
                {

                    $wquery = "insert into log (dateincl, idagent, ipuser, iduser, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$xiduser', 1205)";
                    $wres   = mysql_query($wquery,$wsystem_dbid);
                
                    $_SESSION["iduser"]  = $xiduser;
                    $_SESSION["email"]   = $xemail;
                    $_SESSION["name"]    = $wname;
                    $_SESSION["passmd5"] = $xpassmd5;
                    $_SESSION["maxchns"] = $wmaxchns;
                    $_SESSION["admin"]   = "n";

                    session_write_close();

                    header("Location: .");
                    exit;
        
                }

            }
            
        }

    }

    else
    {
        $wquery = "insert into log (dateincl, idagent, ipuser, operation) values (utc_timestamp(), '$widagent', '$wipuser', 1201)";
        $wres   = mysql_query($wquery,$wsystem_dbid);
    }

    include("mod/head.php");

    echo    '<div style="background-color: #2a3670; text-align: center; color: #fff; font-size: 20px; padding: 10px;">Entrar</div>';

    echo    '<div class="box">';

    if ( $wmsg == "" ) echo '<span class="msg1">Informe login e senha:</span>';
    else               echo '<span class="msg1" style="color: red;">' . $wmsg . '</span>';

    echo        '<form method="post" action="." name="login_form" id="login_form" onsubmit="savePosition(this)">';

    echo            '<input type="hidden" name="oper" id="oper" value="login_go">';
    echo            '<input type="hidden" name="posx" id="posx" value="0">';
    echo            '<input type="hidden" name="posy" id="posy" value="0">';

    echo            '<div style="text-align: left; padding-left: 24px;">';
    echo                '<span class="label1">E-mail:</span>';
    echo                '<input class="input1" name="login_email" type="text" maxlength="255" aria-required="true" spellcheck="false" value="' . $wform_email . '">';
    echo            '</div>';

    echo            '<div style="text-align: left; padding-left: 24px;">';
    echo                '<span class="label1">Senha:</span>';
    echo                '<input class="input1" name="login_pass" type="password" maxlength="80" aria-required="true" placeholder="' . $wplace . '" spellcheck="false" value="">';
    echo            '</div>';

    echo            '<div>';
    echo                '<button class="button1" type="submit" name="login_go">Enviar</button><br><br>';
    echo            '</div>';

    echo            '<div>';
    echo                '<a class="button4" href="recover" style="width: 185px;">Recuperar&nbsp;Senha</a>';
    echo            '</div>';

    echo            '<div>';
    echo                '<a class="button4" href="signup" style="width: 185px;">Nova&nbsp;Conta</a>';
    echo            '</div>';

    echo        '</form>';

    echo    '</div>';

    include("mod/tail.php");

?>

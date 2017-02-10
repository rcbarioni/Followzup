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

    $wform_email = "";
    $wform_name  = "";
    $wform_pass1 = "";
    $wform_pass2 = "";
    $wform_terms = "";

    if ( $wop == "signup_go" )
    {

        if ( isset($_POST["signup_email"]) ) $wform_email = cleanText(strtolower($_POST["signup_email"]));
        if ( isset($_POST["signup_name"]) )  $wform_name  = cleanText($_POST["signup_name"]);
        if ( isset($_POST["signup_pass1"]) ) $wform_pass1 = cleantext($_POST["signup_pass1"]);
        if ( isset($_POST["signup_pass2"]) ) $wform_pass2 = cleanText($_POST["signup_pass2"]);
        if ( isset($_POST["signup_terms"]) ) $wform_terms = cleanText($_POST["signup_terms"]);

        if ( $wform_email == "" or !validaEmail($wform_email) )
        {           
            $wmsg   = "E-mail inválido";
            $wquery = "insert into log (dateincl, idagent, ipuser, operation) values (utc_timestamp(), '$widagent', '$wipuser', 1302)";
            $wres   = mysql_query($wquery,$wsystem_dbid);
        }

        elseif ( $wform_name == "" )
        {
            $wmsg   = "Informe o nome";
            $wquery = "insert into log (dateincl, idagent, ipuser, operation) values (utc_timestamp(), '$widagent', '$wipuser', 1303)";
            $wres   = mysql_query($wquery,$wsystem_dbid);
        }

        elseif ( $wform_pass1 == "" or $wform_pass1 != $wform_pass2 or strlen($wform_pass1) < 6 )
        {
            $wmsg   = "Senha inválida";
            $wquery = "insert into log (dateincl, idagent, ipuser, operation) values (utc_timestamp(), '$widagent', '$wipuser', 1304)";
            $wres   = mysql_query($wquery,$wsystem_dbid);
        }

        elseif ( $wform_terms != "ok" )
        {
            $wmsg   = "Os termos de uso devem ser aceitos";
            $wquery = "insert into log (dateincl, idagent, ipuser, operation) values (utc_timestamp(), '$widagent', '$wipuser', 1305)";
            $wres   = mysql_query($wquery,$wsystem_dbid);
        }

        else
        {

            $wquery1  = "select iduser, regstatus from users where email = '$wform_email'";
            $wres1    = mysql_query($wquery1,$wsystem_dbid);
            $wnumreg1 = mysql_num_rows($wres1);

            if ( $wnumreg1 == 1 )
            {

                $wvetor1    = mysql_fetch_row($wres1);
                $xiduser    = $wvetor1[0];
                $wregstatus = $wvetor1[1];

                if ( $wregstatus == "a" )
                {

                    $wmsg   = "E-mail já cadastrado";
                    $wquery = "insert into log (dateincl, idagent, ipuser, operation, param) values (utc_timestamp(), '$widagent', '$wipuser', 1306, '$wform_email')";
                    $wres   = mysql_query($wquery,$wsystem_dbid);

                }

                elseif ( $wregstatus != "n" )
                {

                    $wmsg   = "Informe seu e-mail";
                    $wquery = "insert into log (dateincl, idagent, ipuser, operation, param) values (utc_timestamp(), '$widagent', '$wipuser', 1307, '$wform_email')";
                    $wres   = mysql_query($wquery,$wsystem_dbid);

                }

                else
                {

                    $wpassmd5  = md5($wform_pass1) . md5(strrev($wform_pass1));

                    $wquery = "update users set name = '$wform_name', pass = '$wpassmd5', dateincl = '$wagora' where iduser = '$xiduser'";

                    $wres   = mysql_query($wquery,$wsystem_dbid);

                    $wquery = "insert into log (dateincl, idagent, ipuser, iduser, operation, param) values (utc_timestamp(), '$widagent', '$wipuser', '$xiduser', 1309, '$wform_email')";
                    $wres   = mysql_query($wquery,$wsystem_dbid);

                    $wquery  = "select pkpub from pkeys where idkey = 'keyweb'";
                    $wres    = mysql_query($wquery,$wsystem_dbid);
                    $wnumreg = mysql_num_rows($wres);

                    if ( $wnumreg == 0 ) $wencrypted = array ("ekey" => "", "data" => "");

                    else
                    {
                        $wvetor = mysql_fetch_row($wres);
                        $wpub64 = $wvetor[0];
                        $wencrypted = dataencrypt($xiduser . " " . $wagora,$wpub64);
                    }
                
                    $wlink = '<a href="http://' . $wsystem_home . '?confsign&key=' . $wencrypted["ekey"] . '&frame=' . $wencrypted["data"] . 
                             '" target="_blank"><b>Confirmar nova conta</b></a>';

                    $wcorpo  = buildMail ( "Prezado usuário,<br><br>
                                            Sua solicitação para cadastramento da conta foi realizada com sucesso.<br>
                                            Antes de entrar no sistema pela primeira vez, clique no <i>link</i> abaixo.<br><br>
                                            Caso não tenha solicitado o cadastramento de sua conta, desconsidere essa mensagem e por favor,<br>
                                            aceite nossas desculpas pelo incoveniente.<br><br>" . $wlink);

                    $wform_subject = "Followzup - Nova Conta";

                    if ( $wsystem_home == "www-followzup" )
                    {
                        echo '<br>Subject: ' . $wform_subject . '<br><br>';
                        echo $wcorpo;
                        exit;
                    }

                    include ("Mail.php");
                    include ("Mail/mime.php");

                    $wrecipients                = $wform_email;
                    $wcrlf                      = "\n";

                    $wheaders = array();

                    $wheaders["From"]           = "contact@followzup.com";
                    $wheaders["To"]             = $wform_email;
                    $wheaders["Subject"]        = $wform_subject;
                    $wheaders["Content-Type"]   = "text/html; charset=UTF-8";

                    $wmime_params = array();

                    $wmime_params["text_encoding"] = "7bit";
                    $wmime_params["text_charset"]  = "UTF-8";
                    $wmime_params["html_charset"]  = "UTF-8";
                    $wmime_params["head_charset"]  = "UTF-8";

                    $wmime = new Mail_mime(array('eol' => $wcrlf));
                    $wmime->setHTMLBody($wcorpo);
                    $wcorpo = $wmime->get($wmime_params);
                    $wheaders = $wmime->headers($wheaders);

                    $wsmtpinfo["host"]          = "mail.followzup.com";
                    $wsmtpinfo["port"]          = "2525";
                    $wsmtpinfo["auth"]          = true;
                    $wsmtpinfo["username"]      = "";
                    $wsmtpinfo["password"]      = "";

                    $wmail_object =& Mail::factory("mail", $wsmtpinfo);

                    $wmail_object->send($wrecipients, $wheaders, $wcorpo);

                    include("mod/head.php");

                    echo    '<div style="background-color: #2a3670; text-align: center; color: #fff; font-size: 20px; padding: 10px;">sign up</div><br><br>';
                    echo    '<div class="box">';
                    echo        '<br><span class="msg1">Inclusão realizada com sucesso.<br><br>
                                                        Antes de entrar no sistema pela primeira vez,<br>confirme o registro da nova conta<br>
                                                        por meio da mensagem enviada para seu e-mail.<br><br>Bem-vindo.</span>';
                    echo    '</div>';

                    $wposx = 0;
                    $wposy = 0;

                    include("mod/tail.php");

                    exit;

                }

            }
            
            else            
            {

                $xiduser = idgenerator("usr");

                $wpassmd5 = md5($wform_pass1) . md5(strrev($wform_pass1));

                $wquery2  = "insert into users (iduser, email, pass, dateincl, name, regstatus)
                                        values ('$xiduser', '$wform_email', '$wpassmd5', '$wagora', '$wform_name', 'n')";

                while ( !mysql_query($wquery2,$wsystem_dbid) )
                {

                    $wquery1  = "select iduser from users where email = '$wform_email'";
                    $wres1    = mysql_query($wquery1,$wsystem_dbid);
                    $wnumreg1 = intval(mysql_num_rows($wres1));

                    if ( $wnumreg1 > 0 )
                    {
                        header("Location: .");
                        exit;
                    }

                    $wquery9 = "insert into log (dateincl, idagent, ipuser, operation, param) values (utc_timestamp(), '$widagent', '$wipuser', 1001, '$xiduser')";
                    $wres9   = mysql_query($wquery9,$wsystem_dbid);

                    $xiduser = idgenerator("usr");
                    $wquery2 = "insert into users (iduser, email, pass, dateincl, name, regstatus)
                                           values ('$xiduser', '$wform_email', '$wpassmd5', '$wagora', '$wform_name', 'n')";
                }

                $wquery = "insert into log (dateincl, idagent, ipuser, iduser, operation, param) values (utc_timestamp(), '$widagent', '$wipuser', '$xiduser', 1308, '$wform_email')";
                $wres   = mysql_query($wquery,$wsystem_dbid);

                $wquery  = "select pkpub from pkeys where idkey = 'keyweb'";
                $wres    = mysql_query($wquery,$wsystem_dbid);
                $wnumreg = mysql_num_rows($wres);

                if ( $wnumreg == 0 ) $wencrypted = array ("ekey" => "", "data" => "");

                else
                {
                    $wvetor = mysql_fetch_row($wres);
                    $wpub64 = $wvetor[0];
                    $wencrypted = dataencrypt($xiduser . " " . $wagora,$wpub64);
                }

                $wlink = '<a href="http://' . $wsystem_home . '?confsign&key=' . $wencrypted["ekey"] . '&frame=' . $wencrypted["data"] . 
                         '" target="_blank"><b>Confirmar nova conta</b></a>';

                $wcorpo  = buildMail ( "Prezado usuário,<br><br>
                                        Sua solicitação para cadastramento da conta foi realizada com sucesso.<br>
                                        Antes de entrar no sistema pela primeira vez, clique no <i>link</i> abaixo.<br><br>
                                        Caso não tenha solicitado o cadastramento de sua conta, desconsidere essa mensagem e por favor,<br>
                                        aceite nossas desculpas pelo incoveniente.<br><br>" . $wlink);

                $wform_subject = "Followzup - Nova Conta";

                if ( $wsystem_home == "www-followzup" )
                {
                    echo '<br>Subject: ' . $wform_subject . '<br><br>';
                    echo $wcorpo;
                    exit;
                }

                include ("Mail.php");
                include ("Mail/mime.php");

                $wrecipients                = $wform_email;
                $wcrlf                      = "\n";

                $wheaders = array();

                $wheaders["From"]           = "contact@followzup.com";
                $wheaders["To"]             = $wform_email;
                $wheaders["Subject"]        = $wform_subject;
                $wheaders["Content-Type"]   = "text/html; charset=UTF-8";

                $wmime_params = array();

                $wmime_params["text_encoding"] = "7bit";
                $wmime_params["text_charset"]  = "UTF-8";
                $wmime_params["html_charset"]  = "UTF-8";
                $wmime_params["head_charset"]  = "UTF-8";

                $wmime = new Mail_mime(array('eol' => $wcrlf));
                $wmime->setHTMLBody($wcorpo);
                $wcorpo = $wmime->get($wmime_params);
                $wheaders = $wmime->headers($wheaders);

                $wsmtpinfo["host"]          = "mail.followzup.com";
                $wsmtpinfo["port"]          = "2525";
                $wsmtpinfo["auth"]          = true;
                $wsmtpinfo["username"]      = "";
                $wsmtpinfo["password"]      = "";

                $wmail_object =& Mail::factory("mail", $wsmtpinfo);

                $wmail_object->send($wrecipients, $wheaders, $wcorpo);

                include("mod/head.php");

                echo    '<div style="background-color: #2a3670; text-align: center; color: #fff; font-size: 20px; padding: 10px;">Nova Conta</div><br><br>';
                echo    '<div class="box">';
                echo        '<br><span class="msg1">Inclusão realizada com sucesso.<br><br>
                                                    Antes de entrar no sistema pela primeira vez,<br>confirme o registro da nova conta<br>
                                                    por meio da mensagem enviada para seu e-mail.<br><br>Bem-vindo.</span>';
                echo    '</div>';

                $wposx = 0;
                $wposy = 0;

                include("mod/tail.php");

                exit;

            }

        }

    }

    else
    {
        $wquery = "insert into log (dateincl, idagent, ipuser, operation) values (utc_timestamp(), '$widagent', '$wipuser', 1301)";
        $wres   = mysql_query($wquery,$wsystem_dbid);
    }

    include("mod/head.php");

    echo '<div style="background-color: #2a3670; text-align: center; color: #fff; font-size: 20px; padding: 10px;">Nova Conta</div>';

    echo '<div class="box">';

    if ( $wmsg == "" ) echo '<span class="msg1">Informe os dados da nova conta:</span>';
    else               echo '<span class="msg1" style="color: red;">' . $wmsg . '</span>';

    echo    '<form method="post" action="." name="signup_form" id="signup_form" onsubmit="savePosition(this)">';

    echo       '<input type="hidden" name="oper" id="oper" value="signup_go">';
    echo       '<input type="hidden" name="posx" id="posx" value="0">';
    echo       '<input type="hidden" name="posy" id="posy" value="0">';

    echo       '<div style="text-align: left; padding-left: 24px;">';
    echo          '<span class="label1">E-mail:</span>';
    echo          '<input class="input1" name="signup_email" type="text" maxlength="255" aria-required="true" spellcheck="false" value="' . $wform_email . '">';
    echo       '</div>';

    echo       '<div style="text-align: left; padding-left: 24px;">';
    echo          '<span class="label1">Nome:</span>';
    echo          '<input class="input1" name="signup_name" type="text" maxlength="40" aria-required="true" spellcheck="false" value="' . $wform_name . '">';
    echo       '</div>';

    echo       '<div style="text-align: left; padding-left: 24px;">';
    echo          '<span class="label1">Senha:</span>';
    echo          '<input class="input1" name="signup_pass1" type="password" maxlength="80" aria-required="true" spellcheck="false" value="">';
    echo       '</div>';

    echo       '<div style="text-align: left; padding-left: 24px;">';
    echo          '<span class="label1">Confirme a senha:</span>';
    echo          '<input class="input1" name="signup_pass2" type="password" maxlength="80" aria-required="true" spellcheck="false" value="">';
    echo       '</div>';

    echo       '<div style="text-align: center; vertical-align: middle;">';

    if ( $wform_terms == "ok" ) echo '<input type="checkbox" name="signup_terms" value="ok" checked>';
    else                        echo '<input type="checkbox" name="signup_terms" value="ok">';

    echo          '<span style="font-size: 16px;">&nbsp;&nbsp;Aceito os <a href="help_tu" target="_blank" style="color: #999;">Termos de Uso</a></span>';
    echo       '</div>';

    echo       '<div style="margin-top: 20px;">';
    echo          '<button class="button1" type="submit" name="signup_go">Enviar</button><br>';
    echo       '</div>';

    echo    '</form>';

    echo '</div>';

    include("mod/tail.php");

?>

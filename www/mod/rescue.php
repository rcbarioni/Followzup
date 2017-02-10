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

    if ( $wop == "rescue_go" )
    {

        if ( isset($_POST["rescue_email"]) ) $wform_email = cleanText($_POST["rescue_email"]);

        if ( $wform_email == "" or !validaEmail($wform_email) )
        {           
            $wmsg   = "Informe o e-mail:";
            $wquery = "insert into log (dateincl, idagent, ipuser, operation) values (utc_timestamp(), '$widagent', '$wipuser', 1404)";
            $wres   = mysql_query($wquery,$wsystem_dbid);
        }

        else
        {

            $wquery1  = "select iduser, regstatus from users where email = '$wform_email'";
            $wres1    = mysql_query($wquery1,$wsystem_dbid);
            $wnumreg1 = intval(mysql_num_rows($wres1));

            if ( $wnumreg1 == 0 )
            {           
                $wmsg   = "Informe o e-mail";
                $wquery = "insert into log (dateincl, idagent, ipuser, operation) values (utc_timestamp(), '$widagent', '$wipuser', 1404)";
                $wres   = mysql_query($wquery,$wsystem_dbid);
            }

            else
            {

                $wvetor1    = mysql_fetch_row($wres1);
                $xiduser    = $wvetor1[0];
                $wregstatus = $wvetor1[1];

                if ( $wregstatus != "a" )
                {           
                    $wmsg   = "Informe o e-mail";
                    $wquery = "insert into log (dateincl, idagent, ipuser, operation) values (utc_timestamp(), '$widagent', '$wipuser', 1404)";
                    $wres   = mysql_query($wquery,$wsystem_dbid);
                }

                else
                {

                    $wquery = "update users set daterescue = '$wagora' where iduser = '$xiduser'";
                    $wres   = mysql_query($wquery,$wsystem_dbid);

                    $wquery = "insert into log (dateincl, idagent, ipuser, iduser, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$xiduser', 1402)";
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

                    $wlink = '<a href="http://' . $wsystem_home . '?confreco&key=' . $wencrypted["ekey"] . '&frame=' . $wencrypted["data"] . 
                             '" target="_blank"><b>Recuperar Senha</b></a>';

                    $wcorpo  = buildMail( "Prezado usuário,<br><br>
                                           Sua solicitação para recuperar a senha foi realizada com sucesso.<br>
                                           Para efetuar a troca da senha, clique no <i>link</i> abaixo.<br><br>
                                           Caso não tenha solicitado a troca da senha, desconsidere essa mensagem e por favor,<br>
                                           aceite nossas desculpas pelo incoveniente.<br><br>" . $wlink);

                    $wsubject = "Followzup - Recuperar Senha";

                    if ( $wsystem_home == "www-followzup" )
                    {
                        echo "<br>Subject: " . $wsubject . "<br><br>";
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
                    $wheaders["Subject"]        = $wsubject;
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

                    echo    '<div style="background-color: #2a3670; text-align: center; color: #fff; font-size: 20px; padding: 10px;">Recuperar Senha</div><br><br>';
                    echo    '<div class="box">';
                    echo        '<br><span class="msg1">Solicitação realizada com sucesso.<br><br>
                                                        Para concluir a recuperação da senha,<br>confirme a mensagem<br>
                                                        enviada para seu e-mail.</span>';
                    echo    '</div>';

                    $wposx = 0;
                    $wpoxy = 0;

                    include("mod/tail.php");

                    exit;

                }

            }
            
        }

        sleep(2);

    }

    else
    {
        $wquery = "insert into log (dateincl, idagent, ipuser, operation) values (utc_timestamp(), '$widagent', '$wipuser', 1401)";
        $wres   = mysql_query($wquery,$wsystem_dbid);
    }

    include("mod/head.php");

    echo    '<div style="background-color: #2a3670; text-align: center; color: #fff; font-size: 20px; padding: 10px;">Recuperar Senha</div>';

    echo    '<div class="box">';

    if ( $wmsg == "" ) echo '<span class="msg1">Recuperar Senha</span>';
    else               echo '<span class="msg1" style="color: red;">' . $wmsg . '</span>';

    echo        '<form method="post" action="." name="rescue_form" id="rescue_form" onsubmit="savePosition(this)">';

    echo            '<input type="hidden" name="oper" id="oper" value="rescue_go">';
    echo            '<input type="hidden" name="posx" id="posx" value="0">';
    echo            '<input type="hidden" name="posy" id="posy" value="0">';

    echo            '<div style="text-align: left; padding-left: 24px; padding-right: 24px;">';
    echo                '<span class="label1">E-mail:</span>';
    echo                '<input class="input1" name="rescue_email" type="text" maxlength="255" aria-required="true" spellcheck="false" value="' . $wform_email . '">';
    echo            '</div>';

    echo            '<div>';
    echo                '<button class="button1" type="submit" name="rescue_go">Enviar</button><br>';
    echo            '</div>';

    echo        '</form>';

    echo    '</div>';

    include("mod/tail.php");

?>

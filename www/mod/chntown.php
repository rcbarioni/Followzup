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

    if ( isset($_GET["chn"]) ) $_SESSION["idchannel"] = preg_replace('/[^a-z0-9]/',"",$_GET["chn"]);
    
    elseif ( !isset($_SESSION["idchannel"]) or !isset($_POST["chntown_go"]) )
    {
        header("Location: .");
        exit;
    }

    $widchannel = $_SESSION["idchannel"];

    $wquery1 = "select tag, newiduser
                  from channels
                 where idchannel = '$widchannel' and iduser = '$widuser' and regstatus != 'd'";

    $wres1    = mysql_query($wquery1,$wsystem_dbid);
    $wnumreg1 = mysql_num_rows($wres1);

    if ( $wnumreg1 == 0 )
    {
        header("Location: chns");
        exit;
    }

    $wvetor1 = mysql_fetch_row($wres1);
    $wtag    = $wvetor1[0];
    $wnewid  = $wvetor1[1];

    $wform_email = "";

    if ( $wnewid != "" )
    {

        $wquery1 = "select email from users where iduser = '$wnewid'";
        $wres1    = mysql_query($wquery1,$wsystem_dbid);
        $wnumreg1 = mysql_num_rows($wres1);

    if ( $wnumreg1 != 0 )
    {
        $wvetor1     = mysql_fetch_row($wres1);
        $wform_email = $wvetor1[0];
    }

    }

    if ( isset($_POST["chntown_go"]) )
    {
        
        $wform_email = cleanText($_POST["chntown_email"]);

        if ( $wform_email == "" and $wnewid != "" )
        {

            $wquery2 = "update channels set newiduser = '', datetransf = '0000-00-00 00:00:00' where idchannel = '$widchannel'";
            $wres2   = mysql_query($wquery2,$wsystem_dbid);

            $wquery = "insert into log (dateincl, idagent, ipuser, iduser, idchannel, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', '$widchannel', 2803)";
            $wres   = mysql_query($wquery,$wsystem_dbid);

            header("Location: chns");
            exit;

        }

        if ( $wform_email == "" or !validaEmail($wform_email) )
        {

            $wmsg = "Informe o e-mail do novo titular.<br>Tag do Canal: $wtag";

            $wquery = "insert into log (dateincl, idagent, ipuser, iduser, idchannel, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', '$widchannel', 2802)";
            $wres   = mysql_query($wquery,$wsystem_dbid);

        }

        else
        {        

            $wquery2  = "select iduser from users where email = '$wform_email' and regstatus = 'a' and iduser != '$widuser'";
            $wres2    = mysql_query($wquery2,$wsystem_dbid);
            $wnumreg2 = mysql_num_rows($wres2);

            if ( $wnumreg2 == 0 )
            {

                $wmsg = "E-mail inválido ou não informado.<br>Tag do Canal: $wtag";

                $wquery = "insert into log (dateincl, idagent, ipuser, iduser, idchannel, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', '$widchannel', 2802)";
                $wres   = mysql_query($wquery,$wsystem_dbid);

            }

            else
            {

                $wvetor2     = mysql_fetch_row($wres2);
                $wnewid      = $wvetor2[0];

                $wquery2 = "update channels set newiduser = '$wnewid', datetransf = '$wagora' where idchannel = '$widchannel'";
                $wres2   = mysql_query($wquery2,$wsystem_dbid);

                $wquery = "insert into log (dateincl, idagent, ipuser, iduser, idchannel, operation, param) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', '$widchannel', 2804, '$wform_email')";
                $wres   = mysql_query($wquery,$wsystem_dbid);

                $wquery  = "select pkpub from pkeys where idkey = 'keyweb'";
                $wres    = mysql_query($wquery,$wsystem_dbid);
                $wnumreg = mysql_num_rows($wres);

                if ( $wnumreg == 0 ) $wencrypted = array ("ekey" => "", "data" => "");

                else
                {
                    $wvetor = mysql_fetch_row($wres);
                    $wpub64 = $wvetor[0];
                    $wencrypted = dataencrypt($widchannel . ' ' . $wnewid . ' ' . $wagora,$wpub64);
                }

                $wlink = '<a href="http://' . $wsystem_home . '?conftown&key=' . $wencrypted["ekey"] . '&frame=' . $wencrypted["data"] . '" target="_blank"><b>Confirm ownership transfer</b></a>';

                $wcorpo  = buildMail("Prezado usuário,<br><br>
                                      A solicitação para transferência de propriedade do canal foi realizada com sucesso.<br>
                                      Para efetivar a transferência, clique no <i>link</i> abaixo.<br><br>
                                      Caso não tenha solicitado a transferência, desconsidere essa mensagem e por favor,<br>
                                      aceite nossas desculpas pelo incoveniente.<br><br>" . $wlink);

                $wform_subject = "Followzup - Transferência de propriedade de Canal";

                if ( $wsystem_home == "www-followzup" )
                {
                    echo "<br>Subject: " . $wform_subject . "<br><br>";
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
                $wsmtpinfo["username"]      = "contact";
                $wsmtpinfo["password"]      = "moXa1144";

                $wmail_object =& Mail::factory("mail", $wsmtpinfo);

                $wmail_object->send($wrecipients, $wheaders, $wcorpo);

                include("mod/head.php");

                echo '<div style="background-color: #762A50; text-align: center; color: #fff; font-size: 20px; padding: 10px;">system developers</div><br><br>';

                echo '<div class="box">';

                echo    '<br><span class="msg1">';
                echo    'Solicitação encaminhada com sucesso.<br><br>Para efetivar a transferência de propriedade do canal,<br>confirme a mensagem de e-mail enviada para o novo tutilar.';
                echo    '</span>';

                echo '</div>';

                $wposx = 0;
                $wposy = 0;

                include("mod/tail.php");

                exit;

            }

        }

    }

    else
    {

        $wquery = "insert into log (dateincl, idagent, ipuser, iduser, idchannel, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', '$widchannel', 2801)";
        $wres   = mysql_query($wquery,$wsystem_dbid);
        
    }

    include("mod/head.php");

    echo    '<div style="background-color: #762A50; text-align: center; color: #fff; font-size: 20px; padding: 10px;">Canais (desenvolvedores)</div>';

    echo    '<div class="box">';

    echo        '<span class="msg1">Transferência de Propriedade</span>';

    if ( $wmsg == "" ) echo '<span class="msg1">Informe o e-mail do novo titular.<br>Tag do Canal: ' . $wtag . '</span>';
    else               echo '<span class="msg1" style="color: red;">' . $wmsg . '</span>';

    echo        '<form method="post" action="." name="chntown_form" id="chntown_form" onsubmit="savePosition(this)">';

    echo            '<input type="hidden" name="oper" id="oper" value="chntown_go">';
    echo            '<input type="hidden" name="posx" id="posx" value="0">';
    echo            '<input type="hidden" name="posy" id="posy" value="0">';

    echo            '<div style="text-align: left; padding-left: 24px;">';
    echo                '<span class="label1">E-mail:</span>';
    echo                '<input class="input1" style="background-color: #ddddff;" name="chntown_email" type="text" maxlength="255" aria-required="true" spellcheck="false" value="' . $wform_email . '">';
    echo            '</div>';

    echo            '<div>';
    echo                '<button class="button1" type="submit" name="chntown_go">Submit</button>';
    echo            '</div>';

    echo            '<div>';
    echo                '<a class="button4" href="chns">Cancel</span></a>';
    echo            '</div>';

    echo        '</form>';

    echo    '</div>';

    include('mod/tail.php');

?>

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

    function fzup_send ($fzup_channel,$fzup_pub64,$fzup_lastseq,$fzup_user,$fzup_hours,$fzup_msgtext)
    {

        global $wsystem_home;

        $fzup_pub = base64_decode($fzup_pub64);
        $fzup_xml = "<usr>$fzup_user</usr><hrs>$fzup_hours</hrs><msg>" . base64_encode($fzup_msgtext) . "</msg>";

        do {

            // set next sequence and build frame request (xml)
            $fzup_lastseq = $fzup_lastseq + 1;
            $fzup_frame1  = "<" . '?xml version="1.0" encoding="utf-8"?' . "><followzup>";
            $fzup_frame1 .= "<com>smsg</com>";
            $fzup_frame1 .= "<seq>" . $fzup_lastseq . "</seq>";
            $fzup_frame1 .= $fzup_xml . "</followzup>";

            // encrypt and encode random key and request 
            $fzup_key1 = openssl_random_pseudo_bytes(24);
            $fzup_frame2 = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $fzup_key1, $fzup_frame1, MCRYPT_MODE_CBC, str_repeat(chr(0),16));
            openssl_public_encrypt($fzup_key1, $fzup_key2, $fzup_pub);
            $fzup_frame3 = base64_encode($fzup_frame2);
            $fzup_key3 = base64_encode($fzup_key2);

            // build curl and get response 
            $fzup_ch = curl_init();
            $fzup_conf = array ( CURLOPT_URL            => "http://$wsystem_home/wschannel",
                                 CURLOPT_POST           => true,
                                 CURLOPT_RETURNTRANSFER => true,
                                 CURLOPT_USERAGENT      => "wschannel: " . $fzup_channel,
                                 CURLOPT_POSTFIELDS     => array ( "id" => $fzup_channel, "key" => "$fzup_key3", "frame" => "$fzup_frame3" ) );
            curl_setopt_array($fzup_ch, $fzup_conf);
            $fzup_resp = curl_exec($fzup_ch);
            curl_close($fzup_ch);

            // decode and decrypt response 
            $fzup_respxml = @simplexml_load_string($fzup_resp);
            $fzup_retcode = $fzup_respxml->retcode;
            $fzup_retframe1 = $fzup_respxml->retframe;
            $fzup_retframe2 = base64_decode($fzup_retframe1);
            $fzup_retframe3 = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $fzup_key1, $fzup_retframe2, MCRYPT_MODE_CBC, str_repeat(chr(0),16));
            $fzup_retframe4 = @simplexml_load_string($fzup_retframe3);

            // if out of sequence, extract last used sequence
            if  ( $fzup_retcode == "6101" ) $fzup_lastseq = $fzup_retframe4->seq;

        // repeat request while out of sequence
        } while ( $fzup_retcode == "6101" );

        return array ( "$fzup_retcode", "$fzup_lastseq", "$fzup_retframe3" );

    }

    $wform_receiver = "all";
    $wform_lifetime = "1";
    $wform_message  = "";
    $wmsg = "";

    if ( $widuser == "" )
    {
        header("Location: .");
        exit;
    }

    if ( isset($_GET["chn"]) ) $_SESSION["idchannel"] = preg_replace('/[^a-z0-9]/',"",$_GET["chn"]);
    
    elseif ( !isset($_SESSION["idchannel"]) or !isset($_POST["chnsend_go"]) )
    {
        header("Location: .");
        exit;
    }

    $widchannel = $_SESSION["idchannel"];

    $wquery1 = "select channels.tag, channels.channelseq, pkeys.pkpub
                  from channels, pkeys
                 where channels.idchannel = '$widchannel' and channels.iduser = '$widuser' and channels.regstatus != 'd' and
                       channels.idchannel = pkeys.idchannel";

    $wres1    = mysql_query($wquery1,$wsystem_dbid);
    $wnumreg1 = mysql_num_rows($wres1);

    if ( $wnumreg1 == 0 )
    {
        header("Location: chns");
        exit;
    }

    $wvetor1    = mysql_fetch_row($wres1);
    $wform_tag  = $wvetor1[0];
    $wform_seq  = $wvetor1[1];
    $wform_pub  = $wvetor1[2];

    if ( isset($_POST["chnsend_go"]) )
    {
        
        $wform_receiver = cleanText(strtolower($_POST["chnsend_receiver"]));
        $wform_lifetime = cleanText(strtolower($_POST["chnsend_lifetime"]));
        $wform_message  = cleanText(substr(trim($_POST["chnsend_message"]),0,200));

        if ( $wform_lifetime == "" ) $wform_lifetime = "1";

        if ( $wform_receiver == "" or $wform_message == "" ) $wmsg = "Informe os parâmetros da mensagem:";

        else
        {

            $wreturn = fzup_send($widchannel,$wform_pub,$wform_seq,$wform_receiver,$wform_lifetime,$wform_message);

            $wlastseq = $wreturn[1];
            $wxml     = @simplexml_load_string($wreturn[2]);
            $wtotal   = $wxml -> snt;

            $wquery = "update channels set channelseq = '$wlastseq' where idchannel = '$widchannel'";
            $wres   = mysql_query($wquery,$wsystem_dbid);

            $wmsg = "Mensagens enviadas = " . $wtotal . "<br>Informe a próxima mensagem:";

        }

    }

    else
    {

        $wquery = "insert into log (dateincl, idagent, ipuser, iduser, idchannel, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', '$widchannel', 2702)";
        $wres   = mysql_query($wquery,$wsystem_dbid);
        
    }

    include("mod/head.php");

    echo '<div style="background-color: #762A50; text-align: center; color: #fff; font-size: 20px; padding: 10px;">Canais (desenvolvedores)</div>';

    echo '<div class="box">';

    echo    '<span class="msg1">Utilitário SMSG</span>';

    if ( $wmsg == "" ) echo '<span class="msg1">Informe os parâmetros da mensagem:</span>';
    else               echo '<span class="msg1" style="color: blue;">' . $wmsg . '</span>';

    echo        '<form method="post" action="." name="chnsend_form" id="chnsend_form" onsubmit="savePosition(this)">';

    echo            '<input type="hidden" name="oper" id="oper" value="chnsend_go">';
    echo            '<input type="hidden" name="posx" id="posx" value="0">';
    echo            '<input type="hidden" name="posy" id="posy" value="0">';

    echo            '<div style="text-align: left; padding-left: 24px;">';
    echo                '<span class="label1">Destinatário (all ou lista multicast)</span>';
    echo                '<input class="input1" name="chnsend_receiver" type="text" maxlength="200" aria-required="true" spellcheck="false" value="' . $wform_receiver . '">';
    echo            '</div>';

    echo            '<div style="text-align: left; padding-left: 24px;">';
    echo                '<span class="label1">Tempo de vida (em horas)</span>';
    echo                '<input class="input1" name="chnsend_lifetime" type="text" maxlength="12" aria-required="true" spellcheck="false" value="' . $wform_lifetime . '">';
    echo            '</div>';

    echo            '<div style="text-align: left; padding-left: 24px;">';
    echo                '<span class="label1">Messagem (até 200 caracteres):</span>';
    echo                '<textarea class="text1" maxlength="200" name="chnsend_message" rows="5" aria-required="true" spellcheck="false">' . $wform_message . '</textarea>';
    echo            '</div>';

    echo            '<div style="margin-top: 20px;">';
    echo                '<button class="button1" type="submit" name="chnsend_go">Enviar</button>';
    echo            '</div>';

    echo            '<div>';
    echo                '<a class="button4" href="chns">Retornar</a>';
    echo            '</div>';

    echo        '</form>';

    echo    '</div>';

    include("mod/tail.php");

?>

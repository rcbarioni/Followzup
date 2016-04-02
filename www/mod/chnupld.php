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

    elseif ( !isset($_SESSION["idchannel"]) or !isset($_POST["chnupld_go"]) )
    {
        header("Location: .");
        exit;
    }

    $widchannel = $_SESSION["idchannel"];

    $wquery1 = "select tag
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

    if ( isset($_POST["chnupld_go"]) )
    {

        $wfilename = cleanText($_FILES["ufile"]["name"][0]);
        $wfiletype = cleanText($_FILES["ufile"]["type"][0]);
        $wfiletemp = cleanText($_FILES["ufile"]["tmp_name"][0]);
        $wfilerror = cleanText($_FILES["ufile"]["error"][0]);
        $wfilesize = cleanText($_FILES["ufile"]["size"][0]);

        if ( $wfilename != "" and $wfilerror == "0" )
        {

            if ( $wfiletype == "image/jpeg" or $wfiletype == "image/pjpeg"  )
            {

                $wfiletype   = "jpg";
                $wfileimage  = imagecreatefromjpeg($wfiletemp);
                $wfilewidth  = imagesx($wfileimage);
                $wfileheight = imagesy($wfileimage);

                $wnewimage = imagecreatetruecolor(72,72);
                $white = imagecolorallocate($wnewimage, 255, 255, 255);
                imagefill($wnewimage, 0, 0, $white);
                imagecopyresampled($wnewimage, $wfileimage, 0, 0, 0, 0, 72, 72, $wfilewidth, $wfileheight);
                imagejpeg ($wnewimage, $wfiletemp, 70);
                $wblob = base64_encode(file_get_contents($wfiletemp));

            }

            elseif ( $wfiletype == "image/gif" )
            {

                $wfiletype   = "jpg";
                $wfileimage  = imagecreatefromgif($wfiletemp);
                $wfilewidth  = imagesx($wfileimage);
                $wfileheight = imagesy($wfileimage);

                $wnewimage = imagecreatetruecolor(72,72);
                $white = imagecolorallocate($wnewimage, 255, 255, 255);
                imagefill($wnewimage, 0, 0, $white);
                imagecopyresampled($wnewimage, $wfileimage, 0, 0, 0, 0, 72, 72, $wfilewidth, $wfileheight);
                imagejpeg ($wnewimage, $wfiletemp, 70);
                $wblob = base64_encode(file_get_contents($wfiletemp));

            }

            elseif ( $wfiletype == "image/png" or $wfiletype == "image/x-png" )
            {

                $wfiletype   = "jpg";
                $wfileimage  = imagecreatefrompng($wfiletemp);
                $wfilewidth  = imagesx($wfileimage);
                $wfileheight = imagesy($wfileimage);

                $wnewimage = imagecreatetruecolor(72,72);
                $white = imagecolorallocate($wnewimage, 255, 255, 255);
                imagefill($wnewimage, 0, 0, $white);
                imagecopyresampled($wnewimage, $wfileimage, 0, 0, 0, 0, 72, 72, $wfilewidth, $wfileheight);
                imagejpeg ($wnewimage, $wfiletemp, 70);
                $wblob = base64_encode(file_get_contents($wfiletemp));

            }

            else $wfilerror = "9";

        }

        if ( $wfilename == "" or $wfilerror != "0" )
        {

            $wquery = "insert into log (dateincl, idagent, ipuser, iduser, idchannel, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', '$widchannel', 2502)";
            $wres   = mysql_query($wquery,$wsystem_dbid);

            $wmsg = "Erro no upload. Selecione arquivo de imagem.<br>Tag do Canal: " . $wtag;

        }

        else
        {

        $wmd5icon = md5("$wblob");

            $wquery1 = "update channels set channelicon = '$wblob', md5icon = '$wmd5icon' where idchannel = '$widchannel'";
            $wres1   = mysql_query($wquery1,$wsystem_dbid);

            $wquery2 = "insert into log (dateincl, idagent, ipuser, iduser, idchannel, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', '$widchannel', 2503)";
            $wres2   = mysql_query($wquery2,$wsystem_dbid);

            header("Location: chns");
            exit;

        }

    }

    else
    {

        $wquery = "insert into log (dateincl, idagent, ipuser, iduser, idchannel, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', '$widchannel', 2501)";
        $wres   = mysql_query($wquery,$wsystem_dbid);

    }

    include("mod/head.php");

    echo    '<div style="background-color: #762A50; text-align: center; color: #fff; font-size: 20px; padding: 10px;">Canais (desenvolvedores)</div>';

    echo    '<div class="box">';

    echo        '<span class="msg1">Upload ícone</span>';

    if ( $wmsg == "" ) echo '<span class="msg1">Selecione imagem para upload.<br>Tag do Canal: ' . $wtag . '</span>';
    else               echo '<span class="msg1" style="color: red;">' . $wmsg . '</span>';

    echo        '<form method="post" action="." name="chnupld_form" id="chnupld_form" onsubmit="savePosition(this)" enctype="multipart/form-data">';

    echo            '<input type="hidden" name="oper" id="oper" value="chnupld_go">';
    echo            '<input type="hidden" name="posx" id="posx" value="0">';
    echo            '<input type="hidden" name="posy" id="posy" value="0">';

    echo            '<div>';
    echo                '<input class="input1" style="margin: 0 auto; width: 400px;" id="ufile[]" name="ufile[]" type="file">';
    echo            '</div>';

    echo            '<div>';
    echo                '<button class="button1" type="submit" name="chnupld_go">Enviar</button>';
    echo            '</div>';

    echo            '<div>';
    echo                '<a class="button4" href="chns">Cancelar</a>';
    echo            '</div>';

    echo        '</form>';

    echo    '</div>';

    include("mod/tail.php");

?>

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

    if ( $widuser == "" or !isset($_GET["chn"]) or !isset($_GET["code"]) )
    {
        header("Location: .");
        exit;
    }

    $widchannel = preg_replace('/[^a-z0-9]/',"",$_GET["chn"]);
    $wcode      = preg_replace('/[^a-z]/',"",$_GET["code"]);

    if ( $wcode != "php" and $wcode != "java" )
    {
        header("Location: .");
        exit;
    }

    $wquery1 = "select tag, pkpub, pkmod, pkpux
                  from channels, pkeys
                 where channels.idchannel = '$widchannel' and channels.idkey = pkeys.idkey and iduser = '$widuser' and regstatus != 'd'";

    $wres1    = mysql_query($wquery1,$wsystem_dbid);
    $wnumreg1 = mysql_num_rows($wres1);

    if ( $wnumreg1 == 0 )
    {
        header("Location: chns");
        exit;
    }

    $wvetor1 = mysql_fetch_row($wres1);
    $wtag    = $wvetor1[0];
    $wpkpub  = $wvetor1[1];
    $wpkmod  = $wvetor1[2];
    $wpkpux  = $wvetor1[3];

    $wquery = "insert into log (dateincl, idagent, ipuser, iduser, idchannel, operation, param) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', '$widchannel', 2701, '$wcode')";
    $wres   = mysql_query($wquery,$wsystem_dbid);

    $wfilename = "mod/fzup.$wcode";
    $wcurfile  = fopen($wfilename,"r");
    $wcontent  = fread($wcurfile,filesize($wfilename));
    fclose($wcurfile);

    $wcontent = str_replace("#idchannel#", "$widchannel", "$wcontent");
    $wcontent = str_replace("#server#",    "$wsystem_home",     "$wcontent");
    $wcontent = str_replace("#pubkey64#",  "$wpkpub",     "$wcontent");
    $wcontent = str_replace("#pubkeymod#", "$wpkmod",     "$wcontent");
    $wcontent = str_replace("#pubkeypux#", "$wpkpux",     "$wcontent");

    header('Content-Type: application/force-download; charset: UTF-8;');
    header('Content-Disposition: attachment; filename=fzup_' . $widchannel . '.' . $wcode);
    header('Content-Transfer-Encoding: binary');

    echo $wcontent;

?>

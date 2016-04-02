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

    if ( $widuser == "" or !isset($_GET["chn"]) )
    {
        header("Location: .");
        exit;
    }

    $widchannel = preg_replace('/[^a-z0-9]/',"",$_GET["chn"]);

    $wquery1 = "select regstatus
                  from channels
                 where idchannel = '$widchannel' and iduser = '$widuser' and regstatus != 'd'";

    $wres1    = mysql_query($wquery1,$wsystem_dbid);
    $wnumreg1 = mysql_num_rows($wres1);

    if ( $wnumreg1 == 0 )
    {
        header("Location: chns");
        exit;
    }

    $wvetor1    = mysql_fetch_row($wres1);
    $wregstatus = $wvetor1[0];

    if ( $wregstatus == "s" ) $wquery2 = "update channels set regstatus = 'a' where idchannel = '$widchannel'";
    else                      $wquery2 = "update channels set regstatus = 's' where idchannel = '$widchannel'";

    $wres2   = mysql_query($wquery2,$wsystem_dbid);

    if ( $wregstatus == "s" ) $wopcode = 2301; 
    else                      $wopcode = 2302;

    $wquery = "insert into log (dateincl, idagent, ipuser, iduser, idchannel, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', '$widchannel', $wopcode)";
    $wres   = mysql_query($wquery,$wsystem_dbid);

    header("Location: chns");

?>

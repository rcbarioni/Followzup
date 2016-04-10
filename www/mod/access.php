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

    $wipuser   = $_SERVER["REMOTE_ADDR"];
    $wipserver = $_SERVER["SERVER_ADDR"];

    $wsystem_home   = "www-followzup";
    $wsystem_dbhost = "localhost";
    $wsystem_dbname = "dbfollowzup";
    $wsystem_dbuser = "admfollowzup";
    $wsystem_dbpass = "passfollowzup";

#   mysql connection

    if ( !($wsystem_dbid = @mysql_connect($wsystem_dbhost, $wsystem_dbuser, $wsystem_dbpass)) ) 
    {
        echo "iderror";
        exit;
    }

    elseif ( !($wsystem_dbcon = @mysql_select_db($wsystem_dbname,$wsystem_dbid)) )
    {
        echo "dberror";
        exit;
    }

#   read control

    $wquery1  = "select chnstatus, intstatus, seqzup, seqzop from control where idcontrol = 0";
    $wres1    = mysql_query($wquery1,$wsystem_dbid);
    $wnumreg1 = intval(mysql_num_rows($wres1));

    if ( $wnumreg1 == 0 )
    {
        include ("dbload.php");
        $wquery1  = "select chnstatus, intstatus, seqzup, seqzop from control where idcontrol = 0";
        $wres1 = mysql_query($wquery1,$wsystem_dbid);
    }

    $wvetor1 = mysql_fetch_row($wres1);

    $wsystem_chnstatus = $wvetor1[0];
    $wsystem_intstatus = $wvetor1[1];
    $wsystem_seqzup    = $wvetor1[2];
    $wsystem_seqzop    = $wvetor1[3];

?>

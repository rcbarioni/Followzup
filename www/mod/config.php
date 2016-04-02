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

    if ( !isset($_SESSION["iduser"]) )
    {
        $_SESSION["iduser"]  = "";
        $_SESSION["admin"]   = "";
        $_SESSION["name"]    = "";
        $_SESSION["email"]   = "";
        $_SESSION["passmd5"] = "";
        $_SESSION["idagent"] = "0";
        $_SESSION["maxchns"] = "0";
        $_SESSION["agent"]   = "";
        $_SESSION["help"]    = "in";
    }

    $widuser  = $_SESSION["iduser"];
    $wadmin   = $_SESSION["admin"];
    $wname    = $_SESSION["name"];
    $wemail   = $_SESSION["email"];
    $wpassmd5 = $_SESSION["passmd5"];
    $widagent = $_SESSION["idagent"];
    $wmaxchns = $_SESSION["maxchns"];
    $wagent   = $_SESSION["agent"];
    $whelp    = $_SESSION["help"];

    $wagora    = gmdate("Y-m-d H:i:s");
    $whoje     = gmdate("Y-m-d");

    if ( isset($_SERVER["HTTP_USER_AGENT"]) ) $wnewagent = cleanText(substr($_SERVER["HTTP_USER_AGENT"],0,300));
    else                                      $wnewagent = "";

    if ( $wagent != $wnewagent )
    {

        $_SESSION["idagent"] = "0";
        $_SESSION["agent"]   = "";

        $widagent = "0";
        $wagent   = "";

        if ( $wnewagent != "" )
        {

            $wquery1  = "select idagent from agents where agent = '$wnewagent'";
            $wres1    = mysql_query($wquery1,$wsystem_dbid);
            $wnumreg1 = intval(mysql_num_rows($wres1));

            if ( $wnumreg1 > 0 )
            {
                $wvetor1  = mysql_fetch_row($wres1);
                $widagent = $wvetor1[0];
            }

            else
            {

                $wquery2  = "insert into agents (agent, dateincl) values ('$wnewagent', '$wagora')";
                $wres2    = mysql_query($wquery2,$wsystem_dbid);
                $widagent = mysql_insert_id($wsystem_dbid);

                $wquery = "insert into log (dateincl, idagent, ipuser, operation) values (utc_timestamp(), '$widagent', '$wipuser', 1003)";
                $wres   = mysql_query($wquery,$wsystem_dbid);

            }

            $wagent   = $wnewagent;

            $_SESSION["idagent"] = $widagent;
            $_SESSION["agent"]   = $wagent;

        }

    }

    if ( $widuser != "" )
    {

        $wquery  = "select name, maxchannels
                      from users 
                     where iduser = '$widuser' and regstatus = 'a' and pass = '$wpassmd5'";

        $wres    = mysql_query($wquery,$wsystem_dbid);
        $wnumreg = intval(mysql_num_rows($wres));

        if ( $wnumreg == 0 )
        {

            session_destroy();

            $wquery = "insert into log (dateincl, idagent, ipuser, iduser, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', 1205)";
            $wres   = mysql_query($wquery,$wsystem_dbid);

            header("Location: .");
            exit;

        }

        $wvetor = mysql_fetch_row($wres);

        $_SESSION["name"]    = $wvetor[0];
        $_SESSION["maxchns"] = $wvetor[1];

        $wname    = $_SESSION["name"];
        $wmaxchns = $_SESSION["maxchns"];

    }

    $wposx = "0";
    $wposy = "0";

    if     ( isset($_POST["posx"]) ) $wposx = preg_replace("/[^0-9]/","",$_POST["posx"]);
    elseif ( isset($_GET["posx"]) )  $wposx = preg_replace("/[^0-9]/","",$_GET["posx"]);

    if     ( isset($_POST["posy"]) ) $wposy = preg_replace("/[^0-9]/","",$_POST["posy"]);
    elseif ( isset($_GET["posy"]) )  $wposy = preg_replace("/[^0-9]/","",$_GET["posy"]);

?>

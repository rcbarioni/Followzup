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

    $wid     = "";
    $wkey1   = "";
    $wframe1 = "";

    if ( isset($_POST["id"]) )    $wid     = preg_replace("/[^a-z0-9]/","",strtolower($_POST["id"]));
    if ( isset($_POST["key"]) )   $wkey1   = $_POST["key"];
    if ( isset($_POST["frame"]) ) $wframe1 = $_POST["frame"];

    $wretcode  = "0";
    $wretframe = "";
    $wresponse = "";
    $wdecrypt  = "";

# ==============================================================================================================================================================
# OUT OF SERVICE
# ==============================================================================================================================================================

    if ( $wsystem_chnstatus != "a" )
    {

        header("Content-Type: application/xml; charset=utf-8;");
        echo "<" . '?xml version="1.0" encoding="utf-8"?' . ">";
        echo "<followzup>";
        echo    "<retcode>6999</retcode>";
        echo    "<retframe></retframe>";
        echo "</followzup>";

        exit;

    }

# ==============================================================================================================================================================
# INVALID IDCHANNEL
# ==============================================================================================================================================================

    if ( strlen($wid) != 12 )
    {
        $wretcode = "6104";
        $wquery   = "insert into log (dateincl, ipuser, idagent, operation) values (utc_timestamp(), '$wipuser', '$widagent', 6104)";
        $wres     = mysql_query($wquery,$wsystem_dbid);
    }

    elseif ( substr($wid,0,1) != "c" )
    {
        $wretcode = "6104";
        $wquery   = "insert into log (dateincl, ipuser, idagent, operation, param) values (utc_timestamp(), '$wipuser', '$widagent', 6104, '$wid')";
        $wres     = mysql_query($wquery,$wsystem_dbid);
    }

    else
    {

        $wquery  = "select pkpri, channelseq, users.iduser, channels.idkey
                      from channels, users, pkeys 
                     where channels.idchannel = '$wid' and channels.iduser = users.iduser and channels.idkey = pkeys.idkey and
                           users.regstatus = 'a' and channels.regstatus != 'd'";

        $wres    = mysql_query($wquery,$wsystem_dbid);
        $wnumreg = mysql_num_rows($wres);

        if ( $wnumreg == 0 )
        {
            $wretcode = "6104";
            $wquery = "insert into log (dateincl, ipuser, idagent, operation, param) values (utc_timestamp(), '$wipuser', '$widagent', 6104, '$wid':status)";
            $wres   = mysql_query($wquery,$wsystem_dbid);
        }

        else
        {

            $wvetor      = mysql_fetch_row($wres);
            $wpri64      = $wvetor[0];
            $wchannelseq = $wvetor[1];
            $widuserchn  = $wvetor[2];
            $widkey      = $wvetor[3];

            $wpri    = base64_decode($wpri64);
            $wkey2   = base64_decode($wkey1);
            $wframe2 = base64_decode($wframe1);

            openssl_private_decrypt($wkey2,$wkey3,$wpri);
            $wdecrypt = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $wkey3, $wframe2, MCRYPT_MODE_CBC, str_repeat(chr(0),16));

# ==============================================================================================================================================================
# INVALID KEY/FRAME
# ==============================================================================================================================================================

            if ( $wdecrypt == "" or $wkey3 == "" )
            {
                $wretcode = "6102";
                $wquery   = "insert into log (dateincl, ipuser, idagent, iduser, idchannel, operation) values (utc_timestamp(), '$wipuser', '$widagent', '$widuserchn', '$wid', 6102)";
                $wres     = mysql_query($wquery,$wsystem_dbid);
            }

            else
            {

                $wxml = @simplexml_load_string($wdecrypt);
                $wcom = preg_replace("/[^a-z]/","",strtolower($wxml->com));
                $wseq = (int)$wxml->seq;

# ==============================================================================================================================================================
# INVALID COMMAND
# ==============================================================================================================================================================

                if ( "$wcom" != "smsg" and "$wcom" != "chck" ) 
                {
                    $wretcode = "6103";
                    $wquery = "insert into log (dateincl, ipuser, idagent, iduser, idchannel, channelseq, operation, param) values (utc_timestamp(), '$wipuser', '$widagent', '$widuserchn', '$wid', $wseq, 6103, '$wcom')";
                    $wres   = mysql_query($wquery,$wsystem_dbid);
                }

# ==============================================================================================================================================================
# INVALID SEQUENCE
# ==============================================================================================================================================================

                elseif ( $wseq != $wchannelseq + 1 )
                {

                    $wretcode = "6101";
                    $wquery   = "insert into log (dateincl, ipuser, idagent, iduser, idchannel, operation, param) values (utc_timestamp(), '$wipuser', '$widagent', '$widuserchn', '$wid', 6101, '$wcom:$wseq')";
                    $wres     = mysql_query($wquery,$wsystem_dbid);

                    $wresponse = "<" . '?xml version="1.0" encoding="utf-8"?' . ">" . "<followzup><seq>$wchannelseq</seq></followzup>";

                    $wretframe1 = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $wkey3, $wresponse, MCRYPT_MODE_CBC, str_repeat(chr(0),16));
                    $wretframe  = base64_encode($wretframe1);

                }

                else
                {

                    $wquery = "update channels set channelseq = $wseq where idchannel = '$wid'";
                    $wres   = mysql_query($wquery,$wsystem_dbid);

                    $wresponse = "<" . '?xml version="1.0" encoding="utf-8"?' . "><followzup>";

/* ==============================================================================================================================================================

   CHCK - CHECK USER

      Param:  FZUP_COMMAND  <com> = smsg
              FZUP_LASTSEQ  <seq> = sequence
              FZUP_USER     <usr> = UserID | e-mail
              FZUP_SUBSCODE <sub> = 99999999

      Return: <uid>User-ID</uid>
              <reg>yes|no</reg>    (registered?)

============================================================================================================================================================== */

                    if ( $wcom == "chck" )
                    {

                        $wsub = (int)$wxml->sub;
                        $wusr = cleanText(strtolower($wxml->usr));

                        $wflag = validaEmail($wusr);

                        if ( substr($wusr,0,1) != "z" and !$wflag )
                        {
                            $wretcode = "6106";
                            $wquery = "insert into log (dateincl, ipuser, idagent, iduser, idchannel, channelseq, operation, param) values (utc_timestamp(), '$wipuser', '$widagent', '$widuserchn', '$wid', $wseq, 6106, '$wusr')";
                            $wres   = mysql_query($wquery,$wsystem_dbid);
                        }

                        else
                        {

                            if ( $wflag )

                                $wquery = "select subscriptions.iduser, subscriptions.subscode, subscriptions.datetry, users.email
                                             from subscriptions, users
                                            where subscriptions.idchannel = '$wid' and subscriptions.regstatus = 'a' and
                                                  subscriptions.iduser = users.iduser and 
                                              users.email = '$wusr' and users.regstatus = 'a'";

                            else

                                $wquery = "select subscriptions.iduser, subscriptions.subscode, subscriptions.datetry, users.email
                                             from subscriptions, users
                                            where subscriptions.idchannel = '$wid' and subscriptions.regstatus = 'a' and
                                              subscriptions.iduser = users.iduser and 
                                                  users.iduser = '$wusr' and users.regstatus = 'a'";

                            $wres = mysql_query($wquery,$wsystem_dbid);
                            $wnumreg = mysql_num_rows($wres);
    
                            if ( $wnumreg == 0 ) 
                            {
                                $wretcode = "6203";
                                $wquery = "insert into log (dateincl, ipuser, idagent, iduser, idchannel, channelseq, operation, param) values (utc_timestamp(), '$wipuser', '$widagent', '$widuserchn', '$wid', $wseq, 6203, '$wusr')";
                                $wres   = mysql_query($wquery,$wsystem_dbid);
                            }

                            else
                            {

                                $wvetor   = mysql_fetch_row($wres);
                                $widuser  = $wvetor[0];
                                $wsign    = $wvetor[1];
                                $wdatetry = $wvetor[2];
                                $wemail   = $wvetor[3];

                                if ( $wagora < $wdatetry )
                                {
                                    $wretcode = "6204";
                                    $wquery = "insert into log (dateincl, ipuser, idagent, iduser, idchannel, channelseq, operation, param) values (utc_timestamp(), '$wipuser', '$widagent', '$widuserchn', '$wid', $wseq, 6204, '$wusr:$wsub (datetry)')";
                                    $wres   = mysql_query($wquery,$wsystem_dbid);
                                }

                                elseif ( $wsub == $wsign ) 
                                {

                                    if ( $widuser != $wemail ) $wresponse = $wresponse . "<uid>$widuser</uid><reg>yes</reg>";
                                    else                       $wresponse = $wresponse . "<uid>$widuser</uid><reg>no</reg>";

                                    $wquery = "insert into log (dateincl, ipuser, idagent, iduser, idchannel, channelseq, operation, param) values (utc_timestamp(), '$wipuser', '$widagent', '$widuserchn', '$wid', $wseq, 6202, '$wusr:$wsub')";
                                    $wres   = mysql_query($wquery,$wsystem_dbid);

                                }

                                else
                                {

                                    $wretcode = "6204";
                                    $wquery = "insert into log (dateincl, ipuser, idagent, iduser, idchannel, channelseq, operation, param) values (utc_timestamp(), '$wipuser', '$widagent', '$widuserchn', '$wid', $wseq, 6204, '$wusr:$wsub')";
                                    $wres   = mysql_query($wquery,$wsystem_dbid);

                                    $wdatetry = gmdate("Y-m-d H:i:s", strtotime("+2 seconds"));
                                    $wquery = "update subscriptions set datetry = '$wdatetry' where idchannel = '$wid' and iduser = '$widuser'";
                                    $wres   = mysql_query($wquery,$wsystem_dbid);
                
                                }

                            }

                        }

                    }

/* ==============================================================================================================================================================

   SMSG - SEND MESSAGE ALL

      Param:  FZUP_COMMAND <com> = smsg
              FZUP_LASTSEQ <seq> = sequence
              FZUP_USER    <usr> = all
              FZUP_HOURS   <hrs> = 99
              FZUP_MSGTEXT <msg> = Message text
              FZUP_MSGURL  <url> = Message URL

      Return: <snt>total-sent</snt>

============================================================================================================================================================== */

                    elseif ( $wcom == "smsg" and $wxml->usr == "all" )
                    {

                        $whrs = (int)$wxml->hrs;
                        $wmsg = substr(cleanText($wxml->msg),0,200);
                        $wurl = substr(cleanText($wxml->url),0,200);

                        if ( $whrs < 1 or $whrs > 960 ) $whrs = 24;

                        $wdateterm = gmdate("Y-m-d H:i:s", strtotime("+$whrs hours"));

                        if ( $wmsg == "" )
                        {
                            $wretcode = "6108";
                            $wquery = "insert into log (dateincl, ipuser, idagent, iduser, idchannel, channelseq, operation) values (utc_timestamp(), '$wipuser', '$widagent', '$widuserchn', '$wid', $wseq, 6108)";
                            $wres   = mysql_query($wquery,$wsystem_dbid);
                        }

                        else
                        {

                            $wmsg = base64_encode($wmsg);
                            $wurl = base64_encode($wurl);

                            $wquery1 = "select subscriptions.iduser
                                          from subscriptions, users
                                          where subscriptions.idchannel = '$wid' and subscriptions.iduser = users.iduser and
                                                subscriptions.regstatus = 'a' and users.regstatus = 'a'
                                          order by subscriptions.iduser";

                            $wres1 = mysql_query($wquery1,$wsystem_dbid);
                            $wnumreg1 = mysql_num_rows($wres1);

                            if ( $wnumreg1 > 0 )
                            {

                                $wmediamd5 = md5("$wmsg;$wurl");
                                $wquery2   = "insert into medias (idchannel, mediamd5, mediatext, mediaurl) values ('$wid', '$wmediamd5', '$wmsg', '$wurl')";
                                $wres2     = mysql_query($wquery2,$wsystem_dbid);

                                while ( $wvetor1 = mysql_fetch_row($wres1) )
                                {

                                    $widuser  = $wvetor1[0];

                                    $wquery = "insert into messages (idchannel, iduser, mediamd5, dateincl, dateterm, hours, regstatus)
                                                             values ('$wid', '$widuser', '$wmediamd5', '$wagora', '$wdateterm', '$whrs', 'p')";

                                    $wres = mysql_query($wquery,$wsystem_dbid);

                                }

                            }

                            $wquery = "insert into log (dateincl, ipuser, idagent, iduser, idchannel, channelseq, operation, param) 
                                                values (utc_timestamp(), '$wipuser', '$widagent', '$widuserchn', '$wid', $wseq, 6221, 'all:$wnumreg1')";

                            $wres   = mysql_query($wquery,$wsystem_dbid);

                            $wresponse = $wresponse . "<snt>$wnumreg1</snt>";

                        }

                    }

/* ==============================================================================================================================================================

   SMSG - SEND MESSAGE LIST

      Param:  FZUP_COMMAND <com> = smsg
              FZUP_LASTSEQ <seq> = sequence
              FZUP_USER    <usr> = UserID,e-mail
              FZUP_HOURS   <hrs> = 99
              FZUP_MSGTEXT <msg> = Message text
              FZUP_MSGURL  <url> = Message URL

      Return: <snt>total-sent</snt>
              <nsb>total-no-subs</nsb>
              <inv>total-invalid</inv>

============================================================================================================================================================== */

                    elseif ( $wcom == "smsg" )
                    {

                        $wusr = cleanText(strtolower($wxml->usr));
                        $whrs = (int)$wxml->hrs;
                        $wmsg = substr(cleanText($wxml->msg),0,200);
                        $wurl = substr(cleanText($wxml->url),0,200);

                        $wlista = explode(",",$wusr);

                        if ( $whrs < 1 or $whrs > 960 ) $whrs = 960;

                        $wdateterm = gmdate("Y-m-d H:i:s", strtotime("+$whrs hours"));

                        if ( $wmsg == "" )
                        {
                            $wretcode = "6108";
                            $wquery = "insert into log (dateincl, ipuser, idagent, iduser, idchannel, channelseq, operation) values (utc_timestamp(), '$wipuser', '$widagent', '$widuserchn', '$wid', $wseq, 6108)";
                            $wres   = mysql_query($wquery,$wsystem_dbid);
                        }

                        elseif ( count($wlista) > 200 )
                        {
                            $wretcode = "6109";
                            $wquery = "insert into log (dateincl, ipuser, idagent, iduser, idchannel, channelseq, operation) values (utc_timestamp(), '$wipuser', '$widagent', '$widuserchn', '$wid', $wseq, 6109)";
                            $wres   = mysql_query($wquery,$wsystem_dbid);
                        }

                        else
                        {

                            $wmsg = base64_encode($wmsg);
                            $wurl = base64_encode($wurl);

                            $wnewmsg  = 0;
                            $wnosubs  = 0;
                            $winvalid = 0;

                            foreach ( $wlista as $widuser )
                            {

                                $wflag = validaEmail($widuser);

                                if ( $widuser == "" ) $winvalid = $winvalid + 1;

                                elseif ( substr($widuser,0,1) != "z" and !$wflag ) $winvalid = $winvalid + 1;
        
                                else
                                {

                                    if ( $wflag )

                                        $wquery = "select subscriptions.iduser
                                                 from subscriptions, users
                                                   where subscriptions.idchannel = '$wid' and subscriptions.regstatus = 'a' and
                                                      subscriptions.iduser = users.iduser and 
                                                             users.email = '$widuser' and users.regstatus = 'a'";

                                    else

                                        $wquery = "select subscriptions.iduser
                                                 from subscriptions, users
                                                   where subscriptions.idchannel = '$wid' and subscriptions.regstatus = 'a' and
                                                      subscriptions.iduser = users.iduser and 
                                                             users.iduser = '$widuser' and users.regstatus = 'a'";

                                    $wres = mysql_query($wquery,$wsystem_dbid);
                                    $wnumreg = mysql_num_rows($wres);
    
                                    if ( $wnumreg == 0 ) $wnosubs = $wnosubs + 1;

                                    else
                                    {

                                        $wvetor  = mysql_fetch_row($wres);
                                        $widuser = $wvetor[0];

                                        $wnewmsg = $wnewmsg + 1;

                                        if ( $wnewmsg == 1 ) 
                                        {

                                            $wmediamd5 = md5("$wmsg;$wurl");
                                            $wquery2   = "insert into medias (idchannel, mediamd5, mediatext, mediaurl) values ('$wid', '$wmediamd5', '$wmsg', '$wurl')";
                                            $wres2     = mysql_query($wquery2,$wsystem_dbid);

                                        }

                                        $wquery = "insert into messages (idchannel, iduser, mediamd5, dateincl, dateterm, hours, regstatus)
                                                                 values ('$wid', '$widuser', '$wmediamd5', '$wagora', '$wdateterm', '$whrs', 'p')";

                                        $wres   = mysql_query($wquery,$wsystem_dbid);

                                    }

                                }

                            }

                            $wquery = "insert into log (dateincl, ipuser, idagent, iduser, idchannel, channelseq, operation, param) 
                                                values (utc_timestamp(), '$wipuser', '$widagent', '$widuserchn', '$wid', $wseq, 6222, 'new=$wnewmsg nosubs=$wnosubs invalid=$winvalid')";

                            $wres   = mysql_query($wquery,$wsystem_dbid);

                            $wresponse = $wresponse . "<snt>$wnewmsg</snt>";
                            $wresponse = $wresponse . "<nsb>$wnosubs</nsb>";
                            $wresponse = $wresponse . "<inv>$winvalid</inv>";

                        }

                    }

# ==============================================================================================================================================================
# INVALID COMMAND
# ==============================================================================================================================================================

                    else
                    {
                        $wretcode = "6999";
                        $wquery = "insert into log (dateincl, ipuser, idagent, iduser, idchannel, channelseq, operation, param) values (utc_timestamp(), '$wipuser', '$widagent', '$widuserchn', '$wid', $wseq, 6999, '$wcom')";
                        $wres   = mysql_query($wquery,$wsystem_dbid);
                    }

                    $wresponse = $wresponse . "</followzup>";
                    if ( $wretcode != "0" and $wretcode != "6101" ) $wresponse = "";

                    $wretframe1 = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $wkey3, $wresponse, MCRYPT_MODE_CBC, str_repeat(chr(0),16));
                    $wretframe  = base64_encode($wretframe1);

                }

            }

        }

    }

    header("Content-Type: application/xml; charset=utf-8;");
    echo "<" . '?xml version="1.0" encoding="utf-8"?' . ">";
    echo "<followzup>";
    echo     "<retcode>$wretcode</retcode>";
    echo     "<retframe>$wretframe</retframe>";
    echo "</followzup>";

?>

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
    $wretmd5   = "";
    $wreticons = "";
    $wresponse = "";
    $wdecrypt  = "";

# ==============================================================================================================================================================
# OUT OF SERVICE
# ==============================================================================================================================================================

    if ( $wsystem_intstatus != 0 )
    {

        header("Content-Type: application/xml; charset=utf-8;");
        echo "<" . '?xml version="1.0" encoding="utf-8"?' . ">";
        echo "<followzup>";
        echo    "<retcode>7999</retcode>";
        echo    "<retframe></retframe>";
        echo    "<retmd5></retmd5>";
        echo "</followzup>";

        exit;

    }

# ==============================================================================================================================================================
# INVALID ID
# ==============================================================================================================================================================

    $wprefix = substr($wid,0,1);

    if ( strlen($wid) != 12 or ( $wprefix != "d" and $wprefix != "i" ) )
    {
        $wretcode = "7104";
        $wquery   = "insert into log (dateincl, ipuser, idagent, operation, param) values (utc_timestamp(), '$wipuser', '$widagent', 7104, 'ID: $wid')";
        $wres     = mysql_query($wquery,$wsystem_dbid);
    }

# ==============================================================================================================================================================
#
#   INSTALL DEVICE
#
#      Param:  FZUP_STAMP <stp> = stamp
#              FZUP_EMAIL <eml> = e-mail
#              FZUP_PASS  <pwd> = password
#
#      Return: <uid>User-ID</uid>
#              <did>Device-ID</did>
#              <pub>pub-key</pub>
#              <mod>mod-key</mod>
#              <pux>pux-key</pux>
#
# ==============================================================================================================================================================

    elseif ( $wprefix == "i" )
    {

        $wcom = "inst";

        $wquery = "select stamp, regstatus
                     from interfaces
                    where idinterface = '$wid' and regstatus != 'd'";

        $wres    = mysql_query($wquery,$wsystem_dbid);
        $wnumreg = mysql_num_rows($wres);

        if ( $wnumreg == 0 )
        {
            $wretcode = "7104";
            $wquery = "insert into log (dateincl, ipuser, operation, param) values (utc_timestamp(), '$wipuser', 7104, 'Interface: $wid')";
            $wres   = mysql_query($wquery,$wsystem_dbid);
        }

        else
        {

            $wvetor     = mysql_fetch_row($wres);
            $istamp     = $wvetor[0];
            $iregstatus = $wvetor[1];

            $wquery  = "select pkpri from pkeys where idkey = 'keydev'";
            $wres    = mysql_query($wquery,$wsystem_dbid);
            $wnumreg = mysql_num_rows($wres);

            if ( $wnumreg == 0 ) $wpri = "";

            else
            {
                $wvetor = mysql_fetch_row($wres);
                $wpri64 = $wvetor[0];
                $wpri   = base64_decode($wpri64);
            }

            $wkey2   = base64_decode($wkey1);
            $wframe2 = base64_decode($wframe1);
            $wseq    = 0;

            openssl_private_decrypt($wkey2,$wkey3,$wpri);
            $wdecrypt = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $wkey3, $wframe2, MCRYPT_MODE_CBC, str_repeat(chr(0),16));

            if ( $wdecrypt == "" or $wkey3 == "" )
            {
                $wretcode = "7102";
                $wquery = "insert into log (dateincl, ipuser, idinterface, operation) values (utc_timestamp(), '$wipuser', '$wid', 7102)"; 
                $wres   = mysql_query($wquery,$wsystem_dbid);
            }

            else
            {

                $wxml = @simplexml_load_string("$wdecrypt");

                $wstamp   = preg_replace("/[^a-z0-9]/","","$wxml->stp");
                $wemail   = cleanText(strtolower("$wxml->eml"));
                $wtrypass = cleanText("$wxml->pwd");

                if ( $wstamp != $istamp )
                {
                    $wretcode = "7105";
                    $wquery = "insert into log (dateincl, ipuser, idinterface, operation) values (utc_timestamp(), '$wipuser', '$wid', 7105)";
                    $wres   = mysql_query($wquery,$wsystem_dbid);
                }

                elseif ( $wemail == "anonymous" and $wtrypass == "anonymous" )
                {

                    $widuser  = idgenerator("usr");

                    $wpassmd5 = md5(rand());

                    $wquery2  = "insert into users (iduser, email, pass, dateincl, name, regstatus)
                                            values ('$widuser', '$widuser', '$wpassmd5', '$wagora', 'Anonymous User', 'a')";

                    while ( !mysql_query($wquery2,$wsystem_dbid) )
                    {

                        $wquery9 = "insert into log (dateincl, idagent, ipuser, operation, param) values (utc_timestamp(), '$widagent', '$wipuser', 1001, '$widuser')";
                        $wres9   = mysql_query($wquery9,$wsystem_dbid);

                        $widuser = idgenerator("usr");

                        $wquery2  = "insert into users (iduser, email, pass, dateincl, name, regstatus)
                                                values ('$widuser', '$widuser', '$wpassmd5', '$wagora', 'Anonymous User', 'a')";
                
                    }

                    $wdev    = keygenerator(2048);
                    $wdevpri = $wdev["pri"];
                    $wdevpub = $wdev["pub"];
                    $wdevmod = $wdev["mod"];
                    $wdevpux = $wdev["pux"];
                    $wdevprx = $wdev["prx"];
                    $wdevpr1 = $wdev["pr1"];
                    $wdevpr2 = $wdev["pr2"];
                    $wdevdmp = $wdev["dmp"];
                    $wdevdmq = $wdev["dmq"];
                    $wdeviqm = $wdev["iqm"];

                    $widdevice  = idgenerator("dev");
                    $wquery2    = "insert into devices (iddevice, iduser, devicetag, dateincl, lastact, idinterface, regstatus, pkpub, pkpri, pkmod, pkpux, pkprx, pkpr1, pkpr2, pkdmp, pkdmq, pkiqm) 
                                                values ('$widdevice', '$widuser', 'Anonymous', '$wagora', '$wagora', '$wid', 'a', 
                                                        '$wdevpub', '$wdevpri', '$wdevmod', '$wdevpux', '$wdevprx', '$wdevpr1', '$wdevpr2', '$wdevdmp', '$wdevdmq', '$wdeviqm')";

                    while ( !mysql_query($wquery2,$wsystem_dbid) )
                    {

                        $wquery9 = "insert into log (dateincl, ipuser, iduser, idinterface, operation, param) values (utc_timestamp(), '$wipuser', '$widuser', '$wid', 1001, '$widdevice')";
                        $wres9   = mysql_query($wquery9,$wsystem_dbid);

                        $widdevice  = idgenerator("dev");
                        $wquery2    = "insert into devices (iddevice, iduser, devicetag, dateincl, lastact, idinterface, regstatus, pkpub, pkpri, pkmod, pkpux, pkprx, pkpr1, pkpr2, pkdmp, pkdmq, pkiqm) 
                                                    values ('$widdevice', '$widuser', 'Anonymous', '$wagora', '$wagora', '$wid', 'a', 
                                                            '$wdevpub', '$wdevpri', '$wdevmod', '$wdevpux', '$wdevprx', '$wdevpr1', '$wdevpr2', '$wdevdmp', '$wdevdmq', '$wdeviqm')";
                    }

                    $wquery = "insert into log (dateincl, ipuser, iduser, iddevice, idinterface, operation) values (utc_timestamp(), '$wipuser', '$widuser', '$widdevice', '$wid', 7112)";
                    $wres   = mysql_query($wquery,$wsystem_dbid);

                    $wresponse = "<" . '?xml version="1.0" encoding="utf-8"?' . "><followzup>";
                    $wresponse = $wresponse . "<uid>$widuser</uid>";
                    $wresponse = $wresponse . "<did>$widdevice</did>";
                    $wresponse = $wresponse . "<pub>$wdevpub</pub>";
                    $wresponse = $wresponse . "<mod>$wdevmod</mod>";
                    $wresponse = $wresponse . "<pux>$wdevpux</pux>";
                    $wresponse = $wresponse . "</followzup>";

                    $wretframe1 = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $wkey3, $wresponse, MCRYPT_MODE_CBC, str_repeat(chr(0),16));
                    $wretframe  = base64_encode($wretframe1);

                }

                elseif ( !validaEmail($wemail) or $wemail == "" )
                {
                    $wretcode = "7107";
                    $wquery = "insert into log (dateincl, ipuser, idinterface, operation, param) values (utc_timestamp(), '$wipuser', '$wid', 7107, '$wemail')";
                    $wres   = mysql_query($wquery,$wsystem_dbid);
                }

                else
                {

                    $wquery = "select iduser, pass, datetry, name
                                 from users
                                where email = '$wemail' and regstatus = 'a'";
                                
                    $wres    = mysql_query($wquery,$wsystem_dbid);
                    $wnumreg = mysql_num_rows($wres);

                    if ( $wnumreg == 0 )
                    {
                        $wretcode = "7107";
                        $wquery = "insert into log (dateincl, ipuser, idinterface, operation, param) values (utc_timestamp(), '$wipuser', '$wid', 7107, '$wemail')";
                        $wres   = mysql_query($wquery,$wsystem_dbid);
                    }

                    else
                    {
    
                        $wvetor   = mysql_fetch_row($wres);
                        $widuser  = $wvetor[0];
                        $wpass    = $wvetor[1];
                        $wdatetry = $wvetor[2];
                        $wname    = $wvetor[3];

                        if ( $wagora < $wdatetry )
                        {

                            $wretcode = "7108";
                            $wquery = "insert into log (dateincl, ipuser, iduser, idinterface, operation, param) values (utc_timestamp(), '$wipuser', '$widuser', '$wid', 7108, '$wtrypass (datetry)')";
                            $wres   = mysql_query($wquery,$wsystem_dbid);

                        }
                    
                        elseif ( $wpass == "" or $wpass != md5($wtrypass . ';' . strrev($wtrypass)) )
                        {

                            $wretcode = "7108";
                            $wquery = "insert into log (dateincl, ipuser, iduser, idinterface, operation, param) values (utc_timestamp(), '$wipuser', '$widuser', '$wid', 7108, '$wtrypass')";
                            $wres   = mysql_query($wquery,$wsystem_dbid);

                            $wdatetry = gmdate("Y-m-d H:i:s", strtotime("+5 seconds"));

                            $wquery = "update users set datetry = '$wdatetry' where iduser = '$widuser'";
                            $wres   = mysql_query($wquery,$wsystem_dbid);

                        }
                    
                        else
                        {

                            $wnext = 0;

                            do
                            {

                                $wnext   = $wnext + 1;
                                $wtag    = "Device-" . $wnext;
                                $wquery  = "select iddevice from devices where iduser = '$widuser' and devicetag = '$wtag'";
                                $wres    = mysql_query($wquery,$wsystem_dbid);
                                $wnumreg = mysql_num_rows($wres);

                            } while ( $wnumreg > 0 );

                            $wdev    = keygenerator(2048);
                            $wdevpri = $wdev["pri"];
                            $wdevpub = $wdev["pub"];
                            $wdevmod = $wdev["mod"];
                            $wdevpux = $wdev["pux"];
                            $wdevprx = $wdev["prx"];
                            $wdevpr1 = $wdev["pr1"];
                            $wdevpr2 = $wdev["pr2"];
                            $wdevdmp = $wdev["dmp"];
                            $wdevdmq = $wdev["dmq"];
                            $wdeviqm = $wdev["iqm"];

                            $widdevice = idgenerator("dev");
                            $wquery2   = "insert into devices (iddevice, iduser, devicetag, dateincl, lastact, idinterface, regstatus, pkpub, pkpri, pkmod, pkpux, pkprx, pkpr1, pkpr2, pkdmp, pkdmq, pkiqm) 
                                                       values ('$widdevice', '$widuser', '$wtag', '$wagora', '$wagora', '$wid', 'a', 
                                                               '$wdevpub', '$wdevpri', '$wdevmod', '$wdevpux', '$wdevprx', '$wdevpr1', '$wdevpr2', '$wdevdmp', '$wdevdmq', '$wdeviqm')";

                            while ( !mysql_query($wquery2,$wsystem_dbid) )
                            {

                                $wquery9 = "insert into log (dateincl, ipuser, iduser, idinterface, operation, param) values (utc_timestamp(), '$wipuser', '$widuser', '$wid', 1001, '$widdevice $wtag')";
                                $wres9   = mysql_query($wquery9,$wsystem_dbid);

                                $wnext = 0;

                                do
                                {

                                    $wnext   = $wnext + 1;
                                    $wtag    = "Device-" . $wnext;
                                    $wquery  = "select iddevice from devices where iduser = '$widuser' and devicetag = '$wtag'";
                                    $wres    = mysql_query($wquery,$wsystem_dbid);
                                    $wnumreg = mysql_num_rows($wres);

                                } while ( $wnumreg > 0 );

                                $widdevice = idgenerator("dev");
                                $wquery2   = "insert into devices (iddevice, iduser, devicetag, dateincl, lastact, idinterface, regstatus, pkpub, pkpri, pkmod, pkpux, pkprx, pkpr1, pkpr2, pkdmp, pkdmq, pkiqm) 
                                                           values ('$widdevice', '$widuser', '$wtag', '$wagora', '$wagora', '$wid', 'a', 
                                                                   '$wdevpub', '$wdevpri', '$wdevmod', '$wdevpux', '$wdevprx', '$wdevpr1', '$wdevpr2', '$wdevdmp', '$wdevdmq', '$wdeviqm')";
                            }

                            $wquery = "insert into log (dateincl, ipuser, iduser, iddevice, idinterface, operation) values (utc_timestamp(), '$wipuser', '$widuser', '$widdevice', '$wid', 7109)";
                            $wres   = mysql_query($wquery,$wsystem_dbid);

                            $wresponse = "<" . '?xml version="1.0" encoding="utf-8"?' . "><followzup>";
                            $wresponse = $wresponse . "<uid>$widuser</uid>";
                            $wresponse = $wresponse . "<did>$widdevice</did>";
                            $wresponse = $wresponse . "<pub>$wdevpub</pub>";
                            $wresponse = $wresponse . "<mod>$wdevmod</mod>";
                            $wresponse = $wresponse . "<pux>$wdevpux</pux>";
                            $wresponse = $wresponse . "</followzup>";

                            $wretframe1 = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $wkey3, $wresponse, MCRYPT_MODE_CBC, str_repeat(chr(0),16));
                            $wretframe  = base64_encode($wretframe1);

                        }

                    }

                }

            }

        }

    } 

# ==============================================================================================================================================================
# DEVICE REQUEST
# ==============================================================================================================================================================

    else
    {

        $wquery = "select devices.iduser, devices.deviceseq, devices.pkpri, interfaces.stamp, 
                          interfaces.idinterface, devices.devicetag, dusers.email
                     from devices, interfaces, users as dusers, users as iusers
                    where devices.iddevice = '$wid' and devices.regstatus = 'a' and
                          devices.idinterface = interfaces.idinterface and interfaces.regstatus != 'd' and
                          devices.iduser = dusers.iduser and dusers.regstatus = 'a' and
                          interfaces.iduser = iusers.iduser and iusers.regstatus = 'a'";

        $wres    = mysql_query($wquery,$wsystem_dbid);
        $wnumreg = mysql_num_rows($wres);

# ==============================================================================================================================================================
# Invalid iddevice
# ==============================================================================================================================================================

        if ( $wnumreg == 0 )
        {
            $wretcode = "7104";
            $wquery = "insert into log (dateincl, ipuser, operation, param) values (utc_timestamp(), '$wipuser', 7104, 'device: $wid')";
            $wres   = mysql_query($wquery,$wsystem_dbid);
        }

        else
        {

            $wvetor       = mysql_fetch_row($wres);
            $widuser      = $wvetor[0];
            $wdeviceseq   = $wvetor[1];
            $wpri64       = $wvetor[2];
            $istamp       = $wvetor[3];
            $widinterface = $wvetor[4];
            $wdevicetag   = $wvetor[5];
            $wemail       = $wvetor[6];

            $wpri    = base64_decode($wpri64);
            $wkey2   = base64_decode($wkey1);
            $wframe2 = base64_decode($wframe1);

            openssl_private_decrypt($wkey2,$wkey3,$wpri);
            $wdecrypt = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $wkey3, $wframe2, MCRYPT_MODE_CBC, str_repeat(chr(0),16));

# ==============================================================================================================================================================
# Invalid key/frame
# ==============================================================================================================================================================

            if ( $wdecrypt == "" or $wkey3 == "" )
            {
                $wretcode = "7102";
                $wquery = "insert into log (dateincl, ipuser, idinterface, iddevice, iduser, operation) values (utc_timestamp(), '$wipuser', '$widinterface', '$wid', '$widuser', 7102)";
                $wres   = mysql_query($wquery,$wsystem_dbid);
            }

            else
            {

                $wxml = @simplexml_load_string($wdecrypt);

                $wcom      = preg_replace("/[^a-z]/","",strtolower($wxml->com));
                $wstamp    = preg_replace("/[^a-z0-9]/","",$wxml->stp);
                $wseq      = (int)$wxml->seq;

# ==============================================================================================================================================================
# Invalid stamp
# ==============================================================================================================================================================

                if ( $wstamp != $istamp )
                {
                    $wretcode = "7105";
                    $wquery = "insert into log (dateincl, ipuser, idinterface, iddevice, iduser, operation) values (utc_timestamp(), '$wipuser', '$widinterface', '$wid', '$widuser', 7105)";
                    $wres   = mysql_query($wquery,$wsystem_dbid);
                }

# ==============================================================================================================================================================
# Invalid command
# ==============================================================================================================================================================

                elseif ( "$wcom" != "ureg" and "$wcom" != "chck" and "$wcom" != "dmsg" and "$wcom" != "lsub" and 
                         "$wcom" != "lkup" and "$wcom" != "schn" and "$wcom" != "uchn" and "$wcom" != "icon" and "$wcom" != "resp" )
                {
                    $wretcode = "7103";
                    $wquery = "insert into log (dateincl, ipuser, idinterface, iddevice, iduser, operation, param) values (utc_timestamp(), '$wipuser', '$widinterface', '$wid', '$widuser', 7103, '$wcom')";
                    $wres   = mysql_query($wquery,$wsystem_dbid);
                }

# ==============================================================================================================================================================
# Invalid sequence
# ==============================================================================================================================================================

                elseif ( $wseq != $wdeviceseq + 1 )
                {

                    $wretcode = "7101";
                    $wquery = "insert into log (dateincl, ipuser, idinterface, iddevice, iduser, operation, param) values (utc_timestamp(), '$wipuser', '$widinterface', '$wid', '$widuser', 7101, '$wcom:$wseq')";
                    $wres   = mysql_query($wquery,$wsystem_dbid);

                    $wresponse = "<" . '?xml version="1.0" encoding="utf-8"?' . "><followzup><seq>$wdeviceseq</seq></followzup>";

                    $wretframe1 = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $wkey3, $wresponse, MCRYPT_MODE_CBC, str_repeat(chr(0),16));
                    $wretframe  = base64_encode($wretframe1);

                }

                else
                {

                    $wquery = "update devices set deviceseq = $wseq, lastact = utc_timestamp() where iddevice = '$wid'";
                    $wres   = mysql_query($wquery,$wsystem_dbid);

# ==============================================================================================================================================================
#
#   CHCK - CHECK FOR NEW MESSAGES
#
#      Param:  FZUP_STAMP   <stp> = stamp
#              FZUP_COMMAND <com> = chck
#              FZUP_LASTSEQ <seq> = sequence
#              FZUP_LASTMSG <msg> = last idmessage
#
#      Return: <md5>MD5-list</md5>                                MD5 ( Device-Tag + Channel-count + Channel-tags + MD5-icons + Flag-responseURL + WelcomeURL)
#              <msg>tag;idmessage;dateincl;msg-text;msg-url</msg> Lista de mensagens 
#
# ==============================================================================================================================================================

                    if ( $wcom == "chck" )
                    {

                        $wlastmsg  = (int)$wxml->msg;

                        $wquery = "select tag, subscode, md5icon, if(responseurl='','n','y'), welcomeurl
                                     from devices, subscriptions, channels, users
                                    where devices.iddevice = '$wid' and devices.regstatus = 'a' and
                                          devices.iduser = subscriptions.iduser and subscriptions.regstatus = 'a' and
                                          subscriptions.idchannel = channels.idchannel and channels.regstatus != 'd' and
                                          subscriptions.iduser = users.iduser and users.regstatus = 'a'
                                    order by tag";

                        $wres = mysql_query($wquery,$wsystem_dbid);
                        $wcountchn = mysql_num_rows($wres);

                        $wstr = "$wdevicetag;$wcountchn";

                        while ( $wvetor = mysql_fetch_row($wres) ) $wstr .= ";" . $wvetor[0] . ";" . $wvetor[1] . ";" . $wvetor[2] . ";" . $wvetor[3] . ";" . $wvetor[4]; 

                        $wmd5list = md5("$wstr");

                        $wnewterm = gmdate("Y-m-d H:i:s", strtotime("+24 hours"));

                        $wquery = "select channels.tag, messages.dateincl, messages.idmessage, messages.regstatus, messages.dateterm, medias.mediatext, medias.mediaurl
                                     from channels, devices, subscriptions, messages, users, medias
                                    where devices.iddevice = '$wid' and devices.regstatus = 'a' and
                                          devices.iduser = subscriptions.iduser and subscriptions.regstatus = 'a' and 
                                          subscriptions.idchannel = channels.idchannel and channels.regstatus = 'a' and
                                          channels.iduser = users.iduser and users.regstatus = 'a' and
                                          subscriptions.idchannel = messages.idchannel and subscriptions.iduser = messages.iduser and
                                          (messages.regstatus = 'p' or messages.regstatus = 's') and messages.dateterm >= '$wagora' and
                                          messages.idchannel = medias.idchannel and messages.mediamd5 = medias.mediamd5 and
                                          messages.idmessage > '$wlastmsg'
                                    order by channels.tag, messages.idmessage";

                        $wres    = mysql_query($wquery,$wsystem_dbid);
                        $wnumreg = mysql_num_rows($wres);

                        $wresponse = "<" . '?xml version="1.0" encoding="utf-8"?' . "><followzup>";
                        $wresponse = $wresponse . "<md5>$wmd5list</md5>";

                        while ( $wvetor = mysql_fetch_row($wres) )
                        {

                            $wtag       = $wvetor[0];
                            $wdateincl  = $wvetor[1];
                            $widmessage = $wvetor[2];
                            $wregstatus = $wvetor[3];
                            $wdateterm  = $wvetor[4];
                            $wmsgtext   = $wvetor[5];
                            $wmsgurl    = $wvetor[6];

                            $wresponse .= "<msg>$wtag;$widmessage;$wdateincl;$wmsgtext;$wmsgurl</msg>";

                            if ( $wregstatus == "p" )
                            {
                                $wquery1 = "update messages set regstatus = 's', dateterm = '$wdateterm' where idmessage = $widmessage and regstatus = 'p'";
                                $wres1   = mysql_query($wquery1,$wsystem_dbid);
                            }

                        }

                        $wresponse .= "</followzup>";

                        $wquery = "insert into log (dateincl, ipuser, idinterface, iddevice, iduser, deviceseq, operation, param) 
                                            values (utc_timestamp(), '$wipuser', '$widinterface', '$wid', '$widuser', '$wseq', 7211, 'chn:$wcountchn new:$wnumreg')";

                        $wres = mysql_query($wquery,$wsystem_dbid);

                    }

# ==============================================================================================================================================================
#
#   LSUB - LIST CHANNELS
#
#      Param:  FZUP_STAMP   <stp> = stamp
#              FZUP_COMMAND <com> = lsub
#              FZUP_LASTSEQ <seq> = sequence
#
#      Return: <tag>Device-tag</tag>                                              Tag do dispositivo
#              <cnt>Channel-count</cnt>                                           Número de canais
#              <chn>Channel-tag;Subscription-code;MD5-icon;flag-responseurl</chn> Lista de canais
#
# ==============================================================================================================================================================

                    elseif ( $wcom == "lsub" )
                    {

                        $wquery = "insert into log (dateincl, ipuser, idinterface, iddevice, iduser, deviceseq, operation) values (utc_timestamp(), '$wipuser', '$widinterface', '$wid', '$widuser', '$wseq', 7221)";
                        $wres   = mysql_query($wquery,$wsystem_dbid);

                        $wquery = "select tag, subscode, md5icon, if(responseurl='','n','y')
                                     from devices, subscriptions, channels, users
                                    where devices.iddevice = '$wid' and devices.regstatus = 'a' and
                                          devices.iduser = subscriptions.iduser and subscriptions.regstatus = 'a' and
                                          subscriptions.idchannel = channels.idchannel and channels.regstatus != 'd' and
                                          channels.iduser = users.iduser and users.regstatus = 'a'
                                    order by tag";

                        $wres = mysql_query($wquery,$wsystem_dbid);
                        $wcountchn = mysql_num_rows($wres);

                        $wresponse = "<" . '?xml version="1.0" encoding="utf-8"?' . "><followzup>";
                        $wresponse = $wresponse . "<tag>$wdevicetag</tag>";
                        $wresponse = $wresponse . "<cnt>$wcountchn</cnt>";

                        while ( $wvetor = mysql_fetch_row($wres) ) $wresponse .= "<chn>"  . $wvetor[0] . ";" . $wvetor[1] . ";" . $wvetor[2] . ";" . $wvetor[3] . "</chn>";

                        $wresponse .= "</followzup>";

                    }

# ==============================================================================================================================================================
#
#   RESP - SEND RESPONSE
#
#      Param:  FZUP_STAMP    <stp> = stamp
#              FZUP_COMMAND  <com> = resp
#              FZUP_LASTSEQ  <seq> = sequence
#              FZUP_CHANNEL  <chn> = channel tag
#              FZUP_RESPONSE <res> = response text (char 60)
#
# ==============================================================================================================================================================

                    elseif ( $wcom == "resp" )
                    {

                        $wtag  = cleanTag($wxml->chn);
                        $wsend = substr(cleanText($wxml->res),0,60);

                        $wresponse = "<" . '?xml version="1.0" encoding="utf-8"?' . "><followzup></followzup>";

                        if ( $wsend == "" )
                        {

                            $wretcode = "7272";
                            $wquery = "insert into log (dateincl, ipuser, idinterface, iddevice, iduser, deviceseq, operation) 
                                                values (utc_timestamp(), '$wipuser', '$widinterface', '$wid', '$widuser', '$wseq', 7272)";
                            $wres   = mysql_query($wquery,$wsystem_dbid);

                        }

                        else
                        {

                            $wquery  = "select channels.idchannel, channels.responseurl, pkeys.pkpri
                                          from channels, pkeys
                                         where channels.tag = BINARY '$wtag' and channels.regstatus != 'd' and channels.idkey = pkeys.idkey and
                                               channels.idchannel in (select idchannel from subscriptions where iduser = '$widuser')";

                            $wres    = mysql_query($wquery,$wsystem_dbid);
                            $wnumreg = mysql_num_rows($wres);
                                
                            if ( $wnumreg == 0 )
                            {

                                $wretcode = "7273";
                                $wquery = "insert into log (dateincl, ipuser, idinterface, iddevice, iduser, deviceseq, operation) 
                                                    values (utc_timestamp(), '$wipuser', '$widinterface', '$wid', '$widuser', '$wseq', 7273)";

                                $wres   = mysql_query($wquery,$wsystem_dbid);

                            }

                            else
                            {
                         
                                $wvetor     = mysql_fetch_row($wres);
                                $widchannel = $wvetor[0];
                                $wurl       = $wvetor[1];
                                $wpri64     = $wvetor[2];

                                $wpri = base64_decode($wpri64);

                                if ( $wurl == "" )
                                {

                                    $wquery = "insert into log (dateincl, ipuser, idinterface, iddevice, iduser, deviceseq, operation) 
                                                        values (utc_timestamp(), '$wipuser', '$widinterface', '$wid', '$widuser', '$wseq', 7274)";

                                    $wres   = mysql_query($wquery,$wsystem_dbid);

                                }

                                else
                                {

                                    $wdecrypt    = "$wagora;$widuser;$wsend";
                                    $werrorssl   = openssl_private_encrypt($wdecrypt, $wencrypt, $wpri);
                                    $wencrypt64  = base64_encode($wencrypt);
                                    $wencrypturl = urlencode($wencrypt64);

                                    $fzup_conf = array ( CURLOPT_URL            => "$wurl",
                                                         CURLOPT_HTTPHEADER     => array ( "application/x-www-form-urlencoded" ),
                                                         CURLOPT_POST           => true,
                                                         CURLOPT_USERAGENT      => "Followzup response",
                                                         CURLOPT_RETURNTRANSFER => false,
                                                         CURLOPT_POSTFIELDS     => array ( "fzupidchannel" => "$widchannel", "fzupresponse" => "$wencrypturl" ) );

                                    $fzup_ch   = curl_init();

                                    curl_setopt_array($fzup_ch, $fzup_conf);

                                    if ( ! $fzup_resp = curl_exec($fzup_ch) )
                                    {

                                        $fzup_errno = curl_errno($fzup_ch);

                                        $wquery = "insert into log (dateincl, ipuser, idinterface, iddevice, idchannel, iduser, deviceseq, operation, param) 
                                                            values (utc_timestamp(), '$wipuser', '$widinterface', '$wid', '$widchannel', '$widuser', '$wseq', 7275, '$fzup_errno')";

                                        $wres   = mysql_query($wquery,$wsystem_dbid);

                                    }

                                    else
                                    {

                                        $wquery = "insert into log (dateincl, ipuser, idinterface, iddevice, idchannel, iduser, deviceseq, operation) 
                                                            values (utc_timestamp(), '$wipuser', '$widinterface', '$wid', '$widchannel', '$widuser', '$wseq', 7271)";

                                        $wres   = mysql_query($wquery,$wsystem_dbid);

                                        $wquery = "insert into responses (dateincl, idchannel, iduser, response)
                                                                  values (utc_timestamp(), '$widchannel', '$widuser', '$wsend')";

                                        $wres   = mysql_query($wquery,$wsystem_dbid);

                                    }

                                    curl_close($fzup_ch);

                                }

                            }

                        }

                    }

# ==============================================================================================================================================================
#
#   ICON - GET ICONS
#
#      Param:  FZUP_STAMP   <stp> = stamp
#              FZUP_COMMAND <com> = icon
#              FZUP_LASTSEQ <seq> = sequence
#              FZUP_CHANNEL <chn> = tag,tag... (lista de tags)
#
#      Return (reticons): icon-string;null,... (null = tag invalida)
#
# ==============================================================================================================================================================

                    elseif ( $wcom == "icon" )
                    {

                        $wtaglist = explode(",",preg_replace("/[^A-Za-z0-9\-\,]/","",$wxml->chn));

                        $wresponse = "<" . '?xml version="1.0" encoding="utf-8"?' . "><followzup></followzup>";

                        foreach ( $wtaglist as $wtag )
                        {

                            $wquery1 = "select channels.idchannel, channels.channelicon
                                          from channels, users
                                         where channels.regstatus != 'd' and channels.tag = BINARY '$wtag' and
                                               channels.iduser = users.iduser and users.regstatus = 'a'";

                            $wres1 = mysql_query($wquery1,$wsystem_dbid);
                            $wnumreg1 = mysql_num_rows($wres1);
                                
                            if ( $wnumreg1 == 0 )
                            {

                                $wretcode = "7232";
                                $wquery = "insert into log (dateincl, ipuser, idinterface, iddevice, iduser, deviceseq, operation, param) 
                                                    values (utc_timestamp(), '$wipuser', '$widinterface', '$wid', '$widuser', '$wseq', 7232, '$wtag')";
                                $wres   = mysql_query($wquery,$wsystem_dbid);

                                $wreticons = $wreticons . ";null";

                            }

                            else
                            {

                                $wvetor1    = mysql_fetch_row($wres1);
                                $widchannel = $wvetor1[0];
                                $wicon      = $wvetor1[1];

                                $wquery = "insert into log (dateincl, ipuser, idinterface, iddevice, idchannel, iduser, deviceseq, operation) 
                                                    values (utc_timestamp(), '$wipuser', '$widinterface', '$wid', '$widchannel', '$widuser', '$wseq', 7231)";
                                $wres   = mysql_query($wquery,$wsystem_dbid);
    
                                $wreticons = $wreticons . ";$wicon";

                            }

                        }

                        $wreticons = substr($wreticons,1);

                    }

# ==============================================================================================================================================================
#
#   LKUP - SEARCH CHANNELS
#
#      Param:  FZUP_STAMP   <stp> = stamp
#              FZUP_COMMAND <com> = lkup
#              FZUP_LASTSEQ <seq> = sequence
#              FZUP_CHANNEL <chn> = search tag
#              FZUP_MORE    <mor> = search more
#
#      Return: <chn>Channel-tag;Flag-private;Flag-private-code;MD5-icon;brief</chn> Lista de canais pesquisados
#              <mor>more-tag</mor>
#
# ==============================================================================================================================================================

                    elseif ( $wcom == "lkup" )
                    {

                        $wtag  = cleanTag($wxml->chn);
                        $wmore = cleanTag($wxml->mor);

                        $wlimit = 10;

                        $wresponse = "<" . '?xml version="1.0" encoding="utf-8"?' . "><followzup>";

                        if ( strlen($wtag) < 3 )
                        {
                            $wretcode = "7242";
                            $wquery = "insert into log (dateincl, ipuser, idinterface, iddevice, iduser, deviceseq, operation, param) 
                                                values (utc_timestamp(), '$wipuser', '$widinterface', '$wid', '$widuser', '$wseq', 7242, '$wtag:$wmore')";
                            $wres   = mysql_query($wquery,$wsystem_dbid);
                        }

                        else
                        {

                            $wquery = "select
                                           ( select count(*)
                                               from channels
                                              where channels.regstatus != 'd' and channels.channeltype = 'u' and channels.tag like '$wtag%' and channels.tag > '$wmore' and
                                                    channels.idchannel not in (select idchannel from subscriptions where iduser = '$widuser' and regstatus = 'a') ) as total1,
                                           ( select count(*)
                                               from channels
                                              where channels.regstatus != 'd' and channels.channeltype = 'r' and channels.tag = BINARY '$wtag' and channels.tag > '$wmore' and
                                                    channels.idchannel not in (select idchannel from subscriptions where iduser = '$widuser' and regstatus = 'a') ) as total2";

                            $wres    = mysql_query($wquery,$wsystem_dbid);
                            $wvetor  = mysql_fetch_row($wres);
                            $wtotal1 = $wvetor[0];
                            $wtotal2 = $wvetor[1];

                            $wquery = "insert into log (dateincl, ipuser, idinterface, iddevice, iduser, deviceseq, operation, param) 
                                                values (utc_timestamp(), '$wipuser', '$widinterface', '$wid', '$widuser', '$wseq', 7241, '$wtag:$wmore pub:$wtotal1 pri:$wtotal2')";
                            $wres   = mysql_query($wquery,$wsystem_dbid);
    
                            $wquery = "(select tag, 'n', 'n', md5icon, briefing
                                          from channels
                                         where channels.regstatus != 'd' and channels.channeltype = 'u' and channels.tag like '$wtag%' and channels.tag > '$wmore' and
                                               channels.idchannel not in (select idchannel from subscriptions where iduser = '$widuser' and regstatus = 'a') )
                                        union
                                       (select tag, 'y', if(privcode='','n','y'), md5icon, briefing
                                          from channels
                                         where channels.regstatus != 'd' and channels.channeltype = 'r' and channels.tag = BINARY '$wtag' and channels.tag > '$wmore' and
                                               channels.idchannel not in (select idchannel from subscriptions where iduser = '$widuser' and regstatus = 'a') )
                                         order by 1 limit $wlimit";

                            $wres = mysql_query($wquery,$wsystem_dbid);

                            $wlast = "";

                            while ( $wvetor = mysql_fetch_row($wres) )
                            {
                                $wresponse .= "<chn>"  . $wvetor[0] . ";" . $wvetor[1]  . ";" . $wvetor[2]  . ";" . $wvetor[3] . ";" . base64_encode($wvetor[4]) . "</chn>";
                                $wlast = $wvetor[0];
                            }

                            if ( ( $wtotal1 + $wtotal2 ) > $wlimit ) $wresponse .= "<mor>$wlast</mor>";
                            else                                     $wresponse .= "<mor></mor>";

                        }

                        $wresponse .= "</followzup>";

                    }

# ==============================================================================================================================================================
#
#   SCHN - SUBSCRIBE CHANNEL
#
#      Param:  FZUP_STAMP    <stp> = stamp
#              FZUP_COMMAND  <com> = schn
#              FZUP_LASTSEQ  <seq> = sequence
#              FZUP_CHANNEL  <chn> = channel tag
#              FZUP_PRIVCODE <pvc> = private code
#
# ==============================================================================================================================================================

                    elseif ( $wcom == "schn" )
                    {

                        $wtag  = cleanTag($wxml->chn);
                        $wcode = cleanTag($wxml->pvc);

                        $wresponse = "<" . '?xml version="1.0" encoding="utf-8"?' . "><followzup></followzup>";

                        $wquery = "select channels.idchannel, channels.welcome, channels.channeltype, channels.privcode, channels.welcomeurl
                                     from channels, users
                                    where channels.tag = BINARY '$wtag' and channels.regstatus != 'd' and
                                          channels.iduser = users.iduser and users.regstatus = 'a' and
                                          channels.idchannel not in (select idchannel from subscriptions where iduser = '$widuser' and regstatus = 'a')";

                        $wres    = mysql_query($wquery,$wsystem_dbid);
                        $wnumreg = mysql_num_rows($wres);

                        if ( $wnumreg == 0 )
                        { 
                            $wretcode = "7252";
                            $wquery = "insert into log (dateincl, ipuser, idinterface, iddevice, iduser, deviceseq, operation, param) 
                                                values (utc_timestamp(), '$wipuser', '$widinterface', '$wid', '$widuser', '$wseq', 7252, '$wtag')";
                            $wres   = mysql_query($wquery,$wsystem_dbid);
                        }

                        else
                        {

                            $wvetor     = mysql_fetch_row($wres);
                            $widchannel = $wvetor[0];
                            $welcome    = $wvetor[1];
                            $wtype      = $wvetor[2];
                            $wprivcode  = $wvetor[3];
                            $welcomeurl = $wvetor[4];

                            if ( $wtype == "r" and $wprivcode != "" and $wprivcode != $wcode )
                            {
                                $wretcode = "7253";
                                $wquery = "insert into log (dateincl, ipuser, idinterface, iddevice, iduser, deviceseq, operation, param) 
                                                    values (utc_timestamp(), '$wipuser', '$widinterface', '$wid', '$widuser', '$wseq', 7253, '$wcode')";
                                $wres   = mysql_query($wquery,$wsystem_dbid);
                            }

                            else
                            {

                                $wsubscode = rand(10000000,99999999);

                                $wquery = "insert into log (dateincl, ipuser, idinterface, iddevice, idchannel, iduser, deviceseq, operation, param) 
                                                    values (utc_timestamp(), '$wipuser', '$widinterface', '$wid', '$widchannel', '$widuser', '$wseq', 7251, 'subscode $wsubscode')";

                                $wres   = mysql_query($wquery,$wsystem_dbid);

                                $wquery  = "select regstatus from subscriptions where idchannel = '$widchannel' and iduser = '$widuser'";
                                $wres    = mysql_query($wquery,$wsystem_dbid);
                                $wnumreg = mysql_num_rows($wres);

                                if ( $wnumreg == 0 )
                                     $wquery = "insert into subscriptions (iduser, idchannel, dateincl, subscode, regstatus) 
                                                                   values ('$widuser','$widchannel','$wagora','$wsubscode','a')";
                                else
                                     $wquery = "update subscriptions set subscode = '$wsubscode', regstatus = 'a', dateincl = utc_timestamp()
                                                 where idchannel = '$widchannel' and iduser = '$widuser'";

                                $wres   = mysql_query($wquery,$wsystem_dbid);

                                if ( $welcome != "" )
                                {

                                    $welcome    = base64_encode($welcome);
                                    $welcomeurl = base64_encode($welcomeurl);

                                    $wdateterm = gmdate("Y-m-d H:i:s", strtotime("+24 hours"));

                                    $wmediamd5 = md5("$welcome;$welcomeurl");
                                    $wquery2   = "insert into medias (idchannel, mediamd5, mediatext, mediaurl) values ('$widchannel', '$wmediamd5', '$welcome', '$welcomeurl')";
                                    $wres2     = mysql_query($wquery2,$wsystem_dbid);

                                    $wquery = "insert into messages (idchannel, iduser, mediamd5, dateincl, dateterm, hours, regstatus)
                                                             values ('$widchannel', '$widuser', '$wmediamd5', '$wagora', '$wdateterm', 24, 'p')";

                                    $wres   = mysql_query($wquery,$wsystem_dbid);

                                }

                            }

                        }

                    }

# ==============================================================================================================================================================
#
#   UCHN - UNSUBSCRIBE CHANNEL
#
#      Param:  FZUP_STAMP    <stp> = stamp
#              FZUP_COMMAND  <com> = uchn
#              FZUP_LASTSEQ  <seq> = sequence
#              FZUP_CHANNEL  <chn> = channel tag
#
# ==============================================================================================================================================================

                    elseif ( $wcom == "uchn" )
                    {

                        $wtag = cleanTag($wxml->chn);

                        $wresponse = "<" . '?xml version="1.0" encoding="utf-8"?' . "><followzup></followzup>";

                        $wquery = "select channels.idchannel
                                     from channels
                                    where channels.tag = BINARY '$wtag' and channels.regstatus != 'd' and
                                          channels.idchannel in (select idchannel from subscriptions where iduser = '$widuser')";

                        $wres    = mysql_query($wquery,$wsystem_dbid);
                        $wnumreg = mysql_num_rows($wres);

                        if ( $wnumreg == 0 )
                        {
                            $wretcode = "7262";
                            $wquery = "insert into log (dateincl, ipuser, idinterface, iddevice, iduser, deviceseq, operation, param) 
                                                values (utc_timestamp(), '$wipuser', '$widinterface', '$wid', '$widuser', '$wseq', 7262, '$wtag')";
                            $wres   = mysql_query($wquery,$wsystem_dbid);
                        }

                        else
                        {

                            $wvetor     = mysql_fetch_row($wres);
                            $widchannel = $wvetor[0];

                            $wquery = "insert into log (dateincl, ipuser, idinterface, iddevice, idchannel, iduser, deviceseq, operation) 
                                                values (utc_timestamp(), '$wipuser', '$widinterface', '$wid', '$widchannel', '$widuser', '$wseq', 7261)";

                            $wres   = mysql_query($wquery,$wsystem_dbid);

                            $wquery = "update subscriptions set regstatus = 'd' where idchannel = '$widchannel' and iduser = '$widuser'";

                            $wres   = mysql_query($wquery,$wsystem_dbid);

                            $wquery = "update messages set regstatus = 'd' where idchannel = '$widchannel' and iduser = '$widuser' and regstatus != 'd'";

                            $wres   = mysql_query($wquery,$wsystem_dbid);

                        }

                    }

# ==============================================================================================================================================================
#
#   DMSG - DELETE MESSAGE
#
#      Param:  FZUP_STAMP    <stp> = stamp
#              FZUP_COMMAND  <com> = icon
#              FZUP_LASTSEQ  <seq> = sequence
#              FZUP_MESSAGE  <msg> = idmessage
#
# ==============================================================================================================================================================

                    elseif ( $wcom == "dmsg" )
                    {

                        $widmessage  = (int)$wxml->msg;

                        $wresponse = "<" . '?xml version="1.0" encoding="utf-8"?' . "><followzup></followzup>";

                        if ( $widmessage == 0 )
                        {
                            $wretcode = "7282";
                            $wquery = "insert into log (dateincl, ipuser, idinterface, iddevice, iduser, deviceseq, operation, param)
                                                values (utc_timestamp(), '$wipuser', '$widinterface', '$wid', '$widuser', '$wseq', 7282, '$widmessage')";
                            $wres     = mysql_query($wquery,$wsystem_dbid);
                        }

                        else
                        {

                            $wquery = "select idmessage
                                         from messages, subscriptions
                                        where messages.idmessage = $widmessage and messages.iduser = '$widuser' and
                                              messages.iduser = subscriptions.iduser and messages.idchannel = subscriptions.idchannel and
                                              subscriptions.regstatus = 'a'";

                            $wres    = mysql_query($wquery,$wsystem_dbid);
                            $wnumreg = mysql_num_rows($wres);

                            if ( $wnumreg == 0 )
                            {
                                $wretcode = "7282";
                                $wquery = "insert into log (dateincl, ipuser, idinterface, iddevice, iduser, deviceseq, operation, param) 
                                                    values (utc_timestamp(), '$wipuser', '$widinterface', '$wid', '$widuser', '$wseq', 7282, '$widmessage')";
                                $wres   = mysql_query($wquery,$wsystem_dbid);
                            }

                            else
                            {

                                $wquery = "insert into log (dateincl, ipuser, idinterface, iddevice, iduser, deviceseq, operation, param) 
                                                    values (utc_timestamp(), '$wipuser', '$widinterface', '$wid', '$widuser', '$wseq', 7281, '$widmessage')";

                                $wres   = mysql_query($wquery,$wsystem_dbid);

                                $wquery = "update messages set regstatus = 'd' where idmessage = $widmessage and iduser = '$widuser'";

                                $wres   = mysql_query($wquery,$wsystem_dbid);

                            }

                        }

                    }

# ==============================================================================================================================================================
#
#   UREG - UNREGISTER DEVICE
#
#      Param:  FZUP_STAMP    <stp> = stamp
#              FZUP_COMMAND  <com> = ureg
#              FZUP_LASTSEQ  <seq> = sequence
#
# ==============================================================================================================================================================

                    elseif ( $wcom == "ureg" )
                    {

                        $wresponse = "<" . '?xml version="1.0" encoding="utf-8"?' . "><followzup></followzup>";

                        $wquery = "insert into log (dateincl, ipuser, idinterface, iddevice, iduser, deviceseq, operation) 
                                            values (utc_timestamp(), '$wipuser', '$widinterface', '$wid', '$widuser', '$wseq', 7110)";

                        $wres   = mysql_query($wquery,$wsystem_dbid);

                        $wquery = "update devices set devicetag = concat('.',iddevice,'.',devicetag), regstatus = 'd' where iddevice = '$wid' and iduser = '$widuser'";

                        $wres   = mysql_query($wquery,$wsystem_dbid);

                        sleep(1);

                    }

# ==============================================================================================================================================================
# Invalid command
# ==============================================================================================================================================================

                    else
                    {
                        $wretcode = "7199";
                        $wquery = "insert into log (dateincl, ipuser, idinterface, iddevice, iduser, operation, param) values (utc_timestamp(), '$wipuser', '$widinterface', '$wid', '$widuser', 7199, '$wcom')";
                        $wres   = mysql_query($wquery,$wsystem_dbid);
                    }

                    $wretframe1 = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $wkey3, $wresponse, MCRYPT_MODE_CBC, str_repeat(chr(0),16));
                    $wretframe  = base64_encode($wretframe1);

                }

            }

        }

    }

    if ( $wretcode != "0" and $wretcode != "7101" ) $wretframe = "";

    if ( $wretframe != "" ) $wretmd5 = md5("$wretframe");

    header("Content-Type: application/xml; charset=utf-8;");
    echo "<" . '?xml version="1.0" encoding="utf-8"?' . ">";
    echo "<followzup>";
    echo    "<retcode>$wretcode</retcode>";
    echo    "<retframe>$wretframe</retframe>";
    echo    "<retmd5>$wretmd5</retmd5>";
    echo    "<reticons>$wreticons</reticons>";
    echo "</followzup>";

?>

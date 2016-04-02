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

    // ===== control

    $wquery  = "insert into control (idcontrol) values (0)";
    $wres    = mysql_query($wquery,$wsystem_dbid);

    // ===== Usuário admin@followzup.com, senha = 123456

    $wpass   = "123456";

    $wmd5    = md5($wpass . ";" . strrev($wpass));

    $wquery  = "insert into users (iduser, email, pass, dateincl, name, maxchannels, regstatus) 
                           values ('z00000000000', 'admin@followzup.com', '$wmd5', utc_timestamp(), 'Admin', 100, 'a')";

    $wres    = mysql_query($wquery,$wsystem_dbid);

    // ===== RSA key para módulos web (signup, rescue, transfer owner)

    $wpk  = keygenerator(2048);
    $wpri = $wpk["pri"];
    $wpub = $wpk["pub"];
    $wmod = $wpk["mod"];
    $wpux = $wpk["pux"];
    $wprx = $wpk["prx"];
    $wpr1 = $wpk["pr1"];
    $wpr2 = $wpk["pr2"];
    $wdmp = $wpk["dmp"];
    $wdmq = $wpk["dmq"];
    $wiqm = $wpk["iqm"];

    $wquery  = "insert into pkeys (idkey, dateincl, pkpub, pkpri, pkmod, pkpux, pkprx, pkpr1, pkpr2, pkdmp, pkdmq, pkiqm) 
                           values ('keyweb', utc_timestamp(), '$wpub', '$wpri', '$wmod', '$wpux', '$wprx', '$wpr1', '$wpr2', '$wdmp', '$wdmq', '$wiqm')";

    $wres    = mysql_query($wquery,$wsystem_dbid);

    // ===== RSA key para registro de novos dispositivos

    $wpk  = keygenerator(2048);
    $wpri = $wpk["pri"];
    $wpub = $wpk["pub"];
    $wmod = $wpk["mod"];
    $wpux = $wpk["pux"];
    $wprx = $wpk["prx"];
    $wpr1 = $wpk["pr1"];
    $wpr2 = $wpk["pr2"];
    $wdmp = $wpk["dmp"];
    $wdmq = $wpk["dmq"];
    $wiqm = $wpk["iqm"];

    $wquery  = "insert into pkeys (idkey, dateincl, pkpub, pkpri, pkmod, pkpux, pkprx, pkpr1, pkpr2, pkdmp, pkdmq, pkiqm) 
                           values ('keydev', utc_timestamp(), '$wpub', '$wpri', '$wmod', '$wpux', '$wprx', '$wpr1', '$wpr2', '$wdmp', '$wdmq', '$wiqm')";

    $wres    = mysql_query($wquery,$wsystem_dbid);

?>

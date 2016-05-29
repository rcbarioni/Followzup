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

    function idgenerator ($wparam)
    {

        if     ( $wparam == "usr" ) $wprefix = "z";
        elseif ( $wparam == "chn" ) $wprefix = "c";
        elseif ( $wparam == "dev" ) $wprefix = "d";
        elseif ( $wparam == "int" ) $wprefix = "i";
        elseif ( $wparam == "key" ) $wprefix = "k";
        else return "";

        $wtime  = explode(" ",microtime());
        $wcode1 = str_pad(strval(base_convert($wtime[1]-1455000000,10,36)),6,"0",STR_PAD_LEFT);
        $wcode2 = str_pad(strval(base_convert($wtime[0]*1000000,10,36)),4,"0",STR_PAD_LEFT);
        $wkey   = $wcode1 . $wcode2 . rand(2,9);

        return $wprefix . strtr("$wkey","01lo","abmp");

    }

    function keygenerator ($wlength)
    {

        // descricao: gerar par de chaves assimetricas
        // uso:       $w = keygenerator($wlength)
        // retorno:   $w["pub"] = chave publica       
        //            $w["pri"] = chave privada       
        //            $w["mod"] = modulus (n)         
        //            $w["pux"] = public expoent (e)  
        //            $w["prx"] = private expoent (d) 
        //            $w["pr1"] = prime1 (p)          
        //            $w["pr2"] = prime2 (q)          
        //            $w["dmp"] = dmp1                
        //            $w["dmq"] = dmq1                
        //            $w["iqm"] = iqmp                

        $wparm = array ( "digest_alg" => "sha1",
                         "private_key_bits" => $wlength,
                         "private_key_type" => OPENSSL_KEYTYPE_RSA );

        $wres = openssl_pkey_new($wparm);
        openssl_pkey_export($wres,$wprikey);
        $wdet = openssl_pkey_get_details($wres);
        $wrsa = $wdet["rsa"];
        $wprikey  = base64_encode($wprikey);
        $wpubkey  = base64_encode($wdet["key"]);
        $wmodulus = bin2hex($wrsa["n"]);
        $wpubexpo = bin2hex($wrsa["e"]);
        $wpriexpo = bin2hex($wrsa["d"]);
        $wprime1  = bin2hex($wrsa["p"]);
        $wprime2  = bin2hex($wrsa["q"]);
        $wdmp1    = bin2hex($wrsa["dmp1"]);
        $wdmq1    = bin2hex($wrsa["dmq1"]);
        $wiqmp    = bin2hex($wrsa["iqmp"]);

        return array ( "pub" => $wpubkey, 
                       "pri" => $wprikey, 
                       "mod" => $wmodulus, 
                       "pux" => $wpubexpo, 
                       "prx" => $wpriexpo, 
                       "pr1" => $wprime1, 
                       "pr2" => $wprime2, 
                       "dmp" => $wdmp1, 
                       "dmq" => $wdmq1, 
                       "iqm" => $wiqmp );

    }

    function newkey ($widchannel)
    {

        global $wsystem_dbid;

        $wpk    = keygenerator(2048);

        $wpkpri = $wpk["pri"];
        $wpkpub = $wpk["pub"];
        $wpkmod = $wpk["mod"];
        $wpkpux = $wpk["pux"];
        $wpkprx = $wpk["prx"];
        $wpkpr1 = $wpk["pr1"];
        $wpkpr2 = $wpk["pr2"];
        $wpkdmp = $wpk["dmp"];
        $wpkdmq = $wpk["dmq"];
        $wpkiqm = $wpk["iqm"];

        $widkey = idgenerator("key");

        $wquery = "insert into pkeys (idkey, idchannel, dateincl, pkpub, pkpri, pkmod, pkpux, pkprx, pkpr1, pkpr2, pkdmp, pkdmq, pkiqm) 
                              values ('$widkey', '$widchannel', utc_timestamp(), '$wpkpub', '$wpkpri', '$wpkmod', '$wpkpux', '$wpkprx', '$wpkpr1', '$wpkpr2', '$wpkdmp', '$wpkdmq', '$wpkiqm')"; 

        while ( !mysql_query($wquery,$wsystem_dbid) )
        {

            $wquery1 = "insert into log (dateincl, idagent, ipuser, iduser, operation, param) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', 1001, '$widkey')";
            $wres1   = mysql_query($wquery1,$wsystem_dbid);

            $widkey = idgenerator("key");

            $wquery = "insert into pkeys (idkey, idchannel, dateincl, pkpub, pkpri, pkmod, pkpux, pkprx, pkpr1, pkpr2, pkdmp, pkdmq, pkiqm) 
                                  values ('$widkey', '$widchannel', utc_timestamp(), '$wpkpub', '$wpkpri', '$wpkmod', '$wpkpux', '$wpkprx', '$wpkpr1', '$wpkpr2', '$wpkdmp', '$wpkdmq', '$wpkiqm')"; 
        }

        return $widkey;

    }

    function dataencrypt ($wdata,$wpkey)
    {

        // descricao: encriptar dados
        // uso:       $w = dataencrypt(dados,chave-publica)
        // retorno:   $w["ekey"] = envelope (base64)
        //            $w["data"] = dados encriptados (base64)

        $wpubkey = base64_decode($wpkey);
        openssl_seal($wdata, $wencrypted, $ekey, array($wpubkey));
        $wenvkey  = rawurlencode(base64_encode($ekey[0]));
        $wenvdata = rawurlencode(base64_encode($wencrypted));

        return array ( "ekey" => $wenvkey, "data" => $wenvdata );

    }

    function datadecrypt ($wdata,$wkey,$wpkey)
    {

        // descricao: decriptar dados
        // uso:       $w = datadecrypt(dados,envelope,chave-privada)
        // retorno:   $w["data"] = dados decriptados

        $wencrypted = base64_decode(rawurldecode($wdata));
        $ekey       = base64_decode(rawurldecode($wkey));
        $wprikey    = base64_decode($wpkey);
        openssl_open($wencrypted, $wdecrypted, $ekey, $wprikey);

        return ($wdecrypted);

    }

    function cleanText ($text)
    {

        $subs = array ( chr(34) => "", chr(39) => "", chr(96) => "" );

        return trim(strip_tags(strtr($text,$subs)));

    }

    function cleanTag ($string)
    {

        $string = preg_replace('/[^A-Za-z0-9\-]/', "", $string);
        $string = preg_replace('/-+/', '-', $string);
        $string = trim($string,'\-');

        if ( strlen($string) < 3 or strlen($string) > 32 ) return "";

        return ($string);

    }

    function validaEmail ($email)
    {

        $er = "/^(([0-9a-zA-Z]+[-._+&])*[0-9a-zA-Z]+@([-0-9a-zA-Z]+[.])+[a-zA-Z]{2,6}){0,1}$/";

        if (preg_match($er, $email)) return true;

        else return false;

    }

    function buildMail ($work)
    {

        $wcorpo = '

        <!DOCTYPE HTML>
        <html>
            <head>
                <title>Followzup</title>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            </head>
            <body>
                <table style="margin: 20px; width: 600px;">
                    <tr>
                        <td style="background-color: #1a2660; border: 3px solid #1a2660; padding: 5px;">
                            <span style="color:white; font-size:19pt; font-family: sans-serif;"><b>Followzup&#46;com</b></span>
                            <span style="color:white; font-size:11.5pt; font-family: sans-serif;"><br>Business Messaging</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border: 3px solid #1a2660; padding: 20px;">
                            <span style="font-size:9pt; font-family: sans-serif;">' . $work . '</span>
                        </td>
                    </tr>
                </table>
            </body>
        </html>';

        return ($wcorpo);

    }

    function cleanUrl ($wurl)
    {

        if ( $wurl == "" ) return "";

        $wurl1 = parse_url("$wurl");

        if ( !isset($wurl1["scheme"]) ) $wurl1 = parse_url("http://$wurl");

        if ( isset($wurl1["path"]) ) "/" . trim($wurl1["path"],"/");
        else $wurl1["path"] = "";

        if ( isset($wurl1["query"]) ) $wurl1["query"] = "?" . $wurl1["query"];
        else $wurl1["query"] = "";

        if ( isset($wurl1["fragment"]) ) $wurl1["fragment"] = "#" . $wurl1["fragment"];
        else $wurl1["fragment"] = "";

        if ( $wurl1["scheme"] != "http" or isset($wurl1["port"]) or isset($wurl1["user"]) or isset($wurl1["pass"]) ) return "error";

        return $wurl1["scheme"] . "://" . $wurl1["host"] . $wurl1["path"] . $wurl1["query"] . $wurl1["fragment"];

    }

?>

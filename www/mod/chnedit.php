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

    $wfollowzup_icon   = "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";
    $wfollowzup_iconpb = "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";

    if ( $widuser == "" )
    {
        header("Location: .");
        exit;
    }

    $wmd5icon = md5($wfollowzup_iconpb);

    $wmsg = "";

    if ( isset($_GET["chn"]) ) $_SESSION["idchannel"] = preg_replace('/[^a-z0-9]/',"",$_GET["chn"]);
    
    elseif ( !isset($_SESSION["idchannel"]) or !isset($_POST["chnedit_go"]) )
    {
        header("Location: .");
        exit;
    }

    $widchannel = $_SESSION["idchannel"];

    if ( $widchannel != "new" )
    {

        $wquery1 = "select tag, briefing, welcome, channeltype, privcode, welcomeurl, responseurl
                      from channels
                     where idchannel = '$widchannel' and iduser = '$widuser' and regstatus != 'd'";

        $wres1    = mysql_query($wquery1,$wsystem_dbid);
        $wnumreg1 = mysql_num_rows($wres1);

        if ( $wnumreg1 == 0 )
        {
            header("Location: chns");
            exit;
        }

        $wvetor1           = mysql_fetch_row($wres1);
        $wform_tag         = $wvetor1[0];
        $wform_briefing    = $wvetor1[1];
        $wform_welcome     = $wvetor1[2];
        $wform_chntype     = $wvetor1[3];
        $wform_privcode    = $wvetor1[4];
        $wform_welcomeurl  = $wvetor1[5];
        $wform_responseurl = $wvetor1[6];

        $_SESSION["edittag"] = strtoupper($wform_tag);

        $woldtag = strtoupper($wform_tag);

        $wcabtag = "Tag do Canal (alterar apenas maiúsculas/minúsculas):";

    }

    else
    {

        $wquery1 = "select count(*) from channels where iduser = '$widuser' and regstatus != 'd'";

        $wres1   = mysql_query($wquery1,$wsystem_dbid);
        $wvetor1 = mysql_fetch_row($wres1);
        $wcount  = $wvetor1[0];

        if ( $wcount >= $wmaxchns )
        {
            header("Location: chns");
            exit;
        }

        $wform_tag         = "";
        $wform_briefing    = "";
        $wform_welcome     = "";
        $wform_chntype     = "";
        $wform_privcode    = "";
        $wform_welcomeurl  = "";
        $wform_responseurl = "";

        $woldtag = "";

        $wcabtag = "Tag do Canal:";

    }

    if ( isset($_POST["chnedit_go"]) )
    {
        
        $wform_tag         = cleanTag($_POST["chnedit_tag"]);
        $wform_chntype     = cleanText($_POST["chnedit_chntype"]);
        $wform_briefing    = cleanText(substr($_POST["chnedit_briefing"],0,200));
        $wform_welcome     = cleanText(substr($_POST["chnedit_welcome"],0,200));
        $wform_privcode    = strtoupper(substr(cleanText($_POST["chnedit_privcode"]),0,8));
        $wform_welcomeurl  = $_POST["chnedit_welcomeurl"];
        $wform_responseurl = $_POST["chnedit_responseurl"];

        if ( $wform_chntype == "" ) $wform_chntype = "u";
        else                        $wform_chntype = "r";

        $wcome1 = parse_url(str_replace(" ","",$wform_welcomeurl));

        if ( $wcome1["scheme"] == "" ) $wcome1 = parse_url(str_replace(" ","","http://" . $wform_welcomeurl));

        if ( $wcome1["host"] == "" ) $wcome2 = ""; 

        else
        {

            $wcome2 = "http://" . $wcome1["host"];

            if ( trim($wcome1["path"],"/") != "" ) $wcome2 .= "/" . trim($wcome1["path"],"/");

            if ( trim($wcome1["query"],"/") != "" ) $wcome2 .= "?" . trim($wcome1["query"],"/");

        }

        $wresp1 = parse_url(str_replace(" ","",$wform_responseurl));

        if ( $wresp1["scheme"] == "" ) $wresp1 = parse_url(str_replace(" ","","http://" . $wform_responseurl));

        if ( $wresp1["host"] == "" ) $wresp2 = "";

        else
        {

            $wresp2 = "http://" . $wresp1["host"];

            if ( trim($wresp1["path"],"/") != "" ) $wresp2 .= "/" . trim($wresp1["path"],"/");

        }

        if ( $wform_tag != $_POST["chnedit_tag"] or $wform_tag == "" or ( strtoupper($wform_tag) != $woldtag and $woldtag != "" ) )
        {

            $wmsg = "Informe uma Tag válida<br>(letras, números e hífen)";
            $wform_tag = cleanText($_POST["chnedit_tag"]);

            $wquery = "insert into log (dateincl, idagent, ipuser, iduser, idchannel, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', '$widchannel', 2202)";
            $wres   = mysql_query($wquery,$wsystem_dbid);

        }

        elseif ( preg_replace('/[^A-Z0-9]/',"",$wform_privcode) != $wform_privcode )
        {

            $wmsg = "Informe um Private Code válido.<br>(apenas letras e números)";

            $wquery = "insert into log (dateincl, idagent, ipuser, iduser, idchannel, operation, param) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', '$widchannel', 2206, '$wform_privcode')";
            $wres   = mysql_query($wquery,$wsystem_dbid);

        }

        elseif ( $wcome2 != $wform_welcomeurl or $wresp2 != $wform_responseurl )
        {

            $wmsg = "Welcome URL e/ou Response URL ajustados";

            $wform_welcomeurl  = $wcome2;
            $wform_responseurl = $wresp2;

            $wquery = "insert into log (dateincl, idagent, ipuser, iduser, idchannel, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', '$widchannel', 2210)";
            $wres   = mysql_query($wquery,$wsystem_dbid);

        }

        else
        {        

            $wquery1  = "select idchannel from channels where idchannel != '$widchannel' and tag = '$wform_tag'";
            $wres1    = mysql_query($wquery1,$wsystem_dbid);
            $wnumreg1 = mysql_num_rows($wres1);

            if ( $wnumreg1 != 0 )
            {

                $wmsg = "Essa Tag já existe";

                $wquery = "insert into log (dateincl, idagent, ipuser, iduser, idchannel, operation, param) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', '$widchannel', 2203, '$wform_tag')";
                $wres   = mysql_query($wquery,$wsystem_dbid);

            }

            elseif ( strlen($wform_tag) < 5 and $widuser != "z00000000000" and strtoupper($wform_tag) != $_SESSION["edittag"] )
            {

                $wmsg = 'Tag disponível sob consulta. <a href="help_cs#tags"><br><b>Clique aqui e saiba mais</b></a>';

                $wquery = "insert into log (dateincl, idagent, ipuser, iduser, idchannel, operation, param) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', '$widchannel', 2207, '$wform_tag')";
                $wres   = mysql_query($wquery,$wsystem_dbid);

            }

            else
            {

                if ( $wform_chntype == "u" ) $wform_privcode = "";

                if ( $widchannel == "new" )
                {

                    $xidchannel = idgenerator("chn");

                    $wquery2   = "insert into channels (idchannel, iduser, tag, briefing, welcome, channeltype, privcode, dateincl, regstatus, channelicon, md5icon, welcomeurl, responseurl) 
                                                values ('$xidchannel', '$widuser', '$wform_tag', '$wform_briefing', '$wform_welcome', '$wform_chntype', '$wform_privcode', '$wagora', 'a', '$wfollowzup_iconpb', '$wmd5icon', '$wform_welcomeurl', '$wform_responseurl')"; 

                    while ( !mysql_query($wquery2,$wsystem_dbid) )
                    {

                        $wquery1  = "select idchannel from channels where tag = '$wform_tag'";
                        $wres1    = mysql_query($wquery1,$wsystem_dbid);
                        $wnumreg1 = mysql_num_rows($wres1);

                        if ( $wnumreg1 > 0 )
                        {
                            header("Location: chns");
                            exit;
                        }

                        $wquery9 = "insert into log (dateincl, idagent, ipuser, iduser, operation, param) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', 1001, '$xidchannel')";
                        $wres9   = mysql_query($wquery9,$wsystem_dbid);

                        $xidchannel = idgenerator("chn");

                        $wquery2   = "insert into channels (idchannel, iduser, tag, briefing, welcome, channeltype, privcode, dateincl, regstatus, channelicon, md5icon, welcomeurl, responseurl) 
                                                    values ('$xidchannel', '$widuser', '$wform_tag', '$wform_briefing', '$wform_welcome', '$wform_chntype', '$wform_privcode', '$wagora', 'a', '$wfollowzup_iconpb', '$wmd5icon', '$wform_welcomeurl', '$wform_responseurl')"; 

                    }

                    $wquery = "insert into log (dateincl, idagent, ipuser, iduser, idchannel, operation, param) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', '$xidchannel', 2204, '$wform_tag')";
                    $wres   = mysql_query($wquery,$wsystem_dbid);

                    $widkey = newkey($xidchannel);

                    $wquery2 = "update channels set idkey = '$widkey' where idchannel = '$xidchannel'";
                    $wres2   = mysql_query($wquery2,$wsystem_dbid);

                    $wquery = "insert into log (dateincl, idagent, ipuser, iduser, idchannel, operation, param) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', '$xidchannel', 2603, '$widkey')";
                    $wres   = mysql_query($wquery,$wsystem_dbid);

                }

                else
                {

                    $wquery2 = "update channels set tag = '$wform_tag', briefing = '$wform_briefing', welcome = '$wform_welcome', channeltype = '$wform_chntype', privcode = '$wform_privcode', welcomeurl = '$wform_welcomeurl', responseurl = '$wform_responseurl'
                                 where idchannel = '$widchannel'";

                    $wres2   = mysql_query($wquery2,$wsystem_dbid);

                    $wquery = "insert into log (dateincl, idagent, ipuser, iduser, idchannel, operation, param) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', '$widchannel', 2205, '$wform_tag')";
                    $wres   = mysql_query($wquery,$wsystem_dbid);

                }

                header("Location: chns");
                exit;

            }

        }

    }

    else
    {

        $wquery = "insert into log (dateincl, idagent, ipuser, iduser, idchannel, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', '$widchannel', 2201)";
        $wres   = mysql_query($wquery,$wsystem_dbid);
        
    }

    include("mod/head.php");

    echo    '<div style="background-color: #762A50; text-align: center; color: #fff; font-size: 20px; padding: 10px;">Canais (desenvolvedores)</div>';

    echo    '<div class="box" style="width: 40%;">';

    if     ( $wmsg == "" and $widchannel == "new" ) echo '<span class="msg1">Informe os dados no novo canal:</span>';
    elseif ( $wmsg == "" )                          echo '<span class="msg1">Altere as informações do canal:</span>';
    else                                            echo '<span class="msg1" style="color: red;">' . $wmsg . '</span>';

    echo        '<form method="post" action="." name="chnedit_form" id="chnedit_form" onsubmit="savePosition(this)">';

    echo            '<input type="hidden" name="oper" id="oper" value="chnedit_go">';
    echo            '<input type="hidden" name="posx" id="posx" value="0">';
    echo            '<input type="hidden" name="posy" id="posy" value="0">';

    echo            '<div style="text-align: left; padding-left: 24px;">';
    echo                '<span class="label1">' . $wcabtag . '</span>';
    echo                '<input class="input1" name="chnedit_tag" type="text" maxlength="32" aria-required="true" spellcheck="false" value="' . $wform_tag . '">';
    echo            '</div>';

    echo            '<div style="text-align: left; padding-left: 24px;">';
    echo                '<span class="label1">Texto de apresentação (200 chars):</span>';
    echo                '<textarea class="text1" maxlength="200" name="chnedit_briefing" rows="5" aria-required="true" spellcheck="false">' . $wform_briefing . '</textarea>';
    echo            '</div>';

    echo            '<div style="text-align: left; padding-left: 24px;">';
    echo                '<span class="label1">Mensagem de boas vindas (200 chars):</span>';
    echo                '<textarea class="text1" maxlength="200" name="chnedit_welcome" rows="5" aria-required="true" spellcheck="false">' . $wform_welcome . '</textarea>';
    echo            '</div>';

    echo            '<div style="text-align: left; padding-left: 24px;">';
    echo                '<span class="label1">URL de destino da mensagem de boas vindas (http)</span>';
    echo                '<input class="input1" name="chnedit_welcomeurl" type="text" maxlength="200" aria-required="true" placeholder="" spellcheck="false" value="' . $wform_welcomeurl . '">';
    echo            '</div>';

    echo            '<div style="text-align: left; padding-left: 24px;">';
    echo                '<span class="label1">URL para mensagens de retorno (http)</span>';
    echo                '<input class="input1" name="chnedit_responseurl" type="text" maxlength="200" aria-required="true" placeholder="" spellcheck="false" value="' . $wform_responseurl . '">';
    echo            '</div>';

    echo            '<div style="text-align: center; vertical-align: middle;">';

    if ( $wform_chntype == "r" ) 
         echo           '<input type="checkbox" name="chnedit_chntype" value="ok" onclick="toggle_visibility(\'pcode\')" checked>';

    else echo           '<input type="checkbox" name="chnedit_chntype" value="ok" onclick="toggle_visibility(\'pcode\')">';

    echo                '<span style="font-size: 16px;">&nbsp;&nbsp;Canal&nbsp;Privado</span>';

    echo            '</div>';

    if ( $wform_chntype == "r" ) $wstyle = "block";
    else                         $wstyle = "none";

    echo            '<div id="pcode" style="width: 200px; margin: 0 auto; display: ' . $wstyle . ';">';
    echo                '<span class="label1"><br>Private Code (opcional)<br></span>';
    echo                '<input class="input1" style="text-transform: uppercase;" name="chnedit_privcode" type="text" maxlength="8" aria-required="true" placeholder="" spellcheck="false" value="' . $wform_privcode . '">';
    echo            '</div>';

    echo            '<div style="margin-top: 20px;">';
    echo                '<button class="button1" type="submit" name="chnedit_go">Enviar</button>';
    echo            '</div>';

    echo            '<div>';
    echo                '<a class="button4" href="chns">Cancelar</a>';
    echo            '</div>';

    echo        '</form>';

    echo    '</div>';

    include("mod/tail.php");

?>

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

    $wtaboper = array ( "0"    => "",
                        "1001" => "duplicated key",                  
                        "1002" => "agent found",                            
                        "1003" => "new agent",                            
                        "1004" => "send-mail",                            
                        "1005" => "opt-out",                            

                        "1101" => "home",                            
                        "1102" => "help",                            
                        "1103" => "download pdf",

                        "1201" => "login: start",                    
                        "1202" => "login: invalid user",             
                        "1203" => "login: empty pass",             
                        "1204" => "login: wrong pass",             
                        "1205" => "login: ok",                       
                        "1206" => "login: admin",                      
                        "1207" => "login: early try",                      
                        "1208" => "logout: ok",                      

                        "1301" => "signup: start",                   
                        "1302" => "signup: invalid email",           
                        "1303" => "signup: invalid name",            
                        "1304" => "signup: invalid pass",            
                        "1305" => "signup: terms not accepted",      
                        "1306" => "signup: email already exist",     
                        "1307" => "signup: regstatus denied",        
                        "1308" => "signup: user included",           
                        "1309" => "signup: user re-included",        
                        "1310" => "signup: user confirmed",          

                        "1401" => "rescue: start",                   
                        "1402" => "rescue: request sent",         
                        "1403" => "rescue: rescue confirmed",        
                        "1404" => "rescue: invalid email",           
                        "1405" => "rescue: invalid pass",            
                        "1406" => "rescue: pass changed",            

                        "1501" => "contact: start",                  
                        "1502" => "contact: invalid name",           
                        "1503" => "contact: invalid email",          
                        "1504" => "contact: msg empty",              
                        "1505" => "contact: msg sent",               

                        "1601" => "profile: start",                  
                        "1602" => "profile: invalid name",           
                        "1603" => "profile: invalid pass",           
                        "1604" => "profile: profile changed",        

                        "1701" => "clear: start",                  
                        "1702" => "clear: invalid iduser",           
                        "1703" => "clear: invalid pass",           
                        "1704" => "clear: profile clear",        

                        "2101" => "home channels",                   

                        "2201" => "channel edit: start",          
                        "2202" => "channel edit: invalid tag",      
                        "2203" => "channel edit: duplicate tag",     
                        "2204" => "channel edit: insert ok",       
                        "2205" => "channel edit: update ok",       
                        "2206" => "channel edit: invalid privcode",  
                        "2207" => "channel edit: tag upon request",     
                        "2208" => "channel edit: tag reserved",     
                        "2209" => "channel edit: invalid tag (zup)",      
                        "2210" => "channel edit: urls fixed",      

                        "2301" => "channel swap: activate",            
                        "2302" => "channel swap: suspend",         

                        "2401" => "channel excl: start",            
                        "2402" => "channel excl: wrong id",       
                        "2403" => "channel excl: done",          

                        "2501" => "channel upld: start",            
                        "2502" => "channel upld: invalid file",  
                        "2503" => "channel upld: upload ok",         

                        "2601" => "channel newk: start",            
                        "2602" => "channel newk: wrong id",       
                        "2603" => "channel newk: key generated",    

                        "2701" => "channel dowk: download ok",       

                        "2702" => "channel send: start",       
                        "2703" => "channel send: invalid userid/email",       
                        "2704" => "channel send: invalid enqueteid",       
                        "2705" => "channel send: message empty",       
                        "2706" => "channel send: message sent",       

                        "2801" => "channel town: start",            
                        "2802" => "channel town: invalid email",    
                        "2803" => "channel town: canceled",         
                        "2804" => "channel town: registered",        
                        "2805" => "channel town: confirmed",        

                        "2901" => "channel logs: show",       
                        "2902" => "channel stat: calculate",       
                        "2903" => "channel stat: show",       

                        "3101" => "home subscriptions",                   

                        "3201" => "unsubscribe: start",            
                        "3202" => "unsubscribe: done",          

                        "3301" => "search: start",          
                        "3302" => "search: invalid param",          
                        "3303" => "search: submit",          
                        "3304" => "search: first",          
                        "3305" => "search: previous",          
                        "3306" => "search: next",          
                        "3307" => "search: last",          

                        "3401" => "subscribe: start",          
                        "3402" => "subscribe: done",
                        "3403" => "subscribe: wrong privcode",          
                        "3404" => "subscribe: limit reached",          

                        "3501" => "home devices",                

                        "3601" => "device delete: start",            
                        "3602" => "device delete: done",          
                        "3603" => "device delete: last 15 days start",          
                        "3604" => "device delete: last 15 days done",          

                        "3701" => "device edit: start",            
                        "3702" => "device edit: done",          
                        "3703" => "device edit: invalid tag",          
                        "3704" => "device edit: duplicate tag",          

                        "4101" => "home enquetes",

                        "4201" => "enquete edit: start",          
                        "4202" => "enquete edit: wrong choices",          
                        "4203" => "enquete edit: invalid question",          
                        "4204" => "enquete edit: invalid term",          
                        "4205" => "enquete edit: insert ok",          
                        "4206" => "enquete edit: update ok",          

                        "4301" => "enquete delete: start",          
                        "4302" => "enquete delete: wrong id",          
                        "4303" => "enquete delete: done",          

                        "4401" => "enquete conclude: start",          
                        "4402" => "enquete conclude: wrong id",          
                        "4403" => "enquete conclude: done",          

                        "4501" => "enquete score: start",          
                        "4502" => "enquete score: sum",          

                        "5101" => "home interfaces",

                        "5201" => "interface new: ok",

                        "5301" => "interface excl: start",            
                        "5302" => "interface excl: wrong id",       
                        "5303" => "interface excl: done",

                        "5401" => "interface show stamp: ok",       

                        "5501" => "interface show log: ok",       

                        "5601" => "interface emulator: start",       

                        "6101" => "channel: invalid sequence",      
                        "6102" => "channel: invalid key/frame",            
                        "6103" => "channel: invalid command",              
                        "6104" => "channel: invalid idchannel",            
                        "6105" => "channel: invalid date", 
                        "6106" => "channel: invalid iduser", 
                        "6107" => "channel: invalid idmessage", 
                        "6108" => "channel: null message",                 
                        "6109" => "channel: big iduser list",                 
                        "6110" => "channel: invalid idenquete",                 
                        "6111" => "channel: get response ok",
                        "6112" => "channel: get response error idchannel",
                        "6113" => "channel: get response empty",

                        "6201" => "channel: lsub since", 
                        "6202" => "channel: lsub subscode", 
                        "6203" => "channel: lsub nosubs", 
                        "6204" => "channel: lsub wrong", 
                        "6221" => "channel: smsg pais/idioma",
                        "6222" => "channel: smsg list",
                        "6231" => "channel: lmsg idmessage",
                        "6232" => "channel: lmsg iduser",
                        "6233" => "channel: lmsg since",
                        "6234" => "channel: lmsg nosubs",
                        "6241" => "channel: cmsg all",
                        "6242" => "channel: cmsg iduser",
                        "6243" => "channel: cmsg message",
                        "6244" => "channel: cmsg nosubs",
                        "6245" => "channel: cmsg message nosubs",
                        "6246" => "channel: cmsg message sent",

                        "7101" => "device: invalid sequence",
                        "7102" => "device: invalid key/frame",
                        "7103" => "device: invalid command",
                        "7104" => "device: invalid idinterface/iddevice",
                        "7105" => "device: invalid stamp",
                        "7106" => "device: invalid install frame",
                        "7107" => "device: invalid install email",
                        "7108" => "device: invalid install password",
                        "7109" => "device: device registered",
                        "7110" => "device: device unregistered",
                        "7111" => "device: idinterface maxusers",
                        "7112" => "device: anonymous user/device",

                        "7211" => "device: check new msgs",
                        "7212" => "device: check re-send",
                        "7221" => "device: list channels",
                        "7231" => "device: get icon",
                        "7232" => "device: get icon invalid tag",
                        "7241" => "device: search channels",                            
                        "7242" => "device: search invalid tag",
                        "7251" => "device: subscribe channel",                        
                        "7252" => "device: subscribe invalid tag",
                        "7253" => "device: subscribe invalid privcode",                        
                        "7254" => "device: subscribe limit reached",                        
                        "7261" => "device: unsubscribe channel",                              
                        "7262" => "device: unsubscribe invalid tag",
                        "7271" => "device: send response ok",                  
                        "7272" => "device: send response empty",                  
                        "7273" => "device: send response invalid tag",
                        "7274" => "device: send response no url",
                        "7275" => "device: send response error",
                        "7281" => "device: delete message",
                        "7282" => "device: invalid idmessage",

                        "7301" => "device: get enquete",
                        "7302" => "device: get enquete invalid idmessage",
                        "7303" => "device: get enquete invalid enquete",
                        "7304" => "device: get enquete time limit",
                        "7305" => "device: get enquete already done",
                        "7311" => "device: votar enquete",
                        "7312" => "device: votar enquete invalid idmessage",
                        "7313" => "device: votar enquete invalid enquete",
                        "7314" => "device: votar enquete time limit",
                        "7315" => "device: votar enquete already done",
                        "7316" => "device: votar enquete invalid choice");

#   ==================================================================================================================================================

    if ( $wadmin != "s" ) 
    {

        if ( $widuser == "z00000000000" )
        {
            $_SESSION["admin"] = "s";
            session_write_close();
        }

        header("Location: .");
        exit;

    }

    if ( isset($_POST["return"]) )
    {
        session_destroy();
        header("Location: .");
        exit;
    }

    $wsql_query = "";

    $work = "";

    if ( isset($_POST["sql_query"]) ) $work = stripslashes($_POST["sql_query"]);
    
    if ( isset($_POST["sql_query_go"]) and $work != "" ) $wsql_query = $work;

    if ( isset($_POST["whois_go"]) or isset($_POST["mala_go"]) ) $wsql_query = "";

    elseif ( $wsql_query == "" ) $wsql_query = "select * from log order by idlog desc limit 200";

    echo    '<!DOCTYPE HTML>';

    echo    '<html>';

    echo        '<head>';
    echo            '<title>Followzup</title>';
    echo            '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
    echo            '<meta name="description" content="Followzup - Business Messaging">';
    echo            '<meta name="author" content="Followzup Team">';
    echo            '<meta name="keywords" content="content="b2p, p2p, message, messaging, communication, batch, bulk, business, sms">';
    echo            '<link rel="shortcut icon" href="img/favicon.gif">';
    echo            '<link rel="stylesheet" href="css/normalize.css">';
    echo            '<link rel="stylesheet" href="css/followzup.css?version=1">';
    echo            '<script src="css/prefixfree.min.js"></script>';
    echo            '<script src="css/savepos.js"></script>';
    echo            '<script type="text/javascript" src="followzup.js"></script>';
    echo        '</head>';

    echo        '<body>';

    echo            '<table>';
    echo                '<tr>';
    echo                    '<td style="background-color: #FFCC99;">';
    echo                        '<form name="form_sql" method="post" action="." style="display: inline;" enctype="multipart/form-data">';
    echo                            '<table>';
    echo                                '<tr>';
    echo                                    '<td style="width: 1px; vertical-align: middle;"><b>&nbsp;SQL:&nbsp;</b></td>';
    echo                                    '<td><input type="text" style="width: 100%; font-size:10pt; font-family: Monospace;" ';
    echo                                        'name="sql_query" maxlength="2000" value="' . $wsql_query . '"></td>';
    echo                                    '<td style="width: 1px;"><input type="submit" style="font-size:8pt;" name="sql_query_go" value=">>"></td>';
    echo                                    '<td style="width: 1px;"><input type="submit" style="font-size:8pt;" name="return" value="Logout"></td>';
    echo                                '</tr>';
    echo                            '</table>';
    echo                        '</form>';
    echo                    '</td>';
    echo                '</tr>';
    echo            '</table>';

    $resx  = mysql_query($wsql_query,$wsystem_dbid);
    $winfo = mysql_info();
    $werro = mysql_error();
    $werrn = mysql_errno();

    if ( $werro != "" )
    {
        echo        '<table>';
        echo            '<tr>';
        echo                '<td style="padding: 15px; background-color: #FFCC99;">Error: ' . $werro . ' (' . $werrn . ')</td>';
        echo            '</tr>';
        echo        '</table>';
    }

    else
    {

        $numregx = intval(mysql_num_rows($resx));
        $numcolx = intval(mysql_num_fields($resx));

        $wflag_oper = 999;
        $wflag_cseq = 999;
        $wflag_dseq = 999;
        $wflag_agen = 999;

        echo        '<table>';
        echo            '<tr>';

        for ( $indexx = 0; $indexx < $numcolx; $indexx = $indexx+1 )
        {

            if ( $indexx % 2 == 0 ) $wbg = "#eee";
            else                    $wbg = "#fff";

            $wfield = mysql_field_name($resx, $indexx);

            echo            '<td style="width: 1px; background-color: ' . $wbg . '; padding-bottom: 10px; padding-top: 10px; border-bottom: 1px solid #999; border-top: 1px solid #999;"><b>' . $wfield . '</b></td>';

            if     ( $wfield == "operation" )  $wflag_oper = $indexx;
            elseif ( $wfield == "channelseq" ) $wflag_cseq = $indexx;
            elseif ( $wfield == "deviceseq" )  $wflag_dseq = $indexx;
            elseif ( $wfield == "idagent" )    $wflag_agen = $indexx;

        }

        echo                '<td style="border-left: 1px solid #999;">&nbsp;</td>';
        echo            '</tr>';

        for ( $index=0; $index<$numregx; $index=$index+1 )
        {

            $vetorx = mysql_fetch_row($resx);
            echo        '<tr>';

            for ( $indexx = 0; $indexx < $numcolx; $indexx = $indexx+1 )
            {

                if ( $indexx % 2 == 0 ) $wbg = "#eee";
                else                    $wbg = "#fff";

                $wfield = $vetorx[$indexx];

                if     ( $indexx == $wflag_oper )                    $wfield = $wfield . ' - ' . $wtaboper[$wfield];
                elseif ( $indexx == $wflag_cseq and $wfield == "0" ) $wfield = "&nbsp;";
                elseif ( $indexx == $wflag_dseq and $wfield == "0" ) $wfield = "&nbsp;";
                elseif ( $indexx == $wflag_agen and $wfield == "0" ) $wfield = "&nbsp;";

                echo        '<td style="background-color: ' . $wbg . '; border-bottom: 1px solid #999; white-space: nowrap;">' . $wfield . '</td>';

            }

            echo            '<td style="border-left: 1px solid #999;">&nbsp;</td>';
            echo        '</tr>';

        }

        echo        '</table>';

    }

    echo        '</body>';

    echo    '</html>';

?>

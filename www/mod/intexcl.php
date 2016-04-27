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

    if ( $widuser != "z00000000000" )
    {
        header("Location: .");
        exit;
    }

    if ( isset($_GET["int"]) ) $_SESSION["idinterface"] = preg_replace('/[^a-z0-9]/',"",$_GET["int"]);
    
    elseif ( !isset($_SESSION["idinterface"]) or !isset($_POST["intexcl_go"]) )
    {
        header("Location: .");
        exit;
    }

    $widinterface = $_SESSION["idinterface"];

    $wquery1 = "select idinterface
                  from interfaces
                 where idinterface = '$widinterface' and iduser = '$widuser' and regstatus != 'a'";

    $wres1    = mysql_query($wquery1,$wsystem_dbid);
    $wnumreg1 = mysql_num_rows($wres1);

    if ( $wnumreg1 == 0 )
    {
        header("Location: ints");
        exit;
    }

    if ( isset($_POST["intexcl_go"]) )
    {
        
        $wform_idinterface = cleanText($_POST["intexcl_idinterface"]);

        if ( strtolower($wform_idinterface) != $widinterface )
        {

            $wmsg = "Confirme a INTERFACE-ID a ser excluída.";

            $wquery = "insert into log (dateincl, idagent, ipuser, iduser, operation, param) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', 5302, '$wform_idinterface')";
            $wres   = mysql_query($wquery,$wsystem_dbid);

        }

        else
        {        

            $wquery2 = "update interfaces set regstatus = 'd' where idinterface = '$widinterface'";
            $wres2   = mysql_query($wquery2,$wsystem_dbid);

            $wquery = "insert into log (dateincl, idagent, ipuser, iduser, operation, param) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', 5303, '$widinterface')";
            $wres   = mysql_query($wquery,$wsystem_dbid);

            header("Location: ints");
            exit;

        }

    }

    else
    {

        $wquery = "insert into log (dateincl, idagent, ipuser, iduser, operation, param) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', 5301, '$widinterface')";
        $wres   = mysql_query($wquery,$wsystem_dbid);
        
    }

    include("mod/head.php");

    echo    '<div style="background-color: #762A50; text-align: center; color: #fff; font-size: 20px; padding: 10px;">Interface (desenvolvedores)</div>';

    echo    '<div class="box">';

    echo        '<span class="msg1">Excluir Interface</span>';

    if ( $wmsg == "" ) echo '<span class="msg1">Confirme a INTERFACE-ID a ser excluída.</span>';
    else               echo '<span class="msg1" style="color: red;">' . $wmsg . '</span>';

    echo        '<form method="post" action="." name="intexcl_form" id="intexcl_form" onsubmit="savePosition(this)">';

    echo            '<input type="hidden" name="oper" id="oper" value="intexcl_go">';
    echo            '<input type="hidden" name="posx" id="posx" value="0">';
    echo            '<input type="hidden" name="posy" id="posy" value="0">';

    echo            '<div>';
    echo                '<input class="input1" style="margin: 0 auto; background-color: #ffdddd; width: 150px;" name="intexcl_idinterface" type="text" maxlength="12" aria-required="true" placeholder="ID" spellcheck="false" value="">';
    echo            '</div>';

    echo            '<div>';
    echo                '<button class="button1" type="submit" name="intexcl_go">Confirmar</button>';
    echo            '</div>';

    echo            '<div>';
    echo                '<a class="button4" href="ints">Cancelar</a>';
    echo            '</div>';

    echo        '</form>';

    echo    '</div>';

    include("mod/tail.php");

?>

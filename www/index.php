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

    session_start();

    include("mod/functions.php");
    include("mod/access.php");
    include("mod/config.php");

    if     ( isset($_GET["wschannel"]) )     $wop = "wschannel";
    elseif ( isset($_GET["wsdevice"]) )      $wop = "wsdevice";
    elseif ( isset($_GET["oper"]) )          $wop = preg_replace("/[^A-Za-z0-9]\_/","",$_GET["oper"]);
    elseif ( isset($_POST["oper"]) )         $wop = preg_replace("/[^A-Za-z0-9]\_/","",$_POST["oper"]);
    elseif ( isset($_GET["confsign"]) )      $wop = "signup_conf";
    elseif ( isset($_GET["confreco"]) )      $wop = "rescue_conf";
    elseif ( isset($_GET["conftown"]) )      $wop = "chntown_conf";
    elseif ( $wadmin == "s" )                $wop = "admin";
    else                                     $wop = "home";

    if     ( $wop == "wschannel" )           include("mod/wschannel.php");
    elseif ( $wop == "wsdevice" )            include("mod/wsdevice.php");
    elseif ( $wop == "admin" )               include("mod/admin.php");
    elseif ( $wop == "logout" )              include("mod/logout.php");
    elseif ( $wop == "login" )               include("mod/login.php");
    elseif ( $wop == "login_go" )            include("mod/login.php");
    elseif ( $wop == "signup" )              include("mod/signup.php");
    elseif ( $wop == "signup_go" )           include("mod/signup.php");
    elseif ( $wop == "signup_conf" )         include("mod/signup_conf.php");
    elseif ( $wop == "rescue" )              include("mod/rescue.php");
    elseif ( $wop == "rescue_go" )           include("mod/rescue.php");
    elseif ( $wop == "rescue_conf" )         include("mod/rescue_conf.php");
    elseif ( $wop == "rescue_conf_go" )      include("mod/rescue_conf.php");
    elseif ( $wop == "profile" )             include("mod/profile.php");
    elseif ( $wop == "profile_go" )          include("mod/profile.php");
    elseif ( $wop == "contact" )             include("mod/contact.php");
    elseif ( $wop == "contact_go" )          include("mod/contact.php");
    elseif ( $wop == "chns" )                include("mod/chns.php");
    elseif ( $wop == "chnedit" )             include("mod/chnedit.php");
    elseif ( $wop == "chnedit_go" )          include("mod/chnedit.php");
    elseif ( $wop == "chnswap" )             include("mod/chnswap.php");
    elseif ( $wop == "chnexcl" )             include("mod/chnexcl.php");
    elseif ( $wop == "chnexcl_go" )          include("mod/chnexcl.php");
    elseif ( $wop == "chnnewk" )             include("mod/chnnewk.php");
    elseif ( $wop == "chnnewk_go" )          include("mod/chnnewk.php");
    elseif ( $wop == "chndowk" )             include("mod/chndowk.php");
    elseif ( $wop == "chntown" )             include("mod/chntown.php");
    elseif ( $wop == "chntown_go" )          include("mod/chntown.php");
    elseif ( $wop == "chntown_conf" )        include("mod/chntown_conf.php");
    elseif ( $wop == "chntown_conf_go" )     include("mod/chntown_conf.php");
    elseif ( $wop == "chnupld" )             include("mod/chnupld.php");
    elseif ( $wop == "chnupld_go" )          include("mod/chnupld.php");
    elseif ( $wop == "chnsend" )             include("mod/chnsend.php");
    elseif ( $wop == "chnsend_go" )          include("mod/chnsend.php");
    elseif ( $wop == "help" )                include("mod/help.php");
    elseif ( $wop == "devs" )                include("mod/devs.php");
    elseif ( $wop == "devexcl" )             include("mod/devexcl.php");
    elseif ( $wop == "devexcl_go" )          include("mod/devexcl.php");
    elseif ( $wop == "devedit" )             include("mod/devedit.php");
    elseif ( $wop == "devedit_go" )          include("mod/devedit.php");
    elseif ( $wop == "devureg" )             include("mod/devureg.php");
    elseif ( $wop == "devureg_go" )          include("mod/devureg.php");
    elseif ( $wop == "ints" )                include("mod/ints.php");
    elseif ( $wop == "intnew" )              include("mod/ints.php");
    elseif ( $wop == "intexcl" )             include("mod/intexcl.php");
    elseif ( $wop == "intexcl_go" )          include("mod/intexcl.php");
    elseif ( $wop == "intstamp" )            include("mod/intstamp.php");
    else                                     include("mod/home.php");

?>

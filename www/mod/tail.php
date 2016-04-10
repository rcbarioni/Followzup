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

    echo            '</div>';

    echo            '<div id="footer" style="background: linear-gradient(to top, 30%, 100%);">';

    echo                '<table>';
    echo                    '<tr>';

    echo                        '<td style="width: 50%;">';
    echo                            '<table>';
    echo                                '<tr>';

    echo                                    '<td style="width: 1%; white-space: nowrap; padding: 30px;">';
    echo                                        '<a class="refoot" href=".">início</a><br>';
    echo                                        '<a class="refoot" href="help_' . $whelp . '">ajuda</a><br>';
    echo                                        '<a class="refoot" href="contact">contato</a><br>';
    echo                                        '<a class="refoot" href="signup">nova&nbsp;conta</a><br>';
    echo                                    '</td>';

    echo                                    '<td style="text-align: center; vertical-align: middle; padding: 30px;">';
    echo                                        '<a href="."><img alt="followzup" src="img/followzup_banner_215.png" style="padding: 5px; background-color: #fff;"></a><br>';
    echo                                    '</td>';

    echo                                    '<td style="text-align: center; vertical-align: middle; padding: 30px;">';
    echo                                        '<span style="color: #999; font-size: 10px;">&copy; Copyright&nbsp;2016&nbsp;-&nbsp;followzup.com</span><br>';

    if ( $widuser != "" ) echo                  '<span style="color: #bbb; font-size: 13px; font-family: monospace;">User-ID: ' . $widuser . '<br>' . $wemail . '</span>';

    echo                                    '</td>';

    echo                                '</tr>';
    echo                            '</table>';
    echo                        '</td>';

    echo                        '<td style="width: 50%; padding: 20px; vertical-align: middle;">';
    echo                            '<table style=" background-color: #2a3670"">';
    echo                                '<tr>';
    
    echo                                    '<td style="white-space: nowrap; padding: 20px;">';
    echo                                        '<a class="refoot" href="help_in">Bem-vindo ao Followzup</a><br>';
    echo                                        '<a class="refoot" href="help_aa">Adaptar aplicações</a><br>';
    echo                                    '</td>';
 
    echo                                    '<td style="white-space: nowrap; padding: 20px;">';
    echo                                        '<a class="refoot" href="help_cs">Canais e assinaturas</a><br>';
    echo                                        '<a class="refoot" href="help_sm">Enviar mensagens (smsg)</a><br>';
    echo                                    '</td>';

    echo                                    '<td style="white-space: nowrap; padding: 20px;">';
    echo                                        '<a class="refoot" href="help_us">Verificar usuários (chck)</a><br>';
    echo                                        '<a class="refoot" href="help_rs">Interações do assinante</a><br>';
    echo                                        '<a class="refoot" href="help_tu">Termos de uso</a><br>';
    echo                                    '</td>';
 
    echo                                '</tr>';
    echo                            '</table>';
    echo                        '</td>';

    echo                    '</tr>';
    echo                '</table>';

    echo            '</div>';

    echo            '<script type="text/javascript"> window.scrollTo( ' . $wposx . ' , ' . $wposy . ' ); </script>';

    echo        '</body>';

    echo    '</html>';

    session_write_close();

?>

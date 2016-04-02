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

    $wquery = "insert into log (dateincl, idagent, ipuser, iduser, operation) values (utc_timestamp(), '$widagent', '$wipuser', '$widuser', 1101)";
    $wres   = mysql_query($wquery,$wsystem_dbid);

    include('mod/head.php');

    echo '<div style="background-color: #2a3670; text-align: center; color: #fff; font-size: 20px; padding: 10px;">Followzup</div>';

    echo    '<table style="background-image: url(img/fullmoon.jpg); background-repeat: no-repeat; background-size: 100% auto;">';

    echo        '<tr>';
    echo            '<td colspan=3 style="padding: 50px;">';
    echo                '<table>';

    echo                    '<tr>';
    echo                        '<td style="width: 50%; text-align: center; color: #333; padding: 80px; background: rgba(255,255,255,0.2);">';
    echo                            '<span style="font-size: 42px;">Para usuários:</span>';
    echo                            '<span style="font-size: 32px;"><br><br>Instale o aplicativo Followzup em seu dispositivo móvel e receba apenas as mensagens de quem você quer. Com segurança e privacidade.</span>';
    echo                        '</td>';
    echo                        '<td style="width: 50%; text-align: center; color: #EEE; padding: 80px; background: rgba(0,0,0,0.6);">';
    echo                            '<span style="font-size: 42px;">Para desenvolvedores:</span>';
    echo                            '<span style="font-size: 32px;"><br><br>Envie mensagens "em lote" de seu site Internet ou sistema corporativo, para o celular de seus usuários.<br>Tudo criptografado.</span>';
    echo                        '</td>';
    echo                    '</tr>';

    echo                '</table>';
    echo            '</td>';
    echo        '</tr>';

    echo        '<tr>';
    echo            '<td colspan=3 style="padding: 100px; background: white;">';
    echo                '<table>';
    echo                    '<tr>';

    echo                        '<td style="vertical-align: middle; width: 1px; padding: 20px; text-align: center;">';
    echo                            '<img class="roundcorner2" alt="applications" src="img/icon_server.png" width="150" height="150"><span style="color: #666;">sistemas&nbsp;corporativos</span>';
    echo                        '</td>';

    echo                        '<td style="vertical-align: middle; width: 1px; padding: 20px; white-space: nowrap;">';
    echo                             '<span style="color: #999; font-size: 50px;">>></span>';
    echo                        '</td>';

    echo                       '<td style="padding: 10px 0px; text-align: center; vertical-align: middle;">';

    echo                            '<a href="help_in" style="font-size: 33px; text-decoration: none; color: #888; padding: 15px; white-space: nowrap;">agendamentos</a> ';
    echo                            '<a href="help_in" style="font-size: 28px; text-decoration: none; color: #555; padding: 15px; white-space: nowrap;">monitor de sistemas</a> ';
    echo                            '<a href="help_in" style="font-size: 30px; text-decoration: none; color: #777; padding: 15px; white-space: nowrap;">avisos de incidentes</a> ';
    echo                            '<a href="help_in" style="font-size: 37px; text-decoration: none; color: #666; padding: 15px; white-space: nowrap;">movimentações financeiras</a> ';
    echo                            '<a href="help_in" style="font-size: 32px; text-decoration: none; color: #444; padding: 15px; white-space: nowrap;">clipping de notícias</a> ';
    echo                            '<a href="help_in" style="font-size: 25px; text-decoration: none; color: #888; padding: 15px; white-space: nowrap;">confirmações</a> ';
    echo                            '<a href="help_in" style="font-size: 28px; text-decoration: none; color: #555; padding: 15px; white-space: nowrap;">status de serviços</a> ';
    echo                            '<a href="help_in" style="font-size: 32px; text-decoration: none; color: #777; padding: 15px; white-space: nowrap;">alarmes</a> ';
    echo                            '<a href="help_in" style="font-size: 35px; text-decoration: none; color: #666; padding: 15px; white-space: nowrap;">acompanhamento de processos</a> ';
    echo                            '<a href="help_in" style="font-size: 25px; text-decoration: none; color: #444; padding: 15px; white-space: nowrap;">rastreamento de encomendas</a> ';
    echo                            '<a href="help_in" style="font-size: 30px; text-decoration: none; color: #555; padding: 15px; white-space: nowrap;">pontos de controle</a> ';

    echo                      '</td>';

    echo                        '<td style="vertical-align: middle; width: 1px; padding: 20px; white-space: nowrap;">';
    echo                            '<span style="color: #999; font-size: 50px;">>></span>';
    echo                        '</td>';

    echo                        '<td style="vertical-align: middle; width: 1px; padding: 20px; text-align: center;">';
    echo                            '<img class="roundcorner2" alt="devices" src="img/icon_device.png" width="150" height="150"><span style="color: #666;">dispositivos&nbsp;móveis</span>';
    echo                      '</td>';

    echo                    '</tr>';
    echo                '</table>';
    echo            '</td>';
    echo        '</tr>';

    echo        '<tr>';
    echo            '<td colspan="3" style="padding: 90px; background-color: orange; text-align: center;">';
    echo                '<span style="font-size: 66px; color: white;">Apenas 1 linha de comando';
    echo                '<span style="font-size: 14px; vertical-align: super;">(*)</span>...</span>';
    echo                '<span style="font-size: 36px; color: white;">';
    echo                '<br><br>...é tudo que você precisa para que seu website ou sistema corporativo envie mensagens para dispositivos móveis por meio do Followzup. ';
    echo                'Crie seu canal de informações e veja como é fácil manter seus usuários e gestores sempre bem informados.</span>';
    echo                '<span style="font-size: 16px; color: white;">';
    echo                '<br><br><br>(*) Classes e métodos de apoio para envio de mensagens já disponíveis na API (PHP e Java). É só baixar e usar.</span>';
    echo            '</td>';
    echo        '</tr>';

    echo        '<tr>';

    echo            '<td style="width: 33%; padding: 40px; background: linear-gradient(to bottom, #1a2660 60%, black 100%);">';
    echo                '<span style="font-size: 28px; color: #eee;">Comunicação&nbsp;criptografada</span>';
    echo                '<span style="font-size: 18px; color: #eee;"><br><br><br>';
    echo                    '<img class="roundcorner2" alt="followzup" src="img/icon_safe.png" width="80" height="80" style="margin-right: 20px; float: left;">';
    echo                    'As mensagens enviadas por meio do Followzup são criptografadas com os protocolos <b>AES</b> e <b>RSA</b>, garantindo confidencialidade em todo o trajeto percorrido pela informação.';
    echo                    '<br><br>Cada aplicação e cada dispositivo móvel possui seu próprio par de chaves assimétricas RSA, e apenas o webservice Followzup pode descriptografar e ter acesso ao conteúdo das requisições.';
    echo                    '<br><br><a href="help_in" style="color: #fff;"><u>Clique aqui</u></a> e saiba mais sobre o Followzup e como ele pode incrementar a segurança que você precisa nas comunicações pela Internet.<br><br>';
    echo                '</span>';
    echo            '</td>';

    echo            '<td style="width: 33%; padding: 40px; background: linear-gradient(to bottom, #1a2660 60%, black 100%);">';
    echo                '<span style="font-size: 28px; color: #eee;">Processamento&nbsp;em&nbsp;lote</span>';
    echo                '<span style="font-size: 18px; color: #eee;"><br><br><br>';
    echo                    '<img class="roundcorner2" alt="followzup" src="img/icon_business.png" width="80" height="80" style="margin-right: 20px; float: left;">';
    echo                    'Followzup implementa o modelo de comunicação <b>Business-to-People</b> entre sistemas e usuários, permitindo o envio de mensagens <b>em lote</b>, com adaptações mínimas em seu código. ';
    echo                    '<br><br>Não importa o tamanho da aplicação, tampouco se está publicada na Intranet ou na Internet. O webservice Followzup está disponível para todos os sistemas que necessitam se comunicar com seus usuários.';
    echo                    '<br><br><a href="help_in" style="color: #fff;"><u>Clique aqui</u></a> e saiba mais sobre o Followzup e como ele pode incrementar as interações entre sistemas e usuários.<br><br>';
    echo                '</span>';
    echo            '</td>';

    echo            '<td style="width: 33%; padding: 40px; background: linear-gradient(to bottom, #1a2660 60%, black 100%);">';
    echo                '<span style="font-size: 28px; color: #eee;">Protocolo&nbsp;aberto</span>';
    echo                '<span style="font-size: 18px; color: #eee;"><br><br><br>';
    echo                    '<img class="roundcorner2" alt="followzup" src="img/icon_open.png" width="80" height="80" style="margin-right: 20px; float: left;">';
    echo                    'Com o Followzup, desenvolvedores e fabricantes de equipamentos podem construir novas interfaces para, virtualmente, qualquer tipo de dispositivo ou sistema operacional conectado à Internet.';
    echo                    '<br><br>Para fazer isso, o Followzup foi projetado com <b>protocolo aberto</b>, o que permite sua interação com praticamente qualquer linguagem ou sistema operacional compatível com o webservice Followzup.';
    echo                    '<br><br><a href="help_in" style="color: #fff;"><u>Clique aqui</u></a> e saiba mais sobre o Followzup e como ele pode conectar aplicações e novos dispositivos de maneira simples.<br><br>';
    echo                '</span>';
    echo            '</td>';

    echo        '</tr>';
    echo        '<tr style="background-color: white;">';

    echo            '<td colspan=3 style="padding: 50px; padding-bottom: 20px;">';
    echo                '<table style="border: 2px solid #5a66a0;">';
    echo                    '<tr>';
    echo                        '<td style="padding: 20px;">';
    echo                            '<span style="font-size: 28px; color: #555;">Guia rápido para usuários</span>';
    echo                            '<span style="font-size: 16px; color: #555;"><br><br>';
    echo                                '<img class="roundcorner2" alt="followzup" src="img/icon_device.png" width="80" height="80" style="margin-right: 20px; float: left;">';
    echo                                'Siga as instruções ao lado para instalar o aplicativo de comunicação Followzup em seu dispositivo móvel.';
    echo                            '</span>';
    echo                        '</td>';
    echo                        '<td style="border: 1px solid #aaa; padding: 10px; text-align: left; vertical-align: middle; background-color: #5a66a0;"><span style="font-size: 32px; color: #fff;">1</span></td>';
    echo                        '<td style="width: 15%; border: 1px solid #aaa; padding: 15px; text-align: left; vertical-align: middle;">';
    echo                            '<span style="font-size: 16px; color: #555;">';
    echo                                '<a href="signup" style="color: #555;"><b>Clique aqui</b></a> para criar seu registro no Followzup, informado seu nome, seu e-mail, suas preferências e senha de acesso.';
    echo                            '</span>';
    echo                        '</td>';
    echo                        '<td style="border: 1px solid #aaa; padding: 10px; text-align: left; vertical-align: middle; background-color: #5a66a0;"><span style="font-size: 32px; color: #fff;">2</span></td>';
    echo                        '<td style="width: 15%; border: 1px solid #aaa; padding: 15px; text-align: left; vertical-align: middle;">';
    echo                            '<span style="font-size: 16px; color: #555;">';
    echo                                'Após preenchido, confirme seu registro por meio da mensagem de verificação encaminhada para seu e-mail.';
    echo                            '</span>';
    echo                        '</td>';
    echo                        '<td style="border: 1px solid #aaa; padding: 10px; text-align: left; vertical-align: middle; background-color: #5a66a0;"><span style="font-size: 32px; color: #fff;">3</span></td>';
    echo                        '<td style="width: 15%; border: 1px solid #aaa; padding: 15px; text-align: left; vertical-align: middle;">';
    echo                            '<span style="font-size: 16px; color: #555;">';
    echo                                'No seu celular, instale o aplicativo Followzup disponível no <b>Google-Play</b> e utilize seu login e senha ao iniciar o aplicativo.';
    echo                            '</span>';
    echo                        '</td>';
    echo                        '<td style="border: 1px solid #aaa; padding: 10px; text-align: left; vertical-align: middle; background-color: #5a66a0;"><span style="font-size: 32px; color: #fff;">4</span></td>';
    echo                        '<td style="width: 15%; border: 1px solid #aaa; padding: 15px; text-align: left; vertical-align: middle;">';
    echo                            '<span style="font-size: 16px; color: #555;">';
    echo                                'Dentro do aplicativo, pesquise, selecione e assine os canais de informações que deseja receber mensagens.';
    echo                            '</span>';
    echo                        '</td>';
    echo                    '</tr>';
    echo                '</table>';
    echo            '</td>';

    echo        '</tr>';

    echo    '</table>';

    include('mod/tail.php');

?>

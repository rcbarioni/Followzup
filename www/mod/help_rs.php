
<!--
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
-->

<h1>Interações do Assinante</h1>

<br><br>
<img class="roundcorner2" alt="followzup" src="img/home1.png" width="80" height="80" style="border: 1px solid #aaa;">
<img class="roundcorner2" alt="followzup" src="img/home3.png" width="80" height="80" style="border: 1px solid #aaa;">
<img class="roundcorner2" alt="followzup" src="img/home2.png" width="80" height="80" style="border: 1px solid #aaa;">
<img class="roundcorner2" alt="followzup" src="img/home4.png" width="80" height="80" style="border: 1px solid #aaa;">

<p> Existem 2 (duas) formas do usuário interagir com uma aplicação do qual é assinante, a partir de seu dispitivo móvel. São elas: 

<ul><li>Links associados às mensagens;</li>
<li>Envio de mensagens de retorno.</li></ul>

<p>Vamos explorar cada uma dessas formas de interação.

<br><br><h4>Links associados às mensagens</h4>

<p>Quando uma aplicação envia uma mensagem, pode-se, opcionalmente, informar um endereço HTTP associado à mesma. Nesse caso, quando a mensagem é apresentada ao usuário, o aplicativo cria um "link" para a URL informada, permitindo assim que o usuário "clique" no texto da mensagem e abra a URL no navegador de seu dispositivo móvel. Veja como parametrizar a URL junto de uma mensagem no tópico de ajuda "Enviando Mensagens (smsg)".

<p>Na forma mais simples, a URL pode apontar para um endereço qualquer, sem que exista a necessidade da aplicação saber quem foi o assinante que "clicou" na mensagem. Exemplo:

<ul><li>Mensagem: "Visite nossa página de ofertas da semana!"</li>
<li>URL: "http://www.website.com/ofertas"</li></ul>

Em um segundo caso, onde a aplicação necessita saber quem é o usuário, a URL pode incluir os parâmetros para sua identificação. Esses parâmetros são USERID e SUBSCODE, que são substituídos no texto da URL quando a mensagem é apresentada. Exemplo:

<ul><li>Mensagem: "Seu pedido 12345 já foi despachado. Clique nessa mensagem e acompanhe o rastreamento de sua encomenda."</li>
<li>URL: "http://www.website.com?order=12345&user=USERID&subs=SUBSCODE"</li></ul>

<p>No exemplo acima, a aplicação receberá os parâmetros "order", "user" e "subs", contendo respectinamente "12345", o User-ID do usuário e o Código da Assinatura do canal. Uma vez que essa URL pode ser aberta por qualquer usuário na Internet (acidentalmente ou não), o que permitiria acesso às informações de rastreamento do pedido 12345, é importante que a aplicação faça a verificação do usuário informado (USERID e SUBSCODE). Essa verificação deve ser realizada com o comando <b>"chck"</b>. Veja como verificar um usuário no tópico de ajuda "Verificar Usuários (chck)".

<p>Caso a aplicação confirme a verificação do usuário, esta poderá apresentar as informações de rastreamento do pedido 12345, sem que seja necessário o "login" do usuário. Caso contrário, a aplicação poderá desviar para uma página alternativa.

<br><br><h4>Envio de mensagens de retorno</h4>

<p>Na parte inferior da lista de mensagens do canal, existe uma caixa de texto onde o usuário do dispositivo móvel pode enviar mensagens para a aplicação associada ao canal. Essa caixa de texto só é apresentada quando o desenvolvedor preenche o atributo "URL para mensagens de retorno (http)", na inclusão do canal. Caso esse atributo não seja informado, a caixa de texto é omitida (veja esse atributo no tópico de ajuda: "Canais e Assinaturas / Gerenciando canais").

<p>A mensagem de retorno é criptografada de ponta a ponta, promovendo segurança para a aplicação que recebe a mensagem, a qual percorre o seguinte caminho:

<ul><li>Do dispositivo móvel para o webservice Followzup, criptografada com a chave pública do dispositivo;</li>
<li>Do webserice Followzup para a URL de destino, criptografada com a chave privada do canal.</li></ul>

A mensagem enviada pelo webservice chega à URL de destino por meio de uma conexão <b>"POST"</b>, a qual inclui 2 (dois) parâmetros:

<ul><li><b>fzupidchannel</b>: Identificação do canal recebedor da mensagem (não criptografado), necessário às aplicações que administram mais de um canal.<br><br></li>
<li><b>fzupresponse</b>: String criptografado com a chave privada do canal, contendo a data e a hora da mensagem, o User-ID e a mensagem do assinante:<br>"YYYY-MM-DD&nbsp;HH:MM:SS;User-ID;Texto-da-mensagem".</li></ul>

Para decriptar o string contido no parâmetro "fzupresponse", a aplicação deve fazer uso do método "decrypt" contido na API do canal, conforme exemplos a seguir:

<p><br><b>Exemplo 1 - Chamada da API com PHP:</b>

<table><tr><td style="background-color: #eee; text-align: center; padding: 30px; padding-right: 50px; font-size: 9px;">
<textarea readonly style="width: 100%; height: 150px; padding: 10px; font-size: 12px; font-family: monospace; color: #777;">
/* include API and create object */
include ("fzup_cXXXXXXXXXXX.php");
$object = new fzup_cXXXXXXXXXXX;

$encrypt_string = $_POST["fzupresponse"];

/* decrypt string response */
$response = $object -> decrypt ( $encrypt_string );

</textarea>
</td></tr></table>

<p><br><b>Exemplo 2 - Chamada da API com Java (servlet):</b>

<table><tr><td style="background-color: #eee; text-align: center; padding: 30px; padding-right: 50px; font-size: 9px;">
<textarea readonly style="width: 100%; height: 150px; padding: 10px; font-size: 12px; font-family: monospace; color: #777;">
/* import API and create object */
import mylib.fzup_cXXXXXXXXXXX;
fzup_cXXXXXXXXXXX object = new fzup_cXXXXXXXXXXX();

String encrypt_string = httpServletRequest.getParameter("fzupresponse");

/* decrypt string response */
String response = object.decrypt ( encrypt_string );

</textarea>
</td></tr></table>

<p><br>Nos quadros acima, é possível observar o retorno da execução da API (método "decrypt"), armazenados na variável "response", contendo a data e a hora da mensagem, o User-ID e a mensagem do assinante: "YYYY-MM-DD HH:MM:SS;User-ID;Texto-da-mensagem". A partir da obtenção desse string, a aplicação pode então extrair a identificação do usuário e a mensagem enviada, procedendo assim com a solicitação do usuário. Exemplo:

<ul><li>URL de resposta: "http://www.website.com/responses"</li>
<li>Mensagem ao usuário: "Compra de R$ 100,00 com o cartão final 1234. Responda B1234 para bloquear o cartão."</li></ul>

<p><br>No exemplo acima, o desenvolvedor configura a URL de resposta no cadastramento do canal; Com a existência desse atributo, o dispositivo móvel apresenta a caixa de texto na parte inferior da lista de mensagens; O usuário insere o texto correspondente para bloquear o cartão (se for o caso); Ao confirmar o envio da resposta, a aplicação recebe um POST na URL informada com os parâmetros "fzupidchannel" e "fzupresponse", contendo a mensagem do usuário; Finalmente, a aplicação decripta o string recebido e processa a solicitação do usuário.

<br><br><br><br>

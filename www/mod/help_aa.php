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

<h1>Adaptar Aplicações</h1>

<br><br>
<img class="roundcorner2" alt="followzup" src="img/home1.png" width="80" height="80" style="border: 1px solid #aaa;">
<img class="roundcorner2" alt="followzup" src="img/home3.png" width="80" height="80" style="border: 1px solid #aaa;">
<img class="roundcorner2" alt="followzup" src="img/home2.png" width="80" height="80" style="border: 1px solid #aaa;">
<img class="roundcorner2" alt="followzup" src="img/home4.png" width="80" height="80" style="border: 1px solid #aaa;">

<br><h2>Exemplo 1 - Monitorar a página "Fale Conosco"</h2>

<p>Em muitos sites da Internet, é comum encontrar as páginas "Fale Conosco", normalmente utilizadas para que os visitantes tirem suas dúvidas, registrem reclamações, solicitem orçamentos, e por ai vai. 

<p>Se você já fez uso de alguma dessas páginas ao visitar um site, deve ter notado também que é comum não receber resposta alguma às suas solicitações. Outras vezes, a resposta de sua solicitação chega tempos depois, indicando que seu contato foi processado com algum atraso.

<p>A razão para esses atrasos nas respostas, via de regra, ocorrem porque as pessoas responsáveis por responder as solicitações, só verificam a chegada de novas mensagens de tempos em tempos, até porque, é comum que essas solicitações sejam encaminhadas para uma caixa postal a parte, diferente da caixa postal de uso regular da pessoa responsável por essa verificação.

<p>Por outro lado, se um determinado site recebe poucas mensagens pela página "Fale Conosco", não é producente fazer essa verificação com frequência, simplesmente porque as pessoas tem mais o que fazer do que verificar a chegada de novas solicitações em caixas postais que quase sempre estão vazias.

<p>Pensando sobre essa situação, qual a melhor alternativa para os sistemas que administramos? Devemos eliminar a página "Fale Conosco" e evitar o envio de respostas extemporâneas ou devemos manter a página e proceder a verificação de solicitações de hora em hora?

<p>Nossa sugestão: Manter a página "Fale Conosco" no site e enviar uma mensagem de alerta para o celular da pessoa responsável, toda vez que algum visitante encaminhar uma solicitação por essa página, evitando assim, a necessidade de verificar a chegada de novas solicitações a cada hora.

<p>Vamos aprender a fazer isso com o Followzup, utilizando apenas 1 linha de comando.

<br><br><h4>Criando e assinando o canal</h4>

<p>O processo inicia com a criação de um canal de comunicação. Para isso, o desenvolvedor faz seu registro no site do Followzup e inclui um novo canal por meio do qual serão enviadas as mensagens ao responsável pelo tratamento das solicitações da página "Fale Conosco".

<p>Por sua vez, o usuário do sistema reponsável pelo tratamento das solicitações também faz seu registro do Followzup, instala o aplicativo Followzup disponível no Google-Play em seu dispositivo móvel, pesquisa e faz a assinatura do canal criado pelo desenvolvedor, no próprio aplicativo.

<br><br><h4>Baixando a chave pública do canal</h4>

<p>Após criar o canal, o desenvolvedor executa o download da API (PHP ou Java), contendo a chave pública RSA criada exclusivamente para cada canal.

<p>A API contém a Classe e o Método necessário para implementar a comunicação com o webservice Followzup, devendo ser armazenada no servidor da aplicação ou incluída diretamente no código do sistema.

<br><br><h4>Incluindo os comandos (aplicações em PHP)</h4>

<p>Finalmente, o desenvolvedor inclui em sua aplicação os comandos necessários para enviar a mensagem de alerta, junto da rotina que faz o tratamento do formulário de contato.

<p><b>Importante:</b> Nas aplicações construídas em PHP, é necessário instalar o pacote php5-mcrypt no servidor, para dar suporte à criptografia dos dados.<br><br>

<table><tr><td style="background-color: #eee; text-align: center; padding: 30px; padding-right: 50px; font-size: 9px;">
<textarea readonly style="width: 100%; height: 160px; padding: 10px; font-size: 12px; font-family: monospace; color: #777;">
/* include API and create object */
include ("fzup_cXXXXXXXXXXX.php");
$object = new fzup_cXXXXXXXXXXX;

/* submit the message to webservice */
$result = $object -> submit ( array ( "FZUP_COMMAND = smsg",
                                      "FZUP_LASTSEQ = 0",
                                      "FZUP_USER    = user.email@anymail.com",
                                      "FZUP_HOURS   = 24",
                                      "FZUP_MSGTEXT = A new contact message has arrived" ) );
</textarea>
</td></tr></table>

<br><h4>Incluindo os comandos (aplicações em Java)</h4>

<p>Finalmente, o desenvolvedor inclui em sua aplicação os comandos necessários para enviar a mensagem de alerta, junto da rotina que faz o tratamento do formulário de contato.

<table><tr><td style="background-color: #eee; text-align: center; padding: 30px; padding-right: 50px; font-size: 9px;">
<textarea readonly style="width: 100%; height: 160px; padding: 10px; font-size: 12px; font-family: monospace; color: #777;">
/* import API and create object */
import mylib.fzup_cXXXXXXXXXXX;
fzup_cXXXXXXXXXXX object = new fzup_cXXXXXXXXXXX();

/* submit the message to webservice */
String [] result = object.submit ( new String[] { "FZUP_COMMAND = smsg",
                                                  "FZUP_LASTSEQ = 0",
                                                  "FZUP_USER    = user.email@anymail.com",
                                                  "FZUP_HOURS   = 24",
                                                  "FZUP_MSGTEXT = A new contact message has arrived" } );
</textarea>
</td></tr></table>

<p><br><br>Em relação aos parâmetros "FZUP_LASTSEQ" e "FZUP_HOURS", utilizados na chamada do método, estes refere-se ao número de sequência da solicitação enviada ao webservice e ao tempo de vida da mensagem. Esses parâmetros serão apresentados mais adiante.

<p>O e-mail informado no parâmetro "FZUP_USER" deve ser o mesmo que o usuário do sistema utilizou na instalação do aplicativo Followzup em seu dispositivo móvel. Nesse parâmetro, também pode ser informado o "USER-ID", que também será apresentado mais adiante.

<p>Concluídas as adaptações acima, o usuário responsável pelo tratamento das solicitações passará a receber as mensagens do sistema em seu dispositivo móvel, todas as vezes que um visitante encaminhar uma solicitação pela página "Fale Conosco".

<br><br><br><br>
<img class="roundcorner2" alt="followzup" src="img/home1.png" width="80" height="80" style="border: 1px solid #aaa;">
<img class="roundcorner2" alt="followzup" src="img/home3.png" width="80" height="80" style="border: 1px solid #aaa;">
<img class="roundcorner2" alt="followzup" src="img/home2.png" width="80" height="80" style="border: 1px solid #aaa;">
<img class="roundcorner2" alt="followzup" src="img/home4.png" width="80" height="80" style="border: 1px solid #aaa;">

<h2>Exemplo 2 - Monitorar as vendas</h2>

<p>Suponha agora um site de comércio eletrônico, onde um visitante faz sua pesquisa de preços, coloca as mercadorias selecionadas em um carrinho de compras, faz o checkout e confirma seu pedido por meio do pagamento com cartão de crédito.

<p>Suponha agora que o gerente de vendas desse site de comércio eletrônico deseja receber uma mensagem do sistema em seu celular, contendo os números dos pedidos das compras acima de 10 mil reais, tão logo a confirmação de pagamento tenha sido realizado.

<p>Depois de apresentadas as necessidades por parte do gerente de vendas, o desenvolvedor do sistema inicia as adaptações para implementar o que foi solicitado.

<br><br><h4>Criando e assinando o canal</h4>

<p>O processo inicia com a criação de um canal de comunicação. Para isso, o desenvolvedor faz seu registro no site do Followzup e inclui um novo canal por meio do qual serão enviadas as mensagens ao gerente de vendas.

<p>Por sua vez, o gerente de vendas também faz seu registro do Followzup, instala o aplicativo Followzup disponível no Google-Play em seu dispositivo móvel, pesquisa e faz a assinatura do canal criado pelo desenvolvedor, no prórprio aplicativo.

<br><br><h4>Baixando a chave pública do canal</h4>

<p>Após criar o canal, o desenvolvedor executa o download da API (PHP ou Java), contendo a chave pública RSA criada exclusivamente para cada canal.

<p>A API contém a Classe e o Método necessário para implementar a comunicação com o webservice Followzup, devendo ser armazenada no servidor da aplicação ou incluída diretamente no código do sistema.

<br><br><h4>Incluindo os comandos (aplicações em PHP)</h4>

<p>Finalmente, o desenvolvedor inclui em sua aplicação os comandos necessários para enviar a mensagem ao gerente de vendas, ao final da rotina de confirmação de pedidos.

<p><b>Importante:</b> Nas aplicações construídas em PHP, é necessário instalar o pacote php5-mcrypt no servidor, para dar suporte à criptografia dos dados.<br><br>

<table><tr><td style="background-color: #eee; text-align: center; padding: 30px; padding-right: 50px; font-size: 9px;">
<textarea readonly style="width: 100%; height: 160px; padding: 10px; font-size: 12px; font-family: monospace; color: #777;">
/* include API and create object */
include ("fzup_cXXXXXXXXXXX.php");
$object = new fzup_cXXXXXXXXXXX;

/* submit the message to webservice */
$result = $object -> submit ( array ( "FZUP_COMMAND = smsg",
                                      "FZUP_LASTSEQ = 0",
                                      "FZUP_USER    = user.email@anymail.com",
                                      "FZUP_HOURS   = 24",
                                      "FZUP_MSGTEXT = A new contact message has arrived" ) );
</textarea>
</td></tr></table>

<br><h4>Incluindo os comandos (aplicações em Java)</h4>

<p>Finalmente, o desenvolvedor inclui em sua aplicação os comandos necessários para enviar a mensagem ao gerente de vendas, ao final da rotina de confirmação de pedidos.

<table><tr><td style="background-color: #eee; text-align: center; padding: 30px; padding-right: 50px; font-size: 9px;">
<textarea readonly style="width: 100%; height: 160px; padding: 10px; font-size: 12px; font-family: monospace; color: #777;">
/* import API and create object */
import mylib.fzup_cXXXXXXXXXXX;
fzup_cXXXXXXXXXXX object = new fzup_cXXXXXXXXXXX();

/* submit the message to webservice */
String [] result = object.submit ( new String[] { "FZUP_COMMAND = smsg",
                                                  "FZUP_LASTSEQ = 0",
                                                  "FZUP_USER    = user.email@anymail.com",
                                                  "FZUP_HOURS   = 24",
                                                  "FZUP_MSGTEXT = A new contact message has arrived" } );
</textarea>
</td></tr></table>

<p><br><br>Em relação aos parâmetros "FZUP_LASTSEQ" e "FZUP_HOURS", utilizados na chamada do método, estes refere-se ao número de sequência da solicitação enviada ao webservice e ao tempo de vida da mensagem. Esses parâmetros serão apresentados mais adiante.

<p>O e-mail informado no parâmetro "FZUP_USER" deve ser o mesmo que o usuário do sistema utilizou na instalação do aplicativo Followzup em seu dispositivo móvel. Nesse parâmetro, também pode ser informado o "USER-ID", que também será apresentado mais adiante.

<p>Concluídas as adaptações acima, o gerente de vendas passará a receber as mensagens provenientes do sistema de comércio eletrônico todas as vezes que um cliente confirmar seu pedido nas compras acima de 10 mil reais.

<br><br><br><br>


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

<h1>Verificar Usuários (chck)</h1>

<br><br>
<img class="roundcorner2" alt="followzup" src="img/home1.png" width="80" height="80" style="border: 1px solid #aaa;">
<img class="roundcorner2" alt="followzup" src="img/home3.png" width="80" height="80" style="border: 1px solid #aaa;">
<img class="roundcorner2" alt="followzup" src="img/home2.png" width="80" height="80" style="border: 1px solid #aaa;">
<img class="roundcorner2" alt="followzup" src="img/home4.png" width="80" height="80" style="border: 1px solid #aaa;">

<p>Quando a assinatura de um canal é efetivada pelo usuário, o Followzup cria um código aleatório de 8 (oito) dígitos numéricos, conhecido como "Código da Assinatura" (Subscription Code), que é associado a essa assinatura. Caso o usuário cancele a assinatura e em seguida efetive a assinatura do canal novamente, um novo código é gerado, e pode ser visto na tela do aplicativo quando o usuário visualiza a lista de mensagens do canal.

<p>O Código da Assinatura é particularmente útil para que o desenvolvedor da aplicação verifique a veracidade da assinatura, confirmando assim o e-mail (ou User-ID) informado pelo assinante (veja o tópico de ajuda: "Canais e Assinaturas / Gerenciando assinaturas").

<p>Para verificar se a assinatura do usuário corresponde ao Código da Assinatura informado pelo usuário, o desenvolvedor deve fazer uso do comando "<b>chck</b>". Após submetido o comando "<b>chck</b>", o webservice retorna uma das seguintes respostas:

<ul><li><b>O e-mail (ou User-ID) informado não é de um assinante do canal</b>. Nesse caso, é provável que o usuário tenha fornecido alguma informação incorreta ou tenha cancelado sua assinatura.<br><br></li>

<li><b>O e-mail (ou User-ID) informado é de um assinante do canal mas o Código da Assinatura não está correto</b>. Nesse caso, é provável que o usuário tenha informado o e-mail (ou User-ID) de um outro usuário que é assinante do canal, ou tenha informado o Código da Assinatura incorreto.<br><br></li>

<li><b>O e-mail (ou User-ID) informado é de um assinante do canal e o Código da Assinatura está correto</b>. Nesse caso, a informação está confirmada.<br></li></ul>

<p>Após o retorno da solicitação, o desenvolvedor pode descartar o Código da Assinatura informado pelo assinante, pois este só tem utilidade no ato verificação, até porque, em uma próxima verificação esse código pode ter sido alterado.

<p>A identificação do usuário para a verificação do Código da Assinatura pode ser realizada de 2 (duas) formas: pelo <b>e-mail</b> do usuário ou pelo seu <b>User-ID</b>. O User-ID é um código de 12 caracteres alfanuméricos iniciado com a letra "z", e corresponde à identificação interna de cada usuário registrado no Followzup (exemplo: za4w7cr23kmk). Todas as letras contidas em um User-ID são minúsculas.

<br><br><h4>Sequenciar as solicitações</h4>

<p>Toda solicitação enviada ao webservice Followzup possui um número de sequência iniciando em 1 (um) a partir da criação ou renovação das chaves assimétricas do canal.

<p>Depois de encaminhada uma solicitação com um determinado número de sequência N, a próxima solicitação deverá obrigatoriamente possuir a sequência N + 1, exceto nas situações onde o webservice não consegue identificar o comando solicitado, a sequência informada ou o canal solicitante (Channel-ID), pois essas informações são necessárias para registrar a última sequência utilizada.

<p>Caso a aplicação encaminhe uma solicitação com uma sequência diferente de N + 1, o webservice simplesmente descarta a solicitação e retorna o código de erro correspondente, junto com o número da última sequência utilizada, a qual é inserida no frame de resposta criptografado, conforme abaixo:

<table><tr><td style="background-color: #eee; text-align: center; padding: 30px; padding-right: 50px; font-size: 9px;">
<textarea readonly style="width: 100%; height: 150px; padding: 10px; font-size: 12px; font-family: monospace; color: #777;">
&lt;?xml version="1.0" encoding="utf-8"?&gt;
<followzup>
  <seq>Last-sequence</seq>
</followzup>

Onde:
 - Last-sequence: Último número de sequência utilizado.
</textarea>
</td></tr></table>

<p>Na medida do possível, é importante que a aplicação guarde sempre o valor da última sequência utilizada em seu banco de dados, para que não seja necessário reenviar comandos descartados pelo webservice, retardando a execução das solicitações.

<p>A verificação do argumento de sequência é essencial para a segurança do processo, pois caso uma determinada solicitação seja interceptada e reencaminhada em algum ponto da rede de acesso durante sua transmissão para o webservice, o reencaminhamento será considerado inválido.

<br><br><h4>Parametrizar a verificação</h4>

<p>São 4 (quatro) os parâmetros válidos na verificação do usuário: FZUP_COMMAND, FZUP_LASTSEQ, FZUP_USER, FZUP_SUBCODE. Esses parâmetros são submetidos por meio de um "array" de "strings" para a API, que tem a função de encriptar os parâmetros e encaminhá-los para o webservice.

<ul><li><b>FZUP_COMMAND</b> (obrigatório): Deve conter a literal "chck" (check user).</li><br>

<li><b>FZUP_LASTSEQ</b> (opcional): Contém o número de sequência da última solicitação, o qual é adicionado em 1 pela API, antes de ser encaminhado ao webservice. Quando o parâmetro FZUP_LASTSEQ não é informado, a API utiliza o último valor contido no objeto FZUP, o qual também será adicionado em 1 antes de ser encaminhado ao webservice.</li><br>

<li><b>FZUP_USER</b> (obrigatório): Contém o <b>e-mail</b> ou p <b>User-ID</b> do usuário a ser verificado</li><br>

<li><b>FZUP_SUBSCODE</b> (obrigatório): Contém o Código da Assinatura a ser verificado.</li></ul>

<br><h4>Exemplo 1 - Chamada da API com PHP</h4>

<table><tr><td style="background-color: #eee; text-align: center; padding: 30px; padding-right: 50px; font-size: 9px;">
<textarea readonly style="width: 100%; height: 150px; padding: 10px; font-size: 12px; font-family: monospace; color: #777;">
/* include API and create object */
include ("fzup_cXXXXXXXXXXX.php");
$object = new fzup_cXXXXXXXXXXX;

/* submit user verification */
$result = $object -> submit ( array ( "FZUP_COMMAND  = chck",
                                      "FZUP_LASTSEQ  = 9999",
                                      "FZUP_USER     = user.email@anymail.com",
                                      "FZUP_SUBSCODE = 12345678" ) );
</textarea>
</td></tr></table>

<br><br><h4>Exemplo 2 - Chamada da API com JAVA</h4>

<table><tr><td style="background-color: #eee; text-align: center; padding: 30px; padding-right: 50px; font-size: 9px;">
<textarea readonly style="width: 100%; height: 150px; padding: 10px; font-size: 12px; font-family: monospace; color: #777;">
/* import API and create object */
import mylib.fzup_cXXXXXXXXXXX;
fzup_cXXXXXXXXXXX object = new fzup_cXXXXXXXXXXX();

/* submit user verification */
String[] result = object.submit ( new String[] { "FZUP_COMMAND  = chck",
                                                 "FZUP_LASTSEQ  = 9999",
                                                 "FZUP_USER     = zw2hg454ghx9",
                                                 "FZUP_SUBSCODE = 12345678" } );
</textarea>
</td></tr></table>

<br><br><h4>Retorno da API</h4>

<p>Nos exemplos acima, é possível observar o retorno da execução da API (método "submit"), armazenados na variável "result". Esse retorno consiste em um array (string) de 3 (três) posições. São elas: 

<ul><li><b>result[0]</b>: String numérico de até 4 caractares informando o código de retorno da execução. Os códigos de retorno possíveis nas solicitações de verificação de usuários são:<br><br>

<table><tr><td style="background-color: #eee; text-align: center; padding: 30px; padding-right: 30px; font-size: 9px;">
<table style="background-color: #fff; border: 1px solid #666; border-collapse: collapse; text-align: left; font-size: 12px;">

<tr><td colspan="3" style="text-align: center; background-color: #ccc; border: 1px solid #666;">Códigos de retorno - Verificar usuário</td></tr>
<tr><td style="border: 1px solid #666;">0</td><td style="border: 1px solid #666; width: 30%;">Verificação confirmada</td><td style="border: 1px solid #666;">Indica que o usuário informado é assinante do canal.</td></tr>
<tr><td style="border: 1px solid #666;">6101</td><td style="border: 1px solid #666; width: 30%;">Sequência inválida</td><td style="border: 1px solid #666;">O parâmetro FZUP_LASTSEQ é inválido. Esse código de retorno é tratado pela própria API.</td></tr>
<tr><td style="border: 1px solid #666;">6102</td><td style="border: 1px solid #666;">Frame inválido</td><td style="border: 1px solid #666;">Informa que houve erro na decriptação dos dados, podendo ter ocorrido falha na transmissão.</td></tr>
<tr><td style="border: 1px solid #666;">6103</td><td style="border: 1px solid #666;">Comando inválido</td><td style="border: 1px solid #666;">O comando informado no parâmetro FZUP_COMMAND é inválido ou não foi informado.</td></tr>
<tr><td style="border: 1px solid #666;">6104</td><td style="border: 1px solid #666;">Channel-ID inválido</td><td style="border: 1px solid #666;">O Channel-ID transmitido pela API é inválido.</td></tr>
<tr><td style="border: 1px solid #666;">6106</td><td style="border: 1px solid #666;">Usuário inválido</td><td style="border: 1px solid #666;">O usuário informado no parâmetro FZUP_USER é inválido.</td></tr>
<tr><td style="border: 1px solid #666;">6203</td><td style="border: 1px solid #666;">Usuário não é assinante</td><td style="border: 1px solid #666;">O usuário informado no parâmetro FZUP_USER não é um assinante do canal.</td></tr>
<tr><td style="border: 1px solid #666;">6204</td><td style="border: 1px solid #666;">Código da Assinatura não confere</td><td style="border: 1px solid #666;">O valor informado no parâmetro FZUP_SUBSCODE não confere com a assinatura do canal.</td></tr>
<tr><td style="border: 1px solid #666;">6999</td><td style="border: 1px solid #666;">Sistema em manutenção</td><td style="border: 1px solid #666;">Informa que o webservice encontra-se em manutenção.</td></tr>

</table>
</td></tr></table>
</li><br><br>

<li><b>result[1]</b>: String numérico contendo número de sequência utilizado na execução. É importante que esse valor seja armazenado pela aplicação para que o mesmo seja informado em uma próxima solicitação de comando.</li><br><br>

<li><b>result[2]</b>: String XML contendo informações do usuário:<br><br>

<table><tr><td style="background-color: #eee; text-align: center; padding: 30px; padding-right: 50px; font-size: 9px;">
<textarea readonly style="width: 100%; height: 150px; padding: 10px; font-size: 12px; font-family: monospace; color: #777;">
&lt;?xml version="1.0" encoding="utf-8"?&gt;
<followzup>
  <uid>User-ID</uid>
  <reg>yes|no</reg>
</followzup>

Onde:
 - User-ID:  Identificação do usuário no Followzup.
 - Register: Flag usuário registrado (yes) ou anônimo (no).      
</textarea>
</td></tr></table>

<br><p>Importante observar que as informações acima (XML de retorno), são apresentadas apenas quando a consulta é realizada com sucesso (Código de retorno = 0).

<p>Por questões de privacidade, observamos também que quando realizamos a consulta utilizando o <b>e-mail</b>, podemos obter o <b>User-ID</b> do usuário, mas a recíproca não é verdadeira, pois quando realizamos a consulta utilizando o <b>User-ID</b>, não obtemos o <b>e-mail</b> do usuário.

</li></ul>

<br><br><br><br>

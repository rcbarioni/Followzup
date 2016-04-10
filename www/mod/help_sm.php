
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

<h1>Enviar Mensagens (smsg)</h1>

<br><br>
<img class="roundcorner2" alt="followzup" src="img/home1.png" width="80" height="80" style="border: 1px solid #aaa;">
<img class="roundcorner2" alt="followzup" src="img/home3.png" width="80" height="80" style="border: 1px solid #aaa;">
<img class="roundcorner2" alt="followzup" src="img/home2.png" width="80" height="80" style="border: 1px solid #aaa;">
<img class="roundcorner2" alt="followzup" src="img/home4.png" width="80" height="80" style="border: 1px solid #aaa;">

<br><br><p>No Followzup, existem 3 (três) formas de enviar mensagens. A primeira delas é conhecida como “broadcast”, que é utilizada quando se deseja enviar uma mensagem padrão para todos os assinantes do canal, sem exceção. A segunda forma é conhecida como “unicast”, que é utilizada quando se deseja enviar uma mensagem específica a um determinado assinante do canal, em particular. A terceira forma é conhecida como "multicast", que é utilizada quando se deseja enviar uma mensagem padrão para um subconjunto de assinantes do canal.

<p>Em um sistema de comércio eletrônico, por exemplo, podemos enviar mensagens do tipo broadcast contendo as ofertas da semana, indistintamente a todos os assinantes do canal, ou podemos também enviar uma mensagem do tipo unicast confirmando o pagamento de uma ordem de compra a um determinado usuário, em particular.

<p>Mas digamos que o sistema de comércio eletrônico deseja agora enviar uma mensagem de broadcast contendo ofertas de perfumes femininos, mas apenas ao subconjunto de assinantes do canal que sejam do sexo feminino, conforme consta em seu cadastro de clientes. Em casos assim, podemos fazer uso do tipo multicast, onde enviamos uma mensagem padrão para uma “lista de usuários”, extraídos da base de dados da loja.

<p>No Followzup, uma mensagem de broadcast pode ser realizada com uma única solicitação ao webservice, enquanto que as mensagens unicast são realizadas uma a uma, até porque, são mensagens diferentes para cada destinatário. No caso das mensagens multicast, podemos realizar o envio de mensagens com listas de até 200 assinantes por solicitação. 

<p>É importante observar entretanto, que quando solicitamos o envio de mensagens unicast, devemos saber para quem estamos enviando. Dessa forma, quando enviamos uma mensagem para confirmar uma ordem de compra a um determinado cliente, devemos identificá-lo na hora de enviar a solicitação ao webservice. O mesmo ocorre quando precisamos construir uma “lista de usuários” para envio de uma mensagem do tipo multicast. Identificar o destinatário é nosso próximo assunto.

<br><br><h4>Identificar o destinatário</h4>

<p>A identificação de destinatários para envio de mensagens unicast e multicast pode ser realizada de 2 (duas) formas: pelo <b>e-mail</b> do usuário ou pelo seu <b>User-ID</b>. A lista de destinatários de mensagens multicast pode misturar ambas as formas de identificação.

<p>O User-ID é um código de 12 caracteres alfanuméricos iniciado com a letra "z", e corresponde à identificação interna de cada usuário registrado no Followzup (exemplo: z4aw7cr23kmk). Todas as letras contidas em um User-ID são minúsculas.

<p>Para a aplicação, não faz diferença enviar mensagens utilizando o e-mail ou o User-ID, mas para o destinatário, a diferença é a privacidade, como veremos no exemplo a seguir. 

<p>Digamos que em nossa loja virtual, o registro de cadastro de um determinado assinante, na base de dados da loja, contém seu e-mail. Com esse e-mail, o sistema poderá enviar mensagens pelo Followzup e também poderá enviar mensagens de e-mails para o cliente, a qualquer momento. Numa outra situação, se um assinante quer apenas receber mensagens pelo Followzup, poderá fazê-lo registrando seu User-ID no cadastro da loja, mas não informar seu e-mail. Nesse caso, o sistema só poderá enviar mensagens por meio do Followzup.

<br><br><h4>Temporizar a mensagem</h4>

<p>Além do destinatário e do texto da mensagem, existe um outro atributo fornecido pela aplicação quando a mensagem está sendo enviada: é o tempo de vida útil da mensagem, limitado a 960 horas, equivalente a 40 dias.

<p>Imagine que estamos numa sexta feira, e nossa loja virtual resolve fazer uma liquidação de calçados no fim de semana, e para tanto, envia de uma mensagem de broadcast para seus clientes. Em uma situação como essa, não faz sentido que um cliente que tenha deixado seu celular desligado durante o fim de semana, receba a mensagem na segunda feira, pois a liquidação já terá terminado. Para evitar que isso aconteça, a aplicação poderá limitar o tempo de vida da mensagem a uma quantidade de horas, suficiente para que os clientes recebam a mensagem a tempo de realizar suas compras. Os clientes que ligarem o celular após o tempo determinado, simplesmente não recebem a mensagem.

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

<br><br><h4>Parametrizar o envio de mensagens</h4>

<p>São 6 (seis) os parâmetros válidos para enviar uma mensagem: FZUP_COMMAND, FZUP_LASTSEQ, FZUP_USER, FZUP_HOURS, FZUP_MSGTEXT e FZUP_MSGURL. Esses parâmetros são submetidos por meio de um "array" (string) para a API, que tem a função de encriptar os parâmetros e encaminhá-los para o webservice.

<ul><li><b>FZUP_COMMAND</b> (obrigatório): Deve conter a literal "smsg" (send message).</li><br>

<li><b>FZUP_LASTSEQ</b> (opcional): Contém o número de sequência da última solicitação, o qual é adicionado em 1 pela API, antes de ser encaminhado ao webservice. Quando o parâmetro FZUP_LASTSEQ não é informado, a API utiliza o último valor contido no objeto FZUP, o qual também será adicionado em 1 antes de ser encaminhado ao webservice.</li><br>

<li><b>FZUP_USER</b> (obrigatório): Contém o destinatário da mensagem, conforme o tipo de mensagem a seguir:<br><br>

<ul><li><b>Broadcast</b>: O parâmetro FZUP_USER deve conter a literal "<b>all</b>". Nesse caso, a mensagem será enviada a todos os assinantes do canal.</li><br>
<li><b>Unicast</b>: O parâmetro FZUP_USER deve conter o e-mail ou o User-ID do usuário. Exemplos: "user.amil@anymail.com", "z12h49d934jw".</li><br>
<li><b>Multicast</b>: O parâmetro FZUP_USER deve conter uma lista com até 200 destinatários (e-mail ou User-ID), separados por "," (virgulas). Exemplo: "user-mail@anymail.com,z893gfle74ga".</li></ul></li><br>

<li><b>FZUP_HOURS</b> (opcional): Deve conter um valor numérico entre 1 e 960, representando o tempo de vida da mensagem. Para os valores inválidos ou não informados, o tempo de vida será definido em 24 horas.</li><br>

<li><b>FZUP_MSGTEXT</b> (obrigatório): Deve conter a mensagem a ser enviada, com até 200 caracteres.</li>

<li><b>FZUP_MSGURL</b> (opcional): Contém o endereço HTTP que será utilizado como "link" associado à mensagem, permitindo que os usuário abra a URL informada ao "clicar" no texto da mensagem. Opcionalmente, a URL pode incluir os parâmetros USERID e SUBSCODE, para que a aplicação possa identificar o usuário que "clicou" na mensagem.</li></ul>

<br><h4>Exemplo 1 - Chamada da API com PHP</h4>

<table><tr><td style="background-color: #eee; text-align: center; padding: 30px; padding-right: 50px; font-size: 9px;">
<textarea readonly style="width: 100%; height: 400px; padding: 10px; font-size: 12px; font-family: monospace; color: #777;">
/* include API and create object */
include ("fzup_cXXXXXXXXXXX.php");
$object = new fzup_cXXXXXXXXXXX;

/* submit Broadcast message */
$result = $object -> submit ( array ( "FZUP_COMMAND = smsg",
                                      "FZUP_LASTSEQ = 9999",
                                      "FZUP_USER    = all",
                                      "FZUP_HOURS   = 48",
                                      "FZUP_MSGTEXT = This is a broadcast message.",
                                      "FZUP_MSGURL  = http://www.website.com?user=USERID&subs=SUBSCODE" ) );

/* submit Unicast message to e-mail */
$result = $object -> submit ( array ( "FZUP_COMMAND = smsg",
                                      "FZUP_USER    = user.email@anymail.com",
                                      "FZUP_MSGTEXT = This is a unicast message.",
                                      "FZUP_MSGURL  = http://www.website.com/contact" ) );

/* submit Unicast message to User-ID */
$result = $object -> submit ( array ( "FZUP_COMMAND = smsg",
                                      "FZUP_USER    = ze87fh397a23",
                                      "FZUP_MSGTEXT = This is a unicast message." ) );

/* submit Multicast message */
$result = $object -> submit ( array ( "FZUP_COMMAND = smsg",
                                      "FZUP_USER    = user.email@anymail.com,z85ghs574kfj",
                                      "FZUP_MSGTEXT = This is a multicast message." ) );
</textarea>
</td></tr></table>

<br><br><h4>Exemplo 2 - Chamada da API com JAVA</h4>

<table><tr><td style="background-color: #eee; text-align: center; padding: 30px; padding-right: 50px; font-size: 9px;">
<textarea readonly style="width: 100%; height: 400px; padding: 10px; font-size: 12px; font-family: monospace; color: #777;">
/* import API and create object */
import mylib.fzup_cXXXXXXXXXXX;
fzup_cXXXXXXXXXXX object = new fzup_cXXXXXXXXXXX();

/* submit Broadcast message */
String[] result = object.submit ( new String[] { "FZUP_COMMAND = smsg",
                                                 "FZUP_LASTSEQ = 9999",
                                                 "FZUP_USER    = all",
                                                 "FZUP_HOURS   = 48",
                                                 "FZUP_MSGTEXT = This is a broadcast message",
                                                 "FZUP_MSGURL  = http://www.website.com?user=USERID&subs=SUBSCODE" } );

/* submit Unicast message to e-mail */
String[] result = object.submit ( new String[] { "FZUP_COMMAND = smsg",
                                                 "FZUP_USER    = user.email@anymail.com",
                                                 "FZUP_MSGTEXT = This is a unicast message",
                                                 "FZUP_MSGURL  = http://www.website.com/contact" } );

/* submit Unicast message to User-ID */
String[] result = object.submit ( new String[] { "FZUP_COMMAND = smsg",
                                                 "FZUP_USER    = z74gd672kmv6",
                                                 "FZUP_MSGTEXT = This is a unicast message" } );

/* submit Multicast message */
String[] result = object.submit ( new String[] { "FZUP_COMMAND = smsg",
                                                 "FZUP_USER    = user.email@anymail.com,z74gd672kmv6",
                                                 "FZUP_MSGTEXT = This is a multicast message" } );
</textarea>
</td></tr></table>

<br><br><h4>Retorno da API</h4>

<p>Nos exemplos acima, é possível observar o retorno da execução da API (método "submit"), armazenados na variável "result". Esse retorno consiste em um array de "strings" de 3 (três) posições. São elas: 

<ul><li><b>result[0]</b>: String numérico de até 4 caractares informando o código de retorno da execução. Os códigos de retorno possíveis nas solicitações de envio de mensagens são:<br><br>

<table><tr><td style="background-color: #eee; text-align: center; padding: 30px; padding-right: 30px; font-size: 9px;">
<table style="background-color: #fff; border: 1px solid #666; border-collapse: collapse; text-align: left; font-size: 12px;">

<tr><td colspan="3" style="text-align: center; background-color: #ccc; border: 1px solid #666;">Códigos de retorno - Enviar mensagem</td></tr>
<tr><td style="border: 1px solid #666;">0</td><td style="border: 1px solid #666; width: 30%;">Execução com sucesso</td><td style="border: 1px solid #666;">Informa que a solicitação em si foi realizada com sucesso, mesmo que algum dos destinatários informados tenha sido considerado inválido.</td></tr>
<tr><td style="border: 1px solid #666;">6101</td><td style="border: 1px solid #666; width: 30%;">Sequência inválida</td><td style="border: 1px solid #666;">O parâmetro FZUP_LASTSEQ é inválido. Esse código de retorno é tratado pela própria API.</td></tr>
<tr><td style="border: 1px solid #666;">6102</td><td style="border: 1px solid #666;">Frame inválido</td><td style="border: 1px solid #666;">Informa que houve erro na decriptação dos dados, podendo ter ocorrido falha na transmissão.</td></tr>
<tr><td style="border: 1px solid #666;">6103</td><td style="border: 1px solid #666;">Comando inválido</td><td style="border: 1px solid #666;">O comando informado no parâmetro FZUP_COMMAND é inválido ou não foi informado.</td></tr>
<tr><td style="border: 1px solid #666;">6104</td><td style="border: 1px solid #666;">Channel-ID inválido</td><td style="border: 1px solid #666;">O Channel-ID transmitido pela API é inválido.</td></tr>
<tr><td style="border: 1px solid #666;">6108</td><td style="border: 1px solid #666;">Mensagem nula</td><td style="border: 1px solid #666;">A mensagem informada no parâmetro FZUP_MSGTEXT é nula.</td></tr>
<tr><td style="border: 1px solid #666;">6109</td><td style="border: 1px solid #666;">Lista muito grande</td><td style="border: 1px solid #666;">A lista de destinatários informada possui mais de 200 entradas.</td></tr>
<tr><td style="border: 1px solid #666;">6999</td><td style="border: 1px solid #666;">Sistema em manutenção</td><td style="border: 1px solid #666;">Informa que o webservice encontra-se em manutenção.</td></tr>

</table>
</td></tr></table>
</li><br><br>

<li><b>result[1]</b>: String numérico contendo número de sequência utilizado na execução. É importante que esse valor seja armazenado pela aplicação para que o mesmo seja informado em uma próxima execução.</li><br><br>

<li><b>result[2]</b>: String XML contendo as totalizações da solicitação de envio de mensagens:<br><br>

<table><tr><td style="background-color: #eee; text-align: center; padding: 30px; padding-right: 50px; font-size: 9px;">
<textarea readonly style="width: 100%; height: 200px; padding: 10px; font-size: 12px; font-family: monospace; color: #777;">
&lt;?xml version="1.0" encoding="utf-8"?&gt;
<followzup>
  <snt>total-sent</snt>
  <nsb>total-no-subs</nsb>
  <inv>total-invalid</inv>
</followzup>

Onde:
 - Total-sent:    corresponde ao total de mensagens enviadas.
 - Total-no-subs: corresponde à quantidade de destinatários informados na solicitação que não são assinantes do canal.
 - Total-invalid: corresponde à quantidade de destinatários informados na solicitação que foram considerados inválidos. 

</textarea>
</td></tr></table>
</li></ul>

<br><br>
<img class="roundcorner2" alt="followzup" src="img/home1.png" width="80" height="80" style="border: 1px solid #aaa;">
<img class="roundcorner2" alt="followzup" src="img/home3.png" width="80" height="80" style="border: 1px solid #aaa;">
<img class="roundcorner2" alt="followzup" src="img/home2.png" width="80" height="80" style="border: 1px solid #aaa;">
<img class="roundcorner2" alt="followzup" src="img/home4.png" width="80" height="80" style="border: 1px solid #aaa;">
<h2>Recebimento de mensagens</h4>

<p>As mensagens encaminhadas pelas aplicações a seus respectivos destinatários, permanecem na base de dados do Followzup até que um dos dispositivos do assinante estabeleça conexão com o webservice. Nesse momento, as mensagens são transferidas para o dispositivo móvel do usuário onde poderão ser consultadas.

<p>Essa transferência de mensagens obedece uma regra básica associada à verificação da vida útil da mensagem, ou seja, se o assinante ligar seu dispositivo após expirar a vida útil da mensagem, ela simplesmente não é transferida. Entretanto, existem situações onde o próprio Followzup modifica o tempo de vida útil das mensagens, como veremos a seguir.

<p>Digamos em determinada situação, onde uma aplicação envia uma mensagem com uma vida útil de 15 dias a um assinante que possui 2 dispositivos, mas apenas um deles está ligado. Nesse caso, quando o primeiro dispositivo receber a mensagem, a vida útil da mensagem é reduzida a 24 horas para que esta seja recebida pelo outro dispositivo do mesmo usuário. Essa característica do Followzup tem por objetivo impedir que dispositivos desligados há muito tempo recebam uma avalanche de mensagens antigas já recebidas por dispositivos conectados. Essa redução de vida útil só não é realizada quando o tempo de vida útil de uma mensagem já é menor do que 24 horas.

<br><br><br><br>

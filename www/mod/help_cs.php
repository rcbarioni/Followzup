
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

<h1>Canais e Assinaturas</h1>

<br><br>
<img class="roundcorner2" alt="followzup" src="img/home1.png" width="80" height="80" style="border: 1px solid #aaa;">
<img class="roundcorner2" alt="followzup" src="img/home3.png" width="80" height="80" style="border: 1px solid #aaa;">
<img class="roundcorner2" alt="followzup" src="img/home2.png" width="80" height="80" style="border: 1px solid #aaa;">
<img class="roundcorner2" alt="followzup" src="img/home4.png" width="80" height="80" style="border: 1px solid #aaa;">

<br><br><p>Canais são os meios utilizados pelos desenvolvedores de aplicações para enviar mensagens a seus assinantes. Se um usuário deixar de ser assinante de um canal, nenhuma mensagem chegará até ele, ou seja, é o usuário quem decide se quer ou não receber mensagens de um canal, simplesmente tornando-se seu assinante ou cancelando sua assinatura.

<p>A criação de um canal deve ser realizada no site do Followzup por qualquer desenvolvedor que queira enviar mensagens a partir de sua aplicação, enquanto que a assinatura de um canal deve ser feita diretamente no aplicativo Followzup instalado no dispositivo móvel do usuário.

<p>Dependendo da estratégia adotada pela instituição, uma ou mais aplicações podem fazer uso de um mesmo canal. Para a maioria dos casos, podemos quantificar os canais de comunicação institucional em 3 (três) segmentos principais. São eles:

<ul><li><b>Canal Clientes</b>, incluindo-se os clientes ativos, que possuem cadastro e registro histórico de transações com a instituição, e clientes potenciais, que ainda não possuem histórico de transações, mas desejam igualmente receber mensagens institucionais ou promocionais das áreas de vendas, marketing ou relacionamento com clientes.<br><br></li>

<li><b>Canal Fornecedores</b>, incluindo-se os fornecedores de bens e serviços para os quais são enviados convites, chamadas e avisos das áreas técnicas, financeiras, compras, logística ou outras áreas de relacionamento com fornecedores.<br><br></li>

<li><b>Canal Interno</b>, incluindo-se os empregados efetivos e temporários em todos os níveis hierárquicos, para os quais são enviados avisos e notícias das áreas de recursos humanos, treinamento, produção ou outras áreas que demandem mensagens de caráter operacional ou gerencial, apoiando inclusive as ferramentas formais de comunicação institucional (correio eletrônico).<br></li></ul>

<p>Em função do tipo de relacionamento com a instituição, outras categorias poderão ser atendidas por canais de interesses específicos, tais como ouvidorias, acionistas, conselheiros, diretores, entidades de classe e gestores de sistemas.

<p>Nas situações onde mais de uma aplicação faz uso de um mesmo canal, torna-se oportuno o desenvolvimento de um “gateway” que coordene a comunicação com o webservice Followzup e atenda as solicitações dos sistemas internos, mantendo assim o controle de sequência das requisições.

<br><br><br><br>
<img class="roundcorner2" alt="followzup" src="img/home1.png" width="80" height="80" style="border: 1px solid #aaa;">
<img class="roundcorner2" alt="followzup" src="img/home3.png" width="80" height="80" style="border: 1px solid #aaa;">
<img class="roundcorner2" alt="followzup" src="img/home2.png" width="80" height="80" style="border: 1px solid #aaa;">
<img class="roundcorner2" alt="followzup" src="img/home4.png" width="80" height="80" style="border: 1px solid #aaa;">

<h2>Gerenciando canais</h2>

<p>Independente da finalidade, a criação de um canal deverá ser realizada na página do Followzup, por um usuário identificado por sua conta de e-mail, o qual será o proprietário do canal. Não existe a possibilidade de dois ou mais desenvolvedores compartilharem a posse de um mesmo canal. Por segurança, caso exista a necessidade de compartilhar a posse de um determinado canal, pode-se fazer uso de contas de e-mail institucionais, com acesso compartilhado.

<p>Para criar um canal, o desenvolvedor deve acessar a página do Followzup e registrar as seguintes informações:

<ul><li><b>Tag</b> (obrigatório) - Conhecida também como “nome do canal”, a tag é a identificação do canal no Followzup. A tag deve ter entre 3 e 32 caracteres, podendo ser composta de letras maiúsculas, letras minúsculas, números e o caracter “-” (hifen). Deve também iniciar e terminar com um caracter alfanumérico.<br><br>

<a name="tags"></a>

<table><tr><td style="background-color: #eee; text-align: center; padding: 20px; font-size: 9px;">
<table border=1 style="background-color: #fff; border: 1px solid #666; border-collapse: collapse; text-align: left; font-size: 12px;"><tr><td style="padding: 20px;">
<b>Tags reservadas:</b> As tags com 3 e 4 caracteres são reservadas pelo sistema, e podem ser disponibilizadas ao usuário mediante solicitação. Havendo o interesse em registrar uma dessas tags, entre em contato conosco, <a href="contact">clicando aqui</a>.
</td></tr></table>
</td></tr></table>
<br><br></li>

<li><b>Texto de apresentação</b> (opcional) - Texto com até 200 caracteres, o qual é exibido junto com a Tag nos resultados de pesquisa, para auxiliar os usuários na identificação do canal a ser assinado.<br><br></li>

<li><b>Mensagem de boas vindas</b> (opcional) - Mensagem com até 200 caracteres enviada automaticamente ao usuário quando o canal é assinado, e que pode ser também um aviso ou uma orientação ao novo assinante.<br><br></li>

<li><b>URL de destino da mensagem de boas vindas (http)</b> (opcional) - Endereço HTTP enviado juntamente com a mensagem de boas vindas, a qual permite ao usuário "clicar" na mensagem e abrir a URL informada por meio do navegador instalado em seu dispositivo móvel.<br><br></li>

<li><b>URL para mensagens de retorno (http)</b> (opcional) - Endereço HTTP para envio de mensagens do usuário a partir de seu dispositivo móvel, por meio da caixa de texto apresentada na parte inferior da lista de mensagens do canal. Essa caixa de texto só é apresentada quando a URL é informada.<br><br></li>

<li><b>Private Channel</b> (flag) - Indicador de canal privado, fazendo com que o canal só apareça nos resultados de pesquisa quando o argumento pesquisado for idêntico à tag do canal, incluindo letras maiúsculas e minúsculas. Uma vez assinado, o canal Private tem a mesma forma de utilização como qualquer outro canal. Um canal pode ser alternado entre “público” e “privado” a qualquer momento pelo seu administrador.<br><br></li>

<li><b>Private Code</b> (opcional) - Código secreto exigido para a assinatura de canais privados, devendo ser informado pelo usuário no ato de sua assinatura.<br><br></li>

<li><b>Channel Icon</b> (opcional) - Imagem representativa do canal, apresentado junto com a Tag na lista de canais e nos resultados de pesquisa. Depois do upload, a imagem é convertida na forma quadrada.<br></li></ul>

<p>Após a criação, o Followzup gera um par de chaves assimétricas (RSA) para uso exclusivo do canal, devendo o desenvolvedor fazer o download da API (PHP ou Java), onde está incluída a chave pública. As chaves assimétricas são usadas no processo de criptografia dos dados transmitidos.

<p>A API que contém a chave pública deve ser armazenada em lugar seguro no servidor da aplicação, pois com essa API podemos interagir com o webservice e enviar mensagens para os assinantes do canal. Em caso de eventual quebra de sigilo no armazenamento da API, o desenvolvedor pode gerar um novo par de chaves assimétricas e proceder um novo download.

<p>Outra informação gerada na criação do canal é sua identificação interna no Followzup (Channel-ID), composto por 12 caracteres alfanuméricos iniciado com a letra "c". Todas as letras contidas em um Channel-ID são minúsculas (exemplo: c03wfcr23k1p).

<p>Para interagir com o webservice, a aplicação possui 2 (dois) comandos. São eles:

<ul><li><b>SMSG</b> - Send Message (Enviar mensagens).<br></li>
<li><b>CHCK</b> - Check User (Verificar usuários).<br></li></ul>

<p>Em caso de necessidade, o canal pode ser “suspenso” pelo desenvolvedor. Um canal suspenso permanece disponível para a aplicação como se fosse um canal ativo, inclusive para envio de mensagens. A diferença está na interrupção do processo de transmissão das mensagens aos dispositivos móveis dos assinantes, como se uma torneira fosse fechada. Uma vez reativado, o Followzup reinicia o processo de transmissão das mensagens represadas durante o período de suspensão, que ainda estejam dentro de seu tempo de vida útil.

<p>Em relação às informações de data e hora relacionadas ao canal, estas obedecem o fuso horário do proprietário do canal, como definido em seu perfil, portanto, o webservice pode encaminhar uma mensagem proveniente da costa leste às 17h00, mas informará ao destinatário da costa oeste que a mensagem foi enviada às 13h00, de acordo com o fuso horário obtido no dispositivo móvel do assinante.

<p>A posse de um canal pode ser transferida entre usuários, devendo ser realizada na página do Followzup, onde é indicado o e-mail do novo proprietário, que por sua vez recebe uma mensagem em seu correio eletrônico solicitando a confirmação da transferência de posse do canal. Após confirmada a transferência de posse, esta não poderá ser desfeita pelo antigo proprietário.

<br><br><br><br>
<img class="roundcorner2" alt="followzup" src="img/home1.png" width="80" height="80" style="border: 1px solid #aaa;">
<img class="roundcorner2" alt="followzup" src="img/home3.png" width="80" height="80" style="border: 1px solid #aaa;">
<img class="roundcorner2" alt="followzup" src="img/home2.png" width="80" height="80" style="border: 1px solid #aaa;">
<img class="roundcorner2" alt="followzup" src="img/home4.png" width="80" height="80" style="border: 1px solid #aaa;">

<h2>Gerenciando assinaturas</h2>

<p>Para que um usuário receba mensagens das aplicações, este deverá efetuar a assinatura dos canais desejados. A assinatura de canais deve ser feita diretamente no aplicativo Followzup instalado em seu dispositivo móvel.

<p>A lista de canais assinados por um usuário é mantida na base de dados do Followzup e sincronizada em todos os dispositivos que estabeleçam comunicação com o webservice. Quanto às mensagens, essas só são enviadas aos dispositivos que estiverem em funcionamento durante o tempo de vida da mensagem, limitado a 960 horas. Por sua vez, as mensagens excluídas por um usuário em um de seus dispositivos são igualmente excluídas da base de dados do Followzup, mas permanecem na base local de outros dispositivos do mesmo usuário, ou seja, não há sincronização de mensagens entre dispositivos.

<p>A assinatura de um canal inicia-se com a pesquisa da Tag, que deve ser realizada no aplicativo instalado no dispositivo móvel. Uma vez localizado, o usuário seleciona o canal e confirma a assinatura. Caso seja um canal "privado" e possua o atributo “Private Code”, esse código deverá ser informado pelo usuário para que a assinatura seja efetivada.

<p>Após a assinatura do canal, o usuário estará habilitado a receber mensagens de broadcast, que são mensagens enviadas indistintamente a qualquer assinante do canal, identificado ou não, sendo particularmente útil para mensagens institucionais e de natureza comercial, tais como ofertas, promoções e avisos em geral. Entretanto, quando a aplicação deseja enviar uma mensagem específica a um determinado usuário, é importante que o desenvolvedor confirme se o usuário é realmente um assinante do canal. Essa confirmação é realizada com a verificação do “Código da Assinatura”, como veremos a seguir.

<p>Quando a assinatura de um canal é efetivada pelo usuário, o Followzup cria um código aleatório de 8 (oito) dígitos numéricos, conhecido como "Código da Assinatura" (Subscription Code), que é associado a essa assinatura. Caso o usuário cancele a assinatura e em seguida efetive a assinatura do canal novamente, um novo código é gerado, e pode ser visto na tela do aplicativo quando o usuário visualiza a lista de mensagens do canal.

<p>O Código da Assinatura é particularmente útil para que o desenvolvedor da aplicação verifique a veracidade da assinatura, confirmando assim o e-mail (ou User-ID) informado pelo assinante. Vejamos um exemplo:

<p>Suponha que o sistema de uma empresa seguradora queira enviar, automaticamente, um aviso a seus clientes quando o contrato de seguro de seus veículos estiver próximo de expirar, para que estes entrem em contato com seus agentes de seguros para providenciar a renovação.

<p>Nesse exemplo, fica evidente que as mensagens enviadas são específicas para casa assinante (unicast), devendo o desenvolvedor recorrer ao cadastro de usuários da seguradora para obter o e-mail do usuário e encaminhar a mensagem.

<p>Certamente, o perfil do usuário no cadastro da seguradora contém o campo e-mail (ou User-ID), que deve ser informado pelo cliente para que este receba as mensagens por meio do Followzup. No momento em que o usuário fornece seu e-mail, o sistema da seguradora não tem como verificar se o e-mail informado é realmente de um assinante do canal e se está relacionado com a pessoa do assinante, fazendo com que a instituição corra o risco de enviar mensagens ao cliente errado, ou simplesmente ser descartada.

<p>Para verificar se o e-mail (ou User-ID) informado pelo assinante é válido, o desenvolvedor deve solicitar do usuário ambas as informações: o e-mail (ou User-ID) e o Código da Assinatura. Após obtidas, a aplicação faz a verificação das informações por meio do comando "chck". Se a verificação for positiva, o usuário poderá receber mensagens com segurança.

<p>O uso do comando "chck" está detalhado no tópico de ajuda respectivo.

<br><br><br><br>

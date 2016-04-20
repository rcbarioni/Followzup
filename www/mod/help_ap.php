
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

<h1>Funcionalidades do APP</h1>

<br><br>
<img class="roundcorner2" alt="followzup" src="img/home1.png" width="80" height="80" style="border: 1px solid #aaa;">
<img class="roundcorner2" alt="followzup" src="img/home3.png" width="80" height="80" style="border: 1px solid #aaa;">
<img class="roundcorner2" alt="followzup" src="img/home2.png" width="80" height="80" style="border: 1px solid #aaa;">
<img class="roundcorner2" alt="followzup" src="img/home4.png" width="80" height="80" style="border: 1px solid #aaa;">

<br><br><p>Para receber mensagens em seu dispositivo móvel, o usuário precisa de duas coisas: Instalar o APP, e por meio deste, fazer a assinatura dos canais de informações por onde estarão sendo enviadas as mensagens. Para instalar o APP, o usuário deve utilizar a loja virtual de aplicativos Google Play.

<p>Na primeira vez que o APP é executado, o usuário deve fazer o registro do dispositivo, o qual pode ser realizado de duas formas: por meio do login de um <b>usuário Followzup</b> ou por meio de um <b>usuário anônimo</b>. As diferenças entre os dois tipos de registro estão descritas adiante.

<br><br><h4>Registro de dispositivo com usuário Followzup</h4>

<p>Um usuário Followzup é aquele que possui "login" e "senha" cadastrados no site do Followzup, o qual é realizado previamente por meio do preenchimento dos dados de sua "nova conta", e confirmados pelo envio de uma mensagem para seu e-mail. Com o login e a senha confirmados, o usuário pode então prosseguir o registro de seu dispositivo, fazendo seu login na tela inicial do aplicativo. O login de um usuário no Followzup é sempre sua conta de e-mail.

<p>Independente de possuir seu login, o usuário também possui um código interno no Followzup, chamado de <b>User-ID</b>. Esse código é composto por 12 caracteres alfanuméricos iniciado com a letra "z". Todas as letras contidas em um User-ID são minúsculas (exemplo: z02wftr44k15).

<br><br><h4>Registro de dispositivo com usuário anônimo</h4>

<p>Um usuário anônimo é aquele que <b>não</b> possui "login" no site do Followzup. Cada vez que o registro de um dispositivo é realizado de forma anônima, o Followzup cria um usuário "anônimo" para uso exclusivo do dispositivo que está sendo registrado. Nesse caso, como não existe uma conta de e-mail para identificar o usuário, sua identificação será realizada apenas pelo seu código interno no Followzup (User-ID).

<br><br><h4>Diferenças entre os tipos de registros</h4>

<p>As diferenças entre os dispositivos registrados por usuários do Followzup (aqueles que possuem login e senha) e usuários anônimos são as seguintes:

<ul><li>Um usuário do Followzup poderá possuir mais de um dispositivo registrado em seu login, bastando para isso, informar seu login e senha nas telas de registro de seus vários dispositivos móveis. Quando uma mensagem é enviada para um usuário que possui mais de um dispositivo, todos os dispositivos receberão a mesma mensagem. No caso de registros anônimos, cada dispositivo estará associado a apenas um usuário anônimo, e não há como registrar mais de um dispositivo para um mesmo usuário anônimo.<br><br></li>

<li>Quando um usuário do Followzup faz o registro de um dispositivo, este replicará todos os canais assinados anteriormente, dessa forma, caso o usuário perca ou tenha seu dispositivo móvel danificado, o registro de um novo dispositivo restaurará sua lista de canais. No caso de usuários anônimos, a lista de assinaturas estará sempre vazia quando do registro inicial do dispositivo.<br><br></li>

<li>No caso da perda ou extravio de seu dispositivo móvel, o usuário anônimo não tem como "descadastrar" o dispositivo perdido, o que poderia possibilitar o acesso às suas mensagens por terceiros. No caso de usuários do Followzup, estes podem "descadastrar" dispositivos no site do Followzup, evitando assim, o acesso às suas mensagens por pessoas não autorizadas.</li></ul>

<br><br><h4>Gerenciando canais e mensagens</h4>

<p>Após o registro do dispositivo, será apresentada a lista de canais assinados pelo usuário. Para assinar novos canais, o usuário deve selecionar o ícone de pesquisa (lupa), onde será apresentada a tela de pesquisa de canais.

<p>Na tela de pesquisa, o usuário deve informar as letras iniciais do nome do canal desejado (pelo menos 3 letras), e clicar no botão "enviar", o que resultará em uma lista de canais que possuem as iniciais informadas. Caso o canal desejado tenha sido apresentado na lista resultante, o usuário poderá selecionar o canal e confirmar a assinatura.

<p>Os canais privados, assim definidos pelos seus gestores, deverão ser pesquisados pelo seu nome completo, obedecendo ainda a colocação de letras maiúsculas e minúsculas, onde houver. No caso de canais privados que possuam ainda o "Private Code" (código de segurança), este deverá ser informado na confirmação da assinatura. O "Private Code" deverá ser fornecido pelo gestor do canal.

<p>Quando uma nova assinatura é confirmada pelo usuário, o sistema cria um número aleatório de 8 dígitos, conhecido pelo nome de "Código da Assinatura", o qual é relacionado à cada nova assinatura. O código pode ser visto no topo da lista de mensagens no canal, e pode ser usado pelos sistemas de informações que enviam mensagens, para confirmar a veracidade da assinatura.

<p>Para cancelar a assinatura de um canal, basta que o usuário selecione (e segure) o canal desejado na lista de canais e confirme seu cancelamento. No cancelamento, o histórico de mensagens do canal é apagado.

<p>Para visualizar a lista de mensagens de um canal, basta que o usuário selecione o canal desejado na lista de canais. Por sua vez, as mensagens também podem ser excluídas da lista, bastando para isso que o usuário selecione (e segure) a mensagem desejada e confirme a exclusão.

<br><br><h4>Outras funcionalidades do APP</h4>

<p>Além das funções básicas de gestão de canais e mensagens, o que constitui sua essência, o APP possui também a opção de cancelamento do registro do dispositivo, disponível no menu do aplicatido. Nesse menu, também pode ser consultado o código do usuário (User-ID), o nome do dispositivo e informações sobre o APP.

<p>Importante lembrar que os usuário do Followzup (não anônimos), podem também gerir seus dispositivos no site do Followzup, onde estão disponíveis as funcionalidades para "descadastramento" e edição de nomes de dispositivos. 

<br><br><br><br>

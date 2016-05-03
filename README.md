# Followzup
Comunicação criptografada para a Internet das coisas

<br>
## Bem vindo ao projeto Followzup
Followzup é um sistema que implementa o conceito Business-to-People (B2P) para encaminhamento de mensagens de texto, onde de um lado encontram-se os sites Internet e sistemas corporativos enviando mensagens, e do outro lado encontram-se seus usuários e gestores recebendo essas mensagens em seus dispositivos móveis.

Desde uma pequena aplicação de mala direta implementada em uma rede doméstica, até um grande portal de informações, o envio de mensagens é realizado "em lote" por intermédio do webservice Followzup, que redireciona as mensagens para os dispositivos móveis de seus usuários.

Tanto do lado das aplicações e sites Internet quanto do lado dos dispositivos móveis, a comunicação é criptografada por meio dos protocolos AES e RSA (chaves assimétricas), promovendo confidencialidade em todo trajeto percorrido pela informação.

A privacidade, outro ponto forte do modelo, também é garantida aos usuários, pois esses podem escolher de quem desejam receber mensagens. Essa garantia é obtida pela adoção de **canais de informações**, criados e mantidos pelos desenvolvedores de sistemas e que são "assinados" pelos usuários conforme a conveniência.

Tanto em canais públicos como privados, o conceito de comunicação por meio do Followzup pode ainda agregar novas funcionalidades aos processos de comunicação corporativa interna e externa em instituições financeiras, comércio, serviços, governos, associações, escolas, universidades, hospitais, aeroportos, indústrias, meios de comunicação e entretenimento, sites internet, enfim, onde houver aplicações interagindo com seus usuários.

<br>
##Estrutura do projeto
O projeto compõe-se de duas partes: Cliente e Servidor.

No lado Servidor, em ambiente PHP/MySql, encontram-se os módulos web para gestão de **canais de informações**, utilizados pelos desenvolvedores de sistemas para criação e manutenção desses canais, assim como, os módulos que compõem o webservice para atendimento às demandas dos canais de informações e dos dispositivos móveis. Além dos módulos web, os desenvolvedores dispõe também, por meio de download, das APIs para apoio ao processo de comunicação entre suas aplicações e o webservice. Essas APIs estão disponíveis inicialmente em PHP e Java. A primeira implementação desse serviço encontra-se disponível no endereço "www.followzup.com".

No lado Cliente, encontra-se o APP para dispositivos Android (Java). Com esse aplicativo, o usuário dispõe das funções de "gestão de assinaturas" de canais e recebimento de mensagens provenientes das aplicações. O lado Cliente também dispõe de API (Classe Fzup) para apoio ao desenvolvimento de novos APPs com diferentes "skins" ou outros dispositivos que disponham dos protocolos de criptografia compatíveis com o serviço (quem sabe um dia estaremos recebendo mensagens na tela de um forno de micro-ondas?).

A comunicação entre os canais de informações (aplicações) e o webservice Followzup, bem como entre o webservice Followzup e os dispositivos móveis, são realizadas em modo assíncrono (**POST**), o que demanda consumo reduzido dos recursos de rede.

Os códigos fontes de ambas as partes do projeto (Cliente e Servidor), inclusive das APIs, estão publicadas nesse Git e disponíveis sob licença GPL.

<br>
##Formas de uso e participação
Existem 3 maneiras de fazer uso ou participar do projeto Followzup.

1. Usuários finais - São aqueles que instalam o APP em seus dispositivos móveis para receber mensagens dos canais de informações dos quais são assinantes. A instalação deve ser realizada em dispositivos compatíveis com os APPs disponíveis.

2. Gestor de Canais de Informações - São os desenvolvedores que adaptam suas aplicações para fazerem uso dos serviços disponíveis no endereço "www.followzup.com", para que suas aplicações possam enviar de mensagens a seus usuários.

3. Participantes do Projeto - São aqueles que envolvem-se no desenvolvimento do projeto em si, seja nos módulos servidor ou cliente. Essa participação pode ser realizada com melhoria do código, documentação, internacionalização, criação de APIs para outras linguagens, criação de novos APPs para outros tipos de dispositivos, ou até no aprimoramento dos protocolos de comunicação para expansão dos serviços.

<br>
##Exemplos de uso
Pela **praticidade** do modelo, relacionamos várias de suas utilidades. No aspecto gerencial, em relação ao monitoramento de recursos, podemos configurar nossos sistemas para enviar mensagens ao administrador de um website quando alguém utiliza a página "Fale Conosco", ou ao gerente de produção quando o processo crítico de determinado sistema sofre algum tipo de interrupção, ou à equipe de suporte quando um circuito de dados sai fora do ar, ou a um funcionário de vendas quando recebe o e-mail urgente de um cliente, ou a um gerente de compras quando o estoque de determinado produto alcança seu ponto crítico, enfim, **tudo que precisamos gerenciar**.

Pela **segurança** do processo, podemos também relacionar outras utilidades, tais como o envio de códigos de barras para pagamento de contas, confirmação de pedidos, agendamento de audiências e compromissos, alertas de compras realizadas com cartões de crédito, confirmações de trocas de senhas, avisos de ligações não atendidas, resultados de exames clínicos, movimentações financeiras, avisos de vencimento de apólices de seguros, alertas de alterações de cadastros, avisos de interrupção de serviços, rastreamento de encomendas, recalls de veículos, enfim, **tudo que precisamos ser informados**.

Por sua **conveniência**, outras utilidades podem ser exemplificadas, tais como: previsões astrológicas, salmos, tábuas de marés, avisos de estréias em cinemas e teatros, avisos de promoções de passagens aéreas, notícias sobre temas específicos (clipping), avisos de liquidações, avisos de ofertas de produtos em sites de compras, resultados de loterias, confirmações de vôos, notas de provas, calendários de eventos, enfim, **tudo que possa nos auxiliar em nosso dia a dia**. 

<br>
##Protocolos abertos
A comunicação entre os canais de informações e o webservice Followzup é estabelecida por meio do módulo denominado "wschannel.php". Essas solicitações são submetidas no formato XML, e são criptografadas com a chave pública (contida na API), criada exclusivamente para o cada canal. O string XML contém basicamente o comando a ser executado e os parâmetros necessários para sua execução. A descrição completa do conteúdo do string XML está contida no wiki deste projeto.

O mesmo conceito estabelece a comunicação entre os dispositivos móveis e o webservice Followzup, desta vez por meio do módulo denominado "wsdevice.php". Essas solicitações também são submetidas no formato XML, e são criptografadas com uma chave pública criada exclusivamente para cada dispositivo móvel. O string XML contém basicamente o comando a ser executado e os parâmetros necessários para sua execução. A descrição completa do conteúdo do string XML também está descrito no wiki deste projeto.

<br>
##Comunicação criptografada
Embora os processos de comunicação sejam implementados por meio de protocolos abertos, o modelo preserva a integridade e a confidencialidade dos dados por meio dos processos de criptografia AES e RSA.

Cada vez que um canal ou um dispositivo móvel é criado, o sistema cria um par de chaves assimétricas (RSA), composta por uma chave pública e uma chave privada. A chave privada é mantida na base de dados do servidor Followzup e utilizada para ter acesso ao conteúdo das solicitações encaminhadas ao webservice.

Com esse modelo, o desenvolvedor tem a segurança de que ninguém estará enviando solicitações ao webservice em nome de sua aplicação, a menos que ocorra alguma falha de segurança no armazenamento da API. Da mesma forma, o usuário do dispositivo móvel tem certeza de que ninguém estará enviando solicitações em seu nome, a menos que ocorra alguma falha de segurança no armazenamento da chave pública de seu dispositivo móvel.

<br>
##Documentação
A documentação do projeto está assim distribuída:

1. No próprio website do serviço (www.followzup.com) - Dirigida aos gestores de canais de informações e usuários de dispositivos móveis. Nessa documentação, não são aprofundados os detalhes do projeto, mas tão somente sobre os exemplos de uso das APIs e as adaptações necessárias nos sistemas para viabilizar a comunicação com o webservice.

2. Wiki do GitHub (http://github.com/rcbarioni/Followzup/wiki) - Dirigida aos participantes de seu desenvolvimento, gestores interessados na viabilização de novas implementações do serviço ou simplesmente interessados em conhecer as soluções técnicas adotadas.

<br>
##Conteúdo do Git
1. Diretório "www" - Contém os scripts PHP do servidor (incluindo os scripts PHP do webservice), arquivos CSS, imagens e outros recursos necessários à implementação do website. O detalhamento dos arquivos encontra-se no wiki do projeto.

2. Diretório "wiki" - Contém demais arquivos referenciados no Wiki do projeto.

<br>
##To do
Várias são as frentes que podem receber apoio dos interessados, entre elas:

1. Desenvimento de APIs para outras linguagens (C, Python, Ruby, Unity, Perl, Lua, etc);
2. Internacionalização (site, wiki, APP);
3. Desenvolvimento de APPs para outras plataformas e dispositivos.

<br>
##Contato
Tem alguma dúvida ou sugestão?

Estamos esperando seu contato no endereço: www.followzup.com/contact

Fique tranquilo. Followzup é software livre.

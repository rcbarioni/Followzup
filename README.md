# Followzup
Comunicação criptografada entre aplicações e dispositivos móveis

# Bem vindo ao projeto Followzup
Followzup é um sistema que implementa o conceito Business-to-People (B2P) para encaminhamento de mensagens de texto, onde de um lado encontram-se os sites Internet e sistemas corporativos enviando mensagens, e do outro lado encontram-se seus usuários e gestores recebendo essas mensagens em seus dispositivos móveis.

Desde uma pequena aplicação de mala direta implementada em uma rede doméstica, até um grande portal de informações, o envio de mensagens é realizado "em lote" por intermédio do webservice Followzup, que redireciona as mensagens para os dispositivos móveis de seus usuários.

Tanto do lado das aplicações e sites Internet quanto do lado dos dispositivos móveis, a comunicação é criptografada por meio dos protocolos AES e RSA (chaves assimétricas), promovendo confidencialidade em todo trajeto percorrido pela informação.

A privacidade, outro ponto forte do modelo, também é garantida aos usuários, pois esses podem escolher de quem desejam receber mensagens. Essa garantia é obtida pela adoção de "canais" de informações, criados e mantidos pelos desenvolvedores de sistemas e que são "assinados" pelos usuários conforme a conveniência.

Tanto em canais públicos como privados, o conceito de comunicação por meio do Followzup pode ainda agregar novas funcionalidades aos processos de comunicação corporativa interna e externa em instituições financeiras, comércio, serviços, governos, associações, escolas, universidades, hospitais, aeroportos, indústrias, meios de comunicação e entretenimento, sites internet, enfim, onde houver aplicações interagindo com seus usuários.

#Estrutura do projeto
O projeto compõe-se de duas partes: Servidor e Cliente.

No lado Servidor, em ambiente PHP/MySql, encontram-se os módulos web para "gestão de canais" de informações, utilizados pelos desenvolvedores de sistemas para criação e manutenção desses canais, assim como, os módulos que compõem o webservice para atendimento às demandas dos canais de aplicações e dos dispositivos móveis. Além dos módulos web, os desenvolvedores dispõe também, por meio de download, das APIs para apoio ao processo de comunicação entre suas aplicações e o webservice. Essas APIs estão disponíveis inicialmente em PHP e Java. A primeira implementação desse serviço encontra-se disponível no endereço "www.followzup.com".

No lado Cliente, encontra-se o APP para dispositivos Android (Java). Com esse aplicativo, o usuário dispõe das funções de "gestão de assinaturas" de canais e recebimento de mensagens provenientes das aplicações. O lado Cliente também dispõe de API (Classe Fzup) para apoio ao desenvolvimento de novos APPs com diferentes "skins" ou outros dispositivos que disponham dos protocolos de criptografia compatíveis com o serviço (quem sabe um dia estaremos recebendo mensagens na tela de um forno de micro-ondas?).

A comunicação entre as aplicações e o serviço Followzup, bem como entre o serviço Followzup e os dispositivos móveis, são realizadas na modalidade assíncrona, o que demanda consumo reduzido dos recursos de rede.

Os códigos fontes de ambas as partes do projeto (Cliente e Servidor), inclusive das APIs, estão publicadas nesse Git e disponíveis sob licença GPL.

#Participantes
Existem 3 maneiras de fazer uso ou participar do projeto Followzup.

1. Usuários finais - São aqueles que instalam o APP em seus dispositivos móveis para receber mensagens dos canais de aplicações dos quais são assinantes. A instalação deve ser realizada em dispositivos compatíveis com os APPs disponíveis.

2. Gestor de Canais de Informações - São os desenvolvedores que adaptam suas aplicações para fazerem uso dos serviços disponíveis no endereço "www.followzup.com", para que suas aplicações possam enviar de mensagens a seus usuários.

3. Participantes do Projeto - São aqueles que envolvem-se no desenvolvimento do projeto em si, seja nos módulos servidor ou cliente. Essa participação pode ser realizada com melhoria do código, criação de APIs para outras linguagens, criação de novos APPs para outros tipos de dispositivos, ou até no aprimoramento dos protocolos de comunicação para expansão dos serviços.

#Roteiro de uso do serviço
A seguir, as etapas necessárias para envio de mensagens por meio do Followzup a partir de um site Internet ou uma aplicação corporativa qualquer:

1. O desenvolvedor cria um canal de informações no Followzup, faz o download da API (PHP ou Java), contendo chave pública RSA criada exclusivamente para o canal e inclui em seu sistema os comandos necessários para enviar as mensagens.

2. Os usuários do sistema instalam em seus dispositivos móveis o aplicativo Followzup (disponível no Google-Play) e fazem a assinatura do canal criado pelo desenvolvedor, permitindo assim receber mensagens provenientes do site Internet ou aplicação associada ao canal.

3. Os sites Internet e as aplicações enviam mensagens a seus usuários por meio do webservice Followzup, que são transmitidas aos assinantes do canal por meio do APP instalado em seus dispositivos.

#Exemplos de uso

Pela praticidade do modelo, relacionamos várias de suas utilidades. No aspecto gerencial, em relação ao monitoramento de recursos, podemos configurar nossos sistemas para enviar mensagens ao administrador de um website quando alguém utiliza a página "Fale Conosco", ou ao gerente de produção quando o processo crítico de determinado sistema sofre algum tipo de interrupção, ou à equipe de suporte quando um circuito de dados sai fora do ar, ou a um funcionário de vendas quando recebe o e-mail urgente de um cliente, ou a um gerente de compras quando o estoque de determinado produto alcança seu ponto crítico, enfim, tudo que precisamos gerenciar.

Pela segurança do processo, podemos também relacionar outras utilidades, tais como o envio de códigos de barras para pagamento de contas, confirmação de pedidos, agendamento de audiências e compromissos, alertas de compras realizadas com cartões de crédito, confirmações de trocas de senhas, avisos de ligações não atendidas, resultados de exames clínicos, movimentações financeiras, avisos de vencimento de apólices de seguros, alertas de alterações de cadastros, avisos de interrupção de serviços, rastreamento de encomendas, recalls de veículos, enfim, tudo que precisamos ser informados.

Por sua conveniência, outras utilidades podem ser exemplificadas, tais como: previsões astrológicas, salmos, tábuas de marés, avisos de estréias em cinemas e teatros, avisos de promoções de passagens aéreas, notícias sobre temas específicos (clipping), avisos de liquidações, avisos de ofertas de produtos em sites de compras, resultados de loterias, confirmações de vôos, notas de provas, calendários de eventos, enfim, tudo que possa nos auxiliar em nosso dia a dia. 

#Protocolos abertos
A comunicação entre os canais de aplicações e o serviço Followzup é estabelecida por meio do módulo webservice denominado "wschannel.php". Essas solicitações são submetidas no formato XML, as quais são criptografadas com a chave pública (contida na API), criada exclusivamente para o cada canal. O string XML contém basicamente o comando a ser executado e os parâmetros necessários para sua execução. A descrição completa do conteúdo do string XML no wiki deste projeto.

O mesmo conceito estabelece a comunicação entre os dispositivos móveis e o serviço Followzup, desta vez por meio do módulo webservice denominado "wsdevice.php". Essas solicitações também são submetidas no formato XML, as quais são criptografadas com uma chave pública criada exclusivamente para cada dispositivo móvel. O string XML contém basicamente o comando a ser executado e os parâmetros necessários para sua execução. A descrição completa do conteúdo do string XML também está descrito no wiki deste projeto.

#Comunicação criptografada
Embora os processos de comunicação sejam implementados por meio de protocolos abertos, o modelo preserva a integridade e a confidencialidade dos dados por meio dos processos de criptografia AES e RSA.

Cada vez que um canal ou um dispositivo móvel é criado, o sistema cria um par de chaves assimétricas (RSA), composta por uma chave pública e uma chave privada. A chave privada é mantida na base de dados do servidor Followzup e utilizada para decriptografar os string de solicitações (XML) e criptografar os string as respostas (XML).

As chaves públicas dos canais são inseridas na API no momento em que o desenvolvedor faz o downlaod dessa API (PHP ou Java). No caso dos dispositivos, a chave pública é enviada ao APP dentro de um string de resposta XML, quando o APP solicita o registro do dispositivo no sistema. 

Com esse modelo, o desenvolvedor tem certeza de que ninguém poderá enviar solicitações ao webservice em nome de sua aplicação, a menos que tenha ocorrido alguma falha de segurança no armazenamento da API. Da mesma forma, o usuário do dispositivo móvel pode ter certeza de que ninguém poderá enviar solicitações em seu nome, a menos que tenha ocorrido alguma falha de segurança no armazenamento da chave pública de seu dispositivo móvel.

No caso de vazamento da chave pública, o desenvolvedor pode solicitar a criação de um novo par de chaves e refazer o download da API. No caso do dispositivo móvel, o usuário pode refazer o registro de seu dispositivo, ocasião em que recebe uma nova chave pública para seu dispositivo.

A seguir, as etapas que estabelecem a comunicação entre um canal de informações (aplicação do desenvolvedor) ou um dispositivo móvel, com o respectivo webservice:

1. A aplicação (ou o dispositivo móvel), cria um string XML contendo a solicitação a ser enviada ao webservice;
2. A API cria uma chave AES aleatória e criptografa o string XML por meio do protocolo de criptografia AES;
3. A API criptografa a chave AES com a chave pública do canal (ou do dispositivo), com protocolo de criptografia RSA;
4. A API envia o XML e a chave AES (ambos criptografados), para o webservice;
5. O webservice decriptografa a chave AES com a chave privada correspondente, por meio do protocolo RSA;
6. Com a chave AES decriptografada, o webservice decriptografa o string XML por meio do protocolo AES;
7. O webservice processa a solicitação contida no string XML e cria outra string XML contendo a resposta da solicitação;
8. O webservice criptografa o string XML de resposta com a mesma chave AES recebida, por meio do protocolo AES;
9. O webservice retorna para a aplicação (ou dispositivo móvel), apenas o string XML de resposta criptografado;
10. A aplicação (ou o dispositivo móvel), decriptografa o string XML de resposta por meio do protocolo AES.

#Documentação
A documentação do projeto está assim distribuída:

1. No próprio website do serviço (www.followzup.com) - Dirigida aos gestores de canais de informações e usuários de dispositivos móveis. Nessa documentação, não são aprofundados os detalhes do projeto, mas tão somente os exemplos de uso das APIs e as adaptações necessárias nos sistemas para viabilizar a comunicação com o webservice.

2. Wiki do GitHub - Dirigida aos participantes de seu desenvolvimento, gestores interessados na viabilização de novas implementações do serviço ou simplesmente interessados em conhecer as soluções técnicas adotadas.

#Arquivos e diretórios

1. Arquivo "fzup.java" - Classe Java utilizada na implememtação da interface Android (API). O detalhamento da classe está contido no wiki do projeto.

2. Arquivo "tbfollowzup.sql" - Contém diretivas para criação das tabelas e índices necessários ao serviço (MySql). O detalhamento do arquivo está contido no wiki do projeto.

3. Diretório "www" - Contém os scripts PHP do servidor, scripts PHP do webservice, arquivos CSS, imagens e outros recursos necessários à implementação do website. O detalhamento dos arquivos encontra-se no wiki do projeto.

#To do

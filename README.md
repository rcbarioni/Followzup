# Followzup
Projeto de comunicação criptografada entre aplicações e dispositivos móveis

# Bem vindo ao projeto Followzup
Followzup é um sistema que implementa o conceito Business-to-People (B2P) para encaminhamento de mensagens de texto, onde de um lado encontram-se os sites Internet e sistemas corporativos enviando mensagens, e do outro lado encontram-se seus usuários e gestores recebendo essas mensagens.

Desde uma pequena aplicação de mala direta implementada em uma rede doméstica, até um grande portal de informações, o envio de mensagens é realizado "em lote" por intermédio do webservice Followzup, que redireciona as mensagens para os dispositivos móveis de seus usuários.

Tanto do lado das aplicações e sites Internet quanto do lado dos dispositivos móveis dos usuário, a comunicação é criptografada por meio dos protocolos AES e RSA (chaves assimétricas), promovendo confidencialidade em todo trajeto percorrido pela informação.

A privacidade, outro ponto forte do modelo, também é garantida aos usuários, pois esses podem escolher de quem desejam receber mensagens. Essa garantia é obtida pela adoção de "canais" de informações, criados e mantidos pelos desenvolvedores de sistemas e que são "assinados" pelos usuários conforme a conveniência.

Tanto em canais públicos como privados, o conceito de comunicação por meio do Followzup pode ainda agregar novas funcionalidades aos processos de comunicação corporativa interna e externa em instituições financeiras, comércio, serviços, governos, associações, escolas, universidades, hospitais, aeroportos, indústrias, meios de comunicação e entretenimento, sites internet, enfim, onde houver aplicações interagindo com seus usuários.

#Estrutura básica do projeto
O projeto compõe-se de duas partes: Cliente e Servidor.

No lado Servidor, contruído no ambiente PHP/MySql, encontram-se principalmente os módulos web para gestão de canais de informações, os quais são utilizados pelos gestores de sistemas para criação e manutenção desses canais. Além dos módulos web, os desenvolvedores dispõe também, por meio de download, das APIs de apoio para comunicação entre o servidor e suas aplicações. Essas APIs estão disponíveis em PHP e Java. A primeira implementação desse serviço encontra-se disponível no endereço "www.followzup.com". 

No lado Cliente, encontra-se o APP para dispositivos Android (Java). Com esse aplicativo, o usuário dispõe das funções de gestão de assinaturas de canais e recebimento de mensagens provenientes das aplicações. O lado Cliente também dispõe de uma API (Classe Fzup) para apoio ao desenvolvimento de novos APPs com diferentes "skins" ou outros dispositivos que disponham dos protocolos de criptografia compatíveis com o serviço (quem sabe um dia estaremos recebendo mensagens na tela de um forno de micro-ondas?).

As comunicações entre as aplicações e o Servidor, bem como entre os Servidor e os dispositivos móveis, são realizadas de forma assíncrona, o que demanda consumo reduzido dos recursos de rede.

Os códigos fontes de ambas as partes, inclusive das APIs, estão publicadas nesse Git e disponíveis sob licença a GPL.

#Roteiro de uso do serviço
A seguir, as etapas necessárias para envio de mensagens por meio do Followzup a partir de um site Internet ou uma aplicação corporativa qualquer:

O desenvolvedor cria um canal de informações no Followzup, faz o download da API (PHP ou Java), contendo chave pública RSA criada exclusivamente para o canal e inclui em seu sistema os comandos necessários para enviar as mensagens.

Os usuários do sistema instalam em seus dispositivos móveis o aplicativo Followzup (disponível no Google-Play) e fazem a assinatura do canal criado pelo desenvolvedor, permitindo assim receber mensagens provenientes do site Internet ou aplicação associada ao canal.

Os sites Internet e as aplicações enviam mensagens a seus usuários por meio do webservice Followzup, que são transmitidas aos assinantes do canal por meio do APP instalado em seus dispositivos.

#Exemplos de uso

Pela praticidade do modelo, relacionamos várias de suas utilidades. No aspecto gerencial, em relação ao monitoramento de recursos, podemos configurar nossos sistemas para enviar mensagens ao administrador de um website quando alguém utiliza a página "Fale Conosco", ou ao gerente de produção quando o processo crítico de determinado sistema sofre algum tipo de interrupção, ou à equipe de suporte quando um circuito de dados sai fora do ar, ou a um funcionário de vendas quando recebe o e-mail de um cliente, ou a um gerente de compras quando o estoque de determinado produto alcança seu ponto crítico, enfim, tudo que precisamos gerenciar.

Pela segurança do processo, podemos também relacionar outras utilidades, tais como o envio de códigos de barras para pagamento de contas, confirmação de pedidos, agendamento de audiências e compromissos, alertas de compras realizadas com cartões de crédito, confirmações de trocas de senhas, avisos de ligações não atendidas, resultados de exames clínicos, movimentações financeiras, avisos de vencimento de apólices de seguros, alertas de alterações de cadastros, avisos de interrupção de serviços, rastreamento de encomendas, recalls de veículos, enfim, tudo que precisamos ser informados.

Por sua conveniência, outras utilidades podem ser exemplificadas, tais como: previsões astrológicas, salmos, tábuas de marés, avisos de estréias em cinemas e teatros, avisos de promoções de passagens aéreas, notícias sobre temas específicos (clipping), avisos de liquidações, avisos de ofertas de produtos em sites de compras, resultados de loterias, confirmações de vôos, notas de provas, calendários de eventos, enfim, tudo que possa nos auxiliar em nosso dia a dia. 

#Protocolos abertos
A comunicação entre as aplicações e o Servidor é estabelecida por meio do módulo webservice denominado "wschannel.php". Essas solicitações são submetidas no formato XML, as quais são criptografadas com a chave pública (contida na API), criada exclusivamente para o cada canal. O string XML contém basicamente o comando a ser executado e os parâmetros necessários para sua execução. A descrição completa do string XML está descrito adiante.

O mesmo conceito estabelece a comunicação entre os dispositivos móveis e o Servidor, desta vez por meio do módulo webservice denominado "wsdevice.php". Essas solicitações também são submetidas no formato XML, as quais são criptografadas com uma chave pública criada exclusivamente para cada dispositivo móvel. O string XML contém basicamente o comando a ser executado e os parâmetros necessários para sua execução. A descrição completa do string XML está descrito adiante.

#Comunicação criptografada
Embora os processos de comunicação sejam implementados por meio de protocolos abertos, o modelo preserva a integridade e a confidencialidade dos dados por meio dos processos de criptografia AES e RSA.

Cada vez que um canal ou um dispositivo móvel é criado, o sistema cria um par de chaves assimétricas (RSA), composta por uma chave pública e uma chave privada. A chave privada é mantida na base de dados e utilizada para decriptografar os string de solicitações (XML) e criptografar os string as respostas (XML).

As chaves públicas dos canais são inseridas na API no momento em que o desenvolvedor faz o downlaod da API (PHP ou Java). No caso dos dispositivos, a chave pública é enviada por meio do APP dentro de um string de resposta XML, quando da solicitação de registro do dispositivo. 

Com esse modelo, o desenvolvedor tem certeza de que ninguém poderá enviar solicitações ao webservice em nome da aplicação que mantém, a menos que ocorra alguma falha de segurança no armazenamento da API. Da mesma forma, o usuário do dispositivo móvel por ter certeza de que ninguém poderá enviar solicitações em seu nome, a menos que ocorra alguma falha de segurança no uso de sua chave pública armazenada em seu dispositivo móvel.

A seguir, as etapas que estabelecem a comunicação entre um canal de informações (aplicação do desenvolvedor) ou um dispositivo móvel, com o webservice:

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


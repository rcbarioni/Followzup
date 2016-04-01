# Followzup
Projeto de comunicação criptografada entre aplicações e dispositivos móveis

# Bem vindo ao projeto Followzup
Followzup é um sistema que implementa o conceito Business-to-People (B2P) para encaminhamento de mensagens de texto, onde de um lado encontram-se os sites Internet e sistemas corporativos enviando mensagens, e do outro lado encontram-se seus usuários e gestores recebendo essas mensagens, de forma semelhante ao envio de mensagens por SMS.

Desde uma pequena aplicação de mala direta implementada em uma rede doméstica, até um grande portal de informações, o envio de mensagens é realizado "em lote" por intermédio do webservice Followzup, que redireciona as mensagens para os dispositivos móveis de seus usuários.

Tanto do lado das aplicações e sites Internet quanto do lado dos dispositivos móveis dos usuário, a comunicação é criptografada por meio dos protocolos AES e RSA (chaves assimétricas), promovendo confidencialidade em todo trajeto percorrido pela informação.

A privacidade, outro ponto forte do modelo, também é garantida aos usuários, pois esses podem escolher de quem desejam receber mensagens, o que não ocorre com mensagens por SMS. Essa garantia é obtida pela adoção de "canais" de informações, criados e mantidos pelos desenvolvedores de sistemas e que são "assinados" pelos usuários conforme a conveniência.

Tanto em canais públicos como privados, o conceito de comunicação por meio do Followzup pode ainda agregar novas funcionalidades aos processos de comunicação corporativa interna e externa em instituições financeiras, comércio, serviços, governos, associações, escolas, universidades, hospitais, aeroportos, indústrias, meios de comunicação e entretenimento, sites internet, enfim, onde houver aplicações interagindo com seus usuários.

#Estrutura básica
O projeto compõe-se de duas partes: Cliente e Servidor.

No lado Servidor, contruído no ambiente PHP/MySql, encontram-se principalmente os módulos web para gestão de canais de informações, os quais são utilizados pelos gestores de sistemas para criação e manutenção desses canais. Além dos módulos web, os desenvolvedores dispõe também, por meio de download, das APIs de apoio para comunicação entre as aplicações e o servidor. Essas APIs estão disponíveis em PHP e Java. A primeira implementação desse serviço encontra-se disponível no endereço "www.followzup.com". 

No lado Cliente, encontra-se o APP para dispositivos Android (Java). Com esse aplicativo, o usuário dispõe das funções de gestão de assinaturas de canais e recebimento de mensagens provenientes das aplicações. O lado Cliente também dispõe de uma API (Classe Fzup) para apoio ao desenvolvimento de APPs com diferentes "skins" ou quaisquer outros dispositivos que disponham dos protocolos de criptografia compatíveis com o serviço.

A comunicação entre as aplicações e o Servidor Followzup, bem como entre os Servidor Followzup e os dispositivos móveis, são realizados de forma assíncrona, o que demanda consumo reduzido dos recursos de rede.

Os códigos fontes de ambas as partes, inclusive das APIs, estão publicadas nesse Git e disponíveis sob licença a GPL.

#Roteiro de uso do serviço
A seguir, as etapas necessárias para envio de mensagens por meio do Followzup a partir de um site Internet ou uma aplicação corporativa qualquer:

O desenvolvedor cria um canal de informações no Followzup, faz o download da API (PHP ou Java), contendo chave pública RSA criada exclusivamente para o canal e inclui em seu sistema os comandos necessários para enviar as mensagens.

Os usuários do sistema instalam em seus dispositivos móveis o aplicativo Followzup (disponível no Google-Play) e fazem a assinatura do canal criado pelo desenvolvedor, permitindo assim receber mensagens provenientes do site Internet ou aplicação associada ao canal.

Os sites Internet e as aplicações enviam mensagens a seus usuários por meio do webservice Followzup, que são transmitidas aos assinantes do canal por meio do APP instalado em seus dispositivos.


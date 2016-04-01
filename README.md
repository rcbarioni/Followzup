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
No lado do Servidor, contruído no ambiente PHP/MySql, encontram-se os módulos de gestão de canais de informações, os quais são utilizados pelos gestores de sistemas para criação e manutenção desses canais. A primeira implementação desse servidor encontra-se disponível no endereço "www.followzup.com".

# Followzup
Comunicação criptografada para a Internet das coisas

Bem-vindo ao Followzup.

O Followzup é um serviço gratuito para envio de mensagens de texto criptografadas para celulares e outros dispositivos móveis.

As mensagens são enviadas em modo batch, a partir de sistemas e websites com o auxílio de APIs, disponíveis atualmente em PHP e Java.

As APIs são simples de usar, e com apenas 1 linha de comando a aplicação pode enviar uma mensagem para o celular de um ou mais usuários.

Além de enviar, os websites Internet também podem receber mensagens dos usuários, originadas a partir de seus dispositivos móveis.

A comunicação entre sistemas e dispositivos móveis por meio do Followzup é criptografada com os padrões AES e RSA, e o protocolo aberto do serviço permite o desenvolvimento de APIs para outras linguagens, assim como o desenvolvimento de APPs para diferentes modelos de dispositivos móveis. 



Exemplos de uso:

Monitorar a atividade de usuários em sistemas e websites;
Monitorar e enviar alertas sobre ocorrências em sistemas e equipamentos;
Enviar notícias, dicas, avisos e mensagens publicitárias;
Receber mensagens de solicitações dos usuários;
Solicitar respostas de confirmação dos usuários;
Enviar mensagens associadas a “links” externos;
Informar e confirmar agendamento de compromissos;
Informar a realização transações comerciais e financeiras;
E muito mais...



Roteiro de uso:

A seguir, as etapas necessárias para enviar mensagens por meio do Followzup a partir de qualquer sistema ou website:

- No site do Followzup, o administrador do sistema cria um canal de informações, faz o download da API contendo chave criptográfica do canal e inclui em seu sistema as chamadas para a API.

- No dispositivo móvel, o usuário instala o APP Followzup disponível na loja de aplicativos, registra o dispositivo no sistema, faz a pesquisa do canal de informações criado pelo desenvolvedor e confirma a assinatura do canal.

- Após o registro do dispositivo móvel e a assinatura do canal, os sistemas e websites podem enviar mensagens por meio de solicitações ao webservice Followzup, informando o destinatário da mensagem.



Para saber mais, acesse o wiki do projeto.

www.followzup.com/wiki

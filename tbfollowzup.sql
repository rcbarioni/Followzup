
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

    SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

    DROP TABLE IF EXISTS control;

    CREATE TABLE control (

        idcontrol       int           unsigned not null default 0,
        chnstatus       char(1)       collate utf8_general_ci not null default 'a',
        intstatus       char(1)       collate utf8_general_ci not null default 'a',
        seqzup          int           unsigned not null default 0,
        seqzop          int           unsigned not null default 0,

    /* chnstatus/intstatus: (a)active */

        PRIMARY KEY (idcontrol) )
        ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

# ========================================================================================

    DROP TABLE IF EXISTS log;

    CREATE TABLE log (

        idlog           bigint        unsigned not null auto_increment,
        dateincl        timestamp     not null default 0,
        idagent         int           unsigned not null default 0,
        ipuser          char(15)      collate utf8_general_ci not null default '',
        iduser          varchar(12)   collate utf8_general_ci not null default '',
        idchannel       varchar(12)   collate utf8_general_ci not null default '',
        iddevice        varchar(12)   collate utf8_general_ci not null default '',
        idinterface     varchar(12)   collate utf8_general_ci not null default '',
        channelseq      int           unsigned not null default 0,
        deviceseq       int           unsigned not null default 0,
        operation       smallint      unsigned not null default 0,
        param           varchar(200)  collate utf8_general_ci not null default '',

        PRIMARY KEY (idlog) )
        ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

# ========================================================================================

    DROP TABLE IF EXISTS agents;

    CREATE TABLE agents (

        idagent         int           unsigned not null auto_increment,
        agent           varchar(300)  collate utf8_general_ci not null default '',
        dateincl        timestamp     not null default 0,

        PRIMARY KEY (idagent) )
        ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

    /* pesquisa agent pelo nome para verificar duplicidade */
    ALTER TABLE agents ADD UNIQUE INDEX (agent);

# ========================================================================================

    DROP TABLE IF EXISTS users;

    CREATE TABLE users (

        iduser          char(12)      collate utf8_general_ci not null default '',
        email           varchar(270)  collate utf8_general_ci not null default '',
        pass            char(32)      collate utf8_general_ci not null default '',
        daterescue      timestamp     not null default 0,
        dateincl        timestamp     not null default 0,
        datetry         timestamp     not null default 0,
        name            varchar(80)   collate utf8_general_ci not null default '',
        maxchannels     smallint      unsigned not null default 10,
        regstatus       char(1)       collate utf8_general_ci not null default '',

        /* regstatus:   (n)new, (a)active, (d)deleted */

        PRIMARY KEY (iduser) )
        ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

    /* pesquisa email para verificar duplicidade */
    ALTER TABLE users ADD UNIQUE INDEX (email);

# ========================================================================================

    DROP TABLE IF EXISTS channels;

    CREATE TABLE channels (

        idchannel       char(12)      collate utf8_general_ci not null default '',
        iduser          char(12)      collate utf8_general_ci not null default '',
        tag             varchar(77)   collate utf8_general_ci not null default '',
        briefing        varchar(1024) collate utf8_general_ci not null default '',
        welcome         varchar(1024) collate utf8_general_ci not null default '',
        dateincl        timestamp     not null default 0,
        datetransf      timestamp     not null default 0,
        newiduser       char(12)      collate utf8_general_ci not null default '',
        channelseq      int           unsigned not null default 0,
        channeltype     char(1)       collate utf8_general_ci not null default '',
        privcode        char(8)       collate utf8_general_ci not null default '',
        idkey           char(12)      collate utf8_general_ci not null default '',
        md5icon         char(32)      collate utf8_general_ci not null default '',
        welcomeurl      varchar(200)  collate utf8_general_ci not null default '',
        responseurl     varchar(200)  collate utf8_general_ci not null default '',
        channelicon     text          collate utf8_general_ci,
        regstatus       char(1)       collate utf8_general_ci not null default '',

        /* channeltype: (r)private, (u)public  
           regstatus:   (a)active,  (s)suspended, (d)deleted */

        PRIMARY KEY (idchannel) )
        ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

    /* pesquisa Tag para verificar duplicidade */
    ALTER TABLE channels ADD UNIQUE INDEX (tag);

    /* obter lista de canais do desenvolvedor */
    ALTER TABLE channels ADD UNIQUE INDEX (iduser, idchannel);

# ========================================================================================

    DROP TABLE IF EXISTS pkeys;

    CREATE TABLE pkeys (

        idkey           char(12)      collate utf8_general_ci not null default '',
        idchannel       char(12)      collate utf8_general_ci not null default '',
        dateincl        timestamp     not null default 0,

        pkpub           varchar(1024) collate utf8_general_ci not null default '',
        pkpri           varchar(3072) collate utf8_general_ci not null default '',
        pkmod           varchar(512)  collate utf8_general_ci not null default '',
        pkpux           varchar(512)  collate utf8_general_ci not null default '',
        pkprx           varchar(512)  collate utf8_general_ci not null default '',
        pkpr1           varchar(512)  collate utf8_general_ci not null default '',
        pkpr2           varchar(512)  collate utf8_general_ci not null default '',
        pkdmp           varchar(512)  collate utf8_general_ci not null default '',
        pkdmq           varchar(512)  collate utf8_general_ci not null default '',
        pkiqm           varchar(512)  collate utf8_general_ci not null default '',

        PRIMARY KEY (idkey) )
        ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

# ========================================================================================

    DROP TABLE IF EXISTS devices;

    CREATE TABLE devices (

        iddevice        char(12)      collate utf8_general_ci not null default '',
        idinterface     char(12)      collate utf8_general_ci not null default '',
        iduser          char(12)      collate utf8_general_ci not null default '',
        devicetag       varchar(46)   collate utf8_general_ci not null default '',
        dateincl        timestamp     not null default 0,
        lastact         timestamp     not null default 0,
        deviceseq       int           unsigned not null default 0,
        regstatus       char(1)       collate utf8_general_ci not null default '',

        pkpub           varchar(1024) collate utf8_general_ci not null default '',
        pkpri           varchar(3072) collate utf8_general_ci not null default '',
        pkmod           varchar(512)  collate utf8_general_ci not null default '',
        pkpux           varchar(512)  collate utf8_general_ci not null default '',
        pkprx           varchar(512)  collate utf8_general_ci not null default '',
        pkpr1           varchar(512)  collate utf8_general_ci not null default '',
        pkpr2           varchar(512)  collate utf8_general_ci not null default '',
        pkdmp           varchar(512)  collate utf8_general_ci not null default '',
        pkdmq           varchar(512)  collate utf8_general_ci not null default '',
        pkiqm           varchar(512)  collate utf8_general_ci not null default '',

        /* regstatus:   (a)active, (d)deleted */

        PRIMARY KEY (iddevice) )
        ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

    /* obter lista de dispositivos do usuário */
    ALTER TABLE devices ADD UNIQUE INDEX (iduser, iddevice);

    /* obter próxima Tag disponivel para registro de dispositivo do usuário */
    ALTER TABLE devices ADD UNIQUE INDEX (iduser, devicetag);

    /* obter mensagens pendentes de envio */
    ALTER TABLE devices ADD INDEX (idinterface, iduser);

# ========================================================================================

    DROP TABLE IF EXISTS interfaces;

    CREATE TABLE interfaces (

        idinterface     char(12)      collate utf8_general_ci not null default '',
        iduser          char(12)      collate utf8_general_ci not null default '',
        stamp           char(128)     collate utf8_general_ci not null default '',
        dateincl        timestamp     not null default 0,
        regstatus       char(1)       collate utf8_general_ci not null default '',

        /* regstatus:   (a)active, (t)testing, (d)deleted */

        PRIMARY KEY (idinterface) )
        ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

# ========================================================================================

    DROP TABLE IF EXISTS subscriptions;

    CREATE TABLE subscriptions (

        iduser          char(12)      collate utf8_general_ci not null default '',
        idchannel       char(12)      collate utf8_general_ci not null default '',
        dateincl        timestamp     not null default 0,
        datetry         timestamp     not null default 0,
        subscode        int           unsigned not null default 0,
        regstatus       char(1)       collate utf8_general_ci not null default '',

        /* regstatus:   (a)active, (d)deleted */

        PRIMARY KEY (iduser, idchannel) )
        ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

    /* obter lista de assinantes do canal */
    ALTER TABLE subscriptions ADD UNIQUE INDEX (idchannel, iduser);

# ========================================================================================

    DROP TABLE IF EXISTS messages;

    CREATE TABLE messages (

        idmessage       bigint        unsigned not null auto_increment,
        iduser          char(12)      collate utf8_general_ci not null default '',
        idchannel       char(12)      collate utf8_general_ci not null default '',
        mediamd5        char(32)      collate utf8_general_ci not null default '',
        dateincl        timestamp     not null default 0,
        dateterm        timestamp     not null default 0,
        hours           smallint      unsigned not null default 0,
        regstatus       char(1)       collate utf8_general_ci not null default '',

        /* regstatus:   (p)pending, (s)sent, (c)canceled-by-channel, (d)deleted-by-user */

    PRIMARY KEY (idmessage) )
    ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

    /* obter mensagens pendentes de envio */
    ALTER TABLE messages ADD INDEX (iduser, idchannel);

# ========================================================================================

    DROP TABLE IF EXISTS medias;

    CREATE TABLE medias (

        idchannel       char(12)      collate utf8_general_ci not null default '',
        mediamd5        char(32)      collate utf8_general_ci not null default '',
        mediatext       text          collate utf8_general_ci,
        mediaurl        varchar(200)  collate utf8_general_ci not null default '',

        PRIMARY KEY (idchannel, mediamd5) )
        ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

# ========================================================================================

    DROP TABLE IF EXISTS contacts;

    CREATE TABLE contacts (

        idcontact       int           unsigned not null auto_increment,
        dateincl        timestamp     not null default 0,
        email           varchar(256)  collate utf8_general_ci not null default '',
        name            varchar(80)   collate utf8_general_ci not null default '',
        message         varchar(2048) collate utf8_general_ci not null default '',

        PRIMARY KEY (idcontact) )
        ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

# ========================================================================================

    DROP TABLE IF EXISTS responses;

    CREATE TABLE responses (

        idresponse      int           unsigned not null auto_increment,
        idchannel       char(12)      collate utf8_general_ci not null default '',
        iduser          char(12)      collate utf8_general_ci not null default '',
        dateincl        timestamp     not null default 0,
        response        varchar(60)   collate utf8_general_ci not null default '',

        PRIMARY KEY (idresponse) )
        ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

    /* obter lista de respostas do canal */
    ALTER TABLE responses ADD INDEX (idchannel, dateincl);

# ========================================================================================

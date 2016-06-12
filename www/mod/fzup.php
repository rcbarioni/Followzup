<?php

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

    class fzup_#idchannel# {

        public $fzup_channel  = "#idchannel#";
        public $fzup_lastseq  = 0;
        public $fzup_pubkey   = "";
        public $fzup_pubkey64 = "#pubkey64#";

        public function __construct() {

            $this->fzup_pubkey = base64_decode($this->fzup_pubkey64);

        }

        public function decrypt ($fzup_encryptURL) {

            $fzup_encrypt = base64_decode(urldecode($fzup_encryptURL));

            openssl_public_decrypt($fzup_encrypt, $fzup_decrypt, $this->fzup_pubkey);

            return $fzup_decrypt;

        }

        public function submit ($fzup_tab) {

            // extract parameters
            $fzup_param["FZUP_COMMAND"]  = ""; 
            $fzup_param["FZUP_LASTSEQ"]  = 0; 
            $fzup_param["FZUP_USER"]     = ""; 
            $fzup_param["FZUP_SUBSCODE"] = ""; 
            $fzup_param["FZUP_HOURS"]    = 0; 
            $fzup_param["FZUP_MSGTEXT"]  = ""; 
            $fzup_param["FZUP_MSGURL"]   = ""; 

            for ( $i=0; $i<count($fzup_tab); $i++ ) {

                $fzup_token = explode ("=",$fzup_tab[$i],2);

                if     ( trim($fzup_token[0]) == "FZUP_COMMAND"   ) $fzup_param["FZUP_COMMAND"]  = trim($fzup_token[1]); 
                elseif ( trim($fzup_token[0]) == "FZUP_LASTSEQ"   ) $fzup_param["FZUP_LASTSEQ"]  = trim($fzup_token[1]); 
                elseif ( trim($fzup_token[0]) == "FZUP_USER"      ) $fzup_param["FZUP_USER"]     = trim($fzup_token[1]); 
                elseif ( trim($fzup_token[0]) == "FZUP_SUBSCODE"  ) $fzup_param["FZUP_SUBSCODE"] = trim($fzup_token[1]); 
                elseif ( trim($fzup_token[0]) == "FZUP_HOURS"     ) $fzup_param["FZUP_HOURS"]    = trim($fzup_token[1]); 
                elseif ( trim($fzup_token[0]) == "FZUP_MSGTEXT"   ) $fzup_param["FZUP_MSGTEXT"]  = trim($fzup_token[1]); 
                elseif ( trim($fzup_token[0]) == "FZUP_MSGURL"    ) $fzup_param["FZUP_MSGURL"]   = trim($fzup_token[1]); 

            }

            // convert message and URL to base64
            $fzup_param["FZUP_MSGTEXT"] = base64_encode($fzup_param["FZUP_MSGTEXT"]);
            $fzup_param["FZUP_MSGURL"]  = base64_encode($fzup_param["FZUP_MSGURL"]);

            // build request
            if     ( $fzup_param["FZUP_COMMAND"] == "chck" ) $fzup_xml = "<usr>" . $fzup_param["FZUP_USER"]     . "</usr>" . 
                                                                         "<sub>" . $fzup_param["FZUP_SUBSCODE"] . "</sub>";

            elseif ( $fzup_param["FZUP_COMMAND"] == "smsg" ) $fzup_xml = "<usr>" . $fzup_param["FZUP_USER"]     . "</usr>" . 
                                                                         "<hrs>" . $fzup_param["FZUP_HOURS"]    . "</hrs>" .
                                                                         "<msg>" . $fzup_param["FZUP_MSGTEXT"]  . "</msg>" .
                                                                         "<url>" . $fzup_param["FZUP_MSGURL"]   . "</url>";

            else return array ( "6103", $this->fzup_lastseq, "<" . '?xml version="1.0" encoding="utf-8"?' . "><followzup></followzup>" );

            if ( (int)$fzup_param["FZUP_LASTSEQ"] > 0 ) $this->fzup_lastseq = (int)$fzup_param["FZUP_LASTSEQ"];

            do {

                // set next sequence and build frame request (xml)
                $this->fzup_lastseq = $this->fzup_lastseq + 1;
                $fzup_frame1  = "<" . '?xml version="1.0" encoding="utf-8"?' . "><followzup>";
                $fzup_frame1 .= "<com>" . $fzup_param["FZUP_COMMAND"] . "</com>";
                $fzup_frame1 .= "<seq>" . $this->fzup_lastseq . "</seq>";
                $fzup_frame1 .= $fzup_xml . "</followzup>";

                // encrypt and encode random key and request 
                $fzup_key1 = openssl_random_pseudo_bytes(24);
                $fzup_frame2 = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $fzup_key1, $fzup_frame1, MCRYPT_MODE_CBC, str_repeat(chr(0),16));
                openssl_public_encrypt($fzup_key1, $fzup_key2, $this->fzup_pubkey);
                $fzup_frame3 = base64_encode($fzup_frame2);
                $fzup_key3 = base64_encode($fzup_key2);

                // build curl and get response 
                $fzup_ch = curl_init();
                $fzup_conf = array ( CURLOPT_URL            => "http://#server#/wschannel",
                                     CURLOPT_POST           => true,
                                     CURLOPT_RETURNTRANSFER => true,
                                     CURLOPT_USERAGENT      => "wschannel: " . $this->fzup_channel,
                                     CURLOPT_POSTFIELDS     => array ( "id" => $this->fzup_channel, "key" => "$fzup_key3", "frame" => "$fzup_frame3" ) );
                curl_setopt_array($fzup_ch, $fzup_conf);
                // curl_setopt($fzup_ch, CURLOPT_PROXY,"http_proxy=http://proxy_server:8080");
                $fzup_resp = curl_exec($fzup_ch);
                curl_close($fzup_ch);

                // decode and decrypt response 
                $fzup_respxml = @simplexml_load_string($fzup_resp);
                $fzup_retcode = $fzup_respxml->retcode;
                $fzup_retframe1 = $fzup_respxml->retframe;
                $fzup_retframe2 = base64_decode($fzup_retframe1);
                $fzup_retframe3 = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $fzup_key1, $fzup_retframe2, MCRYPT_MODE_CBC, str_repeat(chr(0),16));
                $fzup_retframe4 = @simplexml_load_string($fzup_retframe3);

                // if out of sequence, extract last used sequence
                if  ( $fzup_retcode == "6101" ) $this->fzup_lastseq = $fzup_retframe4->seq;

            // repeat request while out of sequence
            } while ( $fzup_retcode == "6101" );

            return array ( "$fzup_retcode", "$this->fzup_lastseq", "$fzup_retframe3" );

        }

    }

?>

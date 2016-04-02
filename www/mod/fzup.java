/*
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
*/

    import java.io.BufferedReader;
    import java.io.ByteArrayInputStream;
    import java.io.DataOutputStream;
    import java.io.InputStreamReader;
    import java.math.BigInteger;
    import java.net.HttpURLConnection;
    import java.net.URL;
    import java.net.URLEncoder;
    import java.security.KeyFactory;
    import java.security.interfaces.RSAPublicKey;
    import java.security.spec.AlgorithmParameterSpec;
    import java.security.spec.RSAPublicKeySpec;
    import java.util.Arrays;
    import java.util.HashMap;
    import java.util.Map;
    import java.util.Random;
    import javax.crypto.Cipher;
    import javax.crypto.spec.IvParameterSpec;
    import javax.crypto.spec.SecretKeySpec;
    import javax.xml.bind.DatatypeConverter;
    import javax.xml.parsers.DocumentBuilder;
    import javax.xml.parsers.DocumentBuilderFactory;
    import org.w3c.dom.NodeList;

    public class fzup_#idchannel# {
    
        public static String       fzup_Channel  = "#idchannel#";
        public static String       fzup_Modulus  = "#pubkeymod#";
        public static String       fzup_Expoent  = "#pubkeypux#";
        public static int          fzup_LastSeq  = 0;
        public static String       fzup_RetCode  = "";
        public static String       fzup_RetFrame = "";
        public static RSAPublicKey fzup_PubKey;
    
        public fzup_#idchannel# () throws Exception {

            // construct public key
            BigInteger wModulus = new BigInteger(fzup_Modulus,16);
            BigInteger wExpoent = new BigInteger(fzup_Expoent,16);
            RSAPublicKeySpec wkeySpec = new RSAPublicKeySpec(wModulus,wExpoent);  
            KeyFactory wkeyFactory = KeyFactory.getInstance("RSA");
            fzup_PubKey = (RSAPublicKey) wkeyFactory.generatePublic(wkeySpec); 
      
        }      
      
        public String decrypt (String wEncryptedURL) throws Exception {    

            String wEncrypted64 = URLDecoder.decode(wEncryptedURL,"UTF-8"); 
    
            byte[] bEncrypted = DatatypeConverter.parseBase64Binary(wEncrypted64);

            Cipher rsaCipher = Cipher.getInstance("RSA/ECB/PKCS1Padding");
            rsaCipher.init(Cipher.DECRYPT_MODE, fzup_PubKey);
            byte[] bDecrypted = rsaCipher.doFinal(bEncrypted);

            String wDecrypted = new String(bDecrypted);
        
            return wDecrypted;
        
        }

        public String[] submit (String[] args) throws Exception {    
      
            // extract parameters
            Map<String,String> mapArgs = new HashMap<String,String>();
      
            mapArgs.put( "FZUP_COMMAND",   ""  );
            mapArgs.put( "FZUP_LASTSEQ",   "0" );
            mapArgs.put( "FZUP_MESSAGEID", ""  );
            mapArgs.put( "FZUP_SUBSCODE",  ""  );
            mapArgs.put( "FZUP_USER",      ""  );
            mapArgs.put( "FZUP_HOURS",     "0" );
            mapArgs.put( "FZUP_MSGTEXT",   ""  );
            mapArgs.put( "FZUP_MSGURL",    ""  );
      
            for ( String arg:args ) {
                int i = arg.indexOf("=");
                if ( i >= 0 ) {
                    String key = arg.substring(0,i).trim();
                    String param = arg.substring(i+1).trim();
                    mapArgs.put(key,param);
                }
            }
      
            int seq = 0;
            try{ seq = Integer.parseInt(mapArgs.get("FZUP_LASTSEQ")); }
            catch (NumberFormatException e) { }
            if ( seq > 0 ) fzup_LastSeq = seq;
      
            // build request
            String xml = "", cmd = mapArgs.get("FZUP_COMMAND");
      
            if      ( cmd.equals("chck") ) xml = "<usr>" + mapArgs.get("FZUP_USER")     + "</usr>" + 
                                                 "<sub>" + mapArgs.get("FZUP_SUBSCODE") + "</sub>";

            else if ( cmd.equals("smsg") ) xml = "<usr>" + mapArgs.get("FZUP_USER")     + "</usr>" + 
                                                 "<hrs>" + mapArgs.get("FZUP_HOURS")    + "</hrs>" +
                                                 "<msg>" + mapArgs.get("FZUP_MSGTEXT")  + "</msg>" +
                                                 "<url>" + mapArgs.get("FZUP_MSGURL")   + "</url>";

            else return new String[] { "6103", Integer.toString(fzup_LastSeq), "<?xml version=\"1.0\" encoding=\"utf-8\"?><followzup></followzup>" };
      
            do {
      
                // set next sequence and build xml request
                fzup_LastSeq = fzup_LastSeq + 1;
                String wPlain = "<?xml version=\"1.0\" encoding=\"utf-8\"?><followzup><com>" + cmd + "</com>" + 
                                "<seq>" + Integer.toString(fzup_LastSeq) + "</seq>" + xml + "</followzup>";

                byte[] nPlain = wPlain.getBytes();        

                // padding xml text with spaces to 16 bytes block
                int nLength = nPlain.length;
                int pLength = nLength + ( 15 - ( (nLength - 1) % 16) );
                byte[] bPlain = new byte[pLength];
                Arrays.fill(bPlain,(byte)32);
                System.arraycopy(nPlain, 0, bPlain, 0, nLength);

                // generate random AES key
                byte[] bKey = new byte[24];
                new Random().nextBytes(bKey);
                SecretKeySpec sKey = new SecretKeySpec(bKey,"AES");

                // Encrypt xml text with AES key
                AlgorithmParameterSpec sVector = new IvParameterSpec(new byte[16]);
                Cipher aesCipher = Cipher.getInstance("AES/CBC/NoPadding");
                aesCipher.init(Cipher.ENCRYPT_MODE, sKey, sVector);
                byte[] bPlainEncrypted = aesCipher.doFinal(bPlain);

                // encrypt AES key with RSA PublicKey
                Cipher rsaCipher = Cipher.getInstance("RSA/ECB/PKCS1Padding");
                rsaCipher.init(Cipher.ENCRYPT_MODE, fzup_PubKey);
                byte[] bKeyEncrypted = rsaCipher.doFinal(bKey);

                // encode encrypted AES key and encrypted xml text        
                String wKey64 = DatatypeConverter.printBase64Binary(bKeyEncrypted);
                String wKeyURL = URLEncoder.encode(wKey64,"UTF-8"); 
                String wPlainEncrypted64 = DatatypeConverter.printBase64Binary(bPlainEncrypted);
                String wPlainEncryptedURL = URLEncoder.encode(wPlainEncrypted64,"UTF-8"); 

                // build post request
                String url = "http://#server#/wschannel";
                URL obj = new URL(url);
                HttpURLConnection con = (HttpURLConnection) obj.openConnection();

                // add request header 
                con.setRequestMethod("POST");
                con.setRequestProperty("User-Agent", "wschannel: " + fzup_Channel );

                String urlParameters = "id=" + fzup_Channel + "&key=" + wKeyURL + "&frame=" + wPlainEncryptedURL;
    
                // send post request 
                con.setDoOutput(true);
                DataOutputStream wr = new DataOutputStream(con.getOutputStream());
                wr.writeBytes(urlParameters);
                wr.flush();
                wr.close();

                // get post response 
                BufferedReader in = new BufferedReader(new InputStreamReader(con.getInputStream()));
                String inputLine;
                StringBuffer getPost = new StringBuffer();

                while ( (inputLine = in.readLine()) != null ) {
                    getPost.append(inputLine);
                }
                in.close();
    
                // extract response tag        
                DocumentBuilderFactory wDocFactory = DocumentBuilderFactory.newInstance();
                DocumentBuilder wDocBuilder = wDocFactory.newDocumentBuilder();
                org.w3c.dom.Document wDoc = wDocBuilder.parse(new ByteArrayInputStream(getPost.toString().getBytes()));

                // extract RETCODE 
                NodeList nRetcode = wDoc.getElementsByTagName("retcode");
                fzup_RetCode = nRetcode.item(0).getTextContent();

                // extract and decrypt RETFRAME with AES key 
                NodeList nRetframe = wDoc.getElementsByTagName("retframe");
                String wRetframeEncrypted64 = nRetframe.item(0).getTextContent();
                byte[] bRetframeEncrypted = DatatypeConverter.parseBase64Binary(wRetframeEncrypted64);
                Cipher resCipher = Cipher.getInstance("AES/CBC/NoPadding");
                resCipher.init(Cipher.DECRYPT_MODE, sKey, sVector);
                byte[] bResponse = resCipher.doFinal(bRetframeEncrypted);
                fzup_RetFrame = new String(bResponse);    

                // if out of sequence, extract last used sequence
                if ( fzup_RetCode.equals("6101") ) {

                    // extract SEQ tag        
                    DocumentBuilderFactory wDocFactory2 = DocumentBuilderFactory.newInstance();
                    DocumentBuilder wDocBuilder2 = wDocFactory2.newDocumentBuilder();
                    org.w3c.dom.Document wDoc2 = wDocBuilder2.parse(new ByteArrayInputStream(fzup_RetFrame.trim().getBytes()));

                    // extract lastseq
                    NodeList nLastseq = wDoc2.getElementsByTagName("seq");
                    String wLastseq = nLastseq.item(0).getTextContent();
                    fzup_LastSeq = Integer.parseInt(wLastseq);
        
                }    

            // repeat request while out of sequence
            } while ( fzup_RetCode.equals("6101") );
      
            return new String[] {fzup_RetCode,Integer.toString(fzup_LastSeq),fzup_RetFrame};
     
        }
 
    }

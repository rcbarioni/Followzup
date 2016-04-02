package com.fzup;

import java.io.BufferedReader;
import java.io.ByteArrayInputStream;
import java.io.ByteArrayOutputStream;
import java.io.DataOutputStream;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;
import java.io.OptionalDataException;
import java.io.Serializable;
import java.io.StreamCorruptedException;
import java.io.UnsupportedEncodingException;
import java.math.BigInteger;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.ProtocolException;
import java.net.URL;
import java.net.URLEncoder;
import java.security.InvalidAlgorithmParameterException;
import java.security.InvalidKeyException;
import java.security.KeyFactory;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.security.interfaces.RSAPublicKey;
import java.security.spec.AlgorithmParameterSpec;
import java.security.spec.InvalidKeySpecException;
import java.security.spec.RSAPublicKeySpec;
import java.text.DateFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Arrays;
import java.util.Date;
import java.util.HashMap;
import java.util.Random;
import java.util.TimeZone;
import javax.crypto.BadPaddingException;
import javax.crypto.Cipher;
import javax.crypto.IllegalBlockSizeException;
import javax.crypto.NoSuchPaddingException;
import javax.crypto.spec.IvParameterSpec;
import javax.crypto.spec.SecretKeySpec;
import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.ParserConfigurationException;
import org.w3c.dom.Element;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;
import org.xml.sax.SAXException;
import android.annotation.SuppressLint;
import android.content.ContentValues;
import android.content.Context;
import android.content.SharedPreferences;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;
import android.net.ConnectivityManager;
import android.os.StrictMode;
import android.util.Base64;

public class Fzup {
	
//  ==============================================================
//  PRIVATE VARIABLES
//  ==============================================================
	
	private String         wDebug           = "";
	private Context        wContext         = null;
	private String         wIdInterface     = "";
	private String         wStamp           = "";
	private String         wServer          = "";
	private String         wModInst64       = "";
	private String         wPuxInst64       = "";
	private String         wSubmitRetFrame  = "";
	private String         wSubmitRetIcons  = "";
	private String         wLookupArg       = "";
	private String         wLookupMore      = "";
	private int            wLookupCount     = 0;
	private FzupHelper     wHelper          = null;
	private SQLiteDatabase wDB              = null;
	private SQLiteDatabase wDBw             = null;
	private int            wNewMsgs         = 0;
	private String         wSelectTag       = "";
	private String         wSelectCode      = "";
	private String         wSelectIcon      = "";
	private String         wSelectMD5       = "";
	private String         wSelectFlag      = "";
	private RSAPublicKey   wPubKey          = null;

//  ==============================================================
//  PRIVATE ARRAYS
//  ==============================================================

	private String[][]     wTabChn          = null;  // tag, subscode, md5icon, count-unread, flag-responseurl
	private String[][]     wLookupTab       = null;  // tag, flag-private, flag-privcode, md5icon, briefing

//  ==============================================================
//  PRIVATE STATIC
//  ==============================================================
	
	private static final String  wZupIcon   = "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";
	private static final String  wZupIconPb = "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";
	private static final String  wBanner    = "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";
	
//  ==============================================================
//  SHARED PREFERENCES VARIABLES
//  ==============================================================

	SharedPreferences      wLocalPrefs      = null; 
	private String         wStringPubKey    = "";
	private String         wStringTabChn    = "";
	private int            wLastMsg         = 0;
	private int            wLastSeq         = 0;
	private int            wChannelCount    = 0;
	private String         wMD5List         = "";
	private String         wDeviceTag       = "";
	private String         wIdDevice        = "";
	private String         wIdUser          = "";
	private boolean        wRegistered      = false;

//  ==============================================================
//  CONSTRUCTOR
//  ==============================================================

	public Fzup (Context wCTX, String wINT, String wSTP, String wSRV, String wMOD, String wPUX) {
		
		wContext     = wCTX;
		wIdInterface = wINT;
		wStamp       = wSTP;
		wServer      = wSRV;
		wModInst64   = wMOD;
		wPuxInst64   = wPUX;
		
		StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
	    StrictMode.setThreadPolicy(policy);
	    
	    wHelper = new FzupHelper(wContext);
	    
	    wDB = wHelper.getReadableDatabase();
		 				
		wLocalPrefs = wContext.getSharedPreferences("Followzup_Prefs", Context.MODE_PRIVATE);

	    if ( wLocalPrefs.contains("PUBKEY") ) {
	    	
	    	wStringPubKey = wLocalPrefs.getString  ("PUBKEY","");
	    	wStringTabChn = wLocalPrefs.getString  ("TABCHN","");
	    	wLastMsg      = wLocalPrefs.getInt     ("LASTMSG",0);
	    	wLastSeq      = wLocalPrefs.getInt     ("LASTSEQ",0);
	    	wChannelCount = wLocalPrefs.getInt     ("CHANNELCOUNT", 0);
	    	wMD5List      = wLocalPrefs.getString  ("MD5LIST","");		    
	    	wDeviceTag    = wLocalPrefs.getString  ("DEVICETAG","");		    
	    	wIdDevice     = wLocalPrefs.getString  ("IDDEVICE","");		    
	    	wIdUser       = wLocalPrefs.getString  ("IDUSER","");		    
	    	wRegistered   = wLocalPrefs.getBoolean ("REGISTERED",false);

	    	wPubKey = (RSAPublicKey) stringToObject(wStringPubKey);
	    	wTabChn = (String[][]) stringToObject(wStringTabChn);
	    		    	
	    	if ( wTabChn.length > 0 ) {
	    		if ( wTabChn[0].length < 5 ) { wMD5List = ""; check(true); }
	    	}
	    	
	    }  
	    
	    else unregisterDevice();
		
	}

//  ==============================================================
//  GETTERS (VARIABLES)
//  ==============================================================
	
	public String getDebug      () { return wDebug; }
	public String getZupIcon    () { return wZupIcon; }
	public String getZupIconPb  () { return wZupIconPb; }
	public String getBanner     () { return wBanner; }
	public String getUserID     () { return wIdUser; }
	public String getDeviceTag  () { return wDeviceTag; }
	public int    getNewMsgs    () { return wNewMsgs; }
	public String getLookupArg  () { return wLookupArg; }
	public String getLookupMore () { return wLookupMore; }
	public String getSelectTag  () { return wSelectTag; }
	public String getSelectCode () { return wSelectCode; }
	public String getSelectIcon () { return wSelectIcon; }
	public String getSelectFlag () { return wSelectFlag; }
	
//  ==============================================================
//  GETTERS (DEPRECATED)
//  ==============================================================
	
	public int selectPoll(String wParam) { return 0; }
    public int getPollQuestion() { return 0; }
    public int getPollNumChoices() { return 0; }
    public int getPollMinChoices() { return 0; }
    public int getPollMaxChoices() { return 0; }
    public int getChoices() { return 0; }
    public int responsePoll(String wParam1, String wParam2) { return 0; }
	
//  ==============================================================
//  GETTERS (ARRAYS)
//  ==============================================================
	
	public String [][] getChannels () {
	
		String [][] wReturn = new String[wChannelCount][6]; 
		// tag, subscode, count-unread, icon, md5icon, flag-responseurl
		
		int wIndex = -1;
	    if ( !wDB.isOpen() ) wDB = wHelper.getReadableDatabase();
		
		for ( int i = 0; i < wChannelCount; i++ ) {
		
			if ( !wTabChn[i][3].equals("0") ) {
				
				String wIcon = wZupIconPb;
				
				String wQuery = "SELECT icon FROM icons WHERE keyicon = '" + wTabChn[i][2] + "-" + wTabChn[i][0] + "';";
				
				Cursor wCursor = wDB.rawQuery(wQuery, null);

				int wCount = wCursor.getCount();
				
				if ( wCount > 0 ) {
					
					wCursor.moveToFirst();
					wIcon = wCursor.getString(0);
					
				}
				
				wCursor.close();
							
				wIndex++;

				wReturn[wIndex][0] = wTabChn[i][0];
				wReturn[wIndex][1] = wTabChn[i][1];
				wReturn[wIndex][2] = wTabChn[i][3];
				wReturn[wIndex][3] = wIcon;
				wReturn[wIndex][4] = wTabChn[i][2];
				wReturn[wIndex][5] = wTabChn[i][4];

			}
			
		}
		
		for ( int i = 0; i < wChannelCount; i++ ) {
			
			if ( wTabChn[i][3].equals("0") ) {
				
				String wIcon = wZupIconPb;
				
				String wQuery = "SELECT icon FROM icons WHERE keyicon = '" + wTabChn[i][2] + "-" + wTabChn[i][0] + "';";
				
				Cursor wCursor = wDB.rawQuery(wQuery, null);

				int wCount = wCursor.getCount();
				
				if ( wCount > 0 ) {
					
					wCursor.moveToFirst();
					wIcon = wCursor.getString(0);
					
				}
				
				wCursor.close();

				wIndex++;

				wReturn[wIndex][0] = wTabChn[i][0];
				wReturn[wIndex][1] = wTabChn[i][1];
				wReturn[wIndex][2] = wTabChn[i][3];
				wReturn[wIndex][3] = wIcon;
				wReturn[wIndex][4] = wTabChn[i][2];
				wReturn[wIndex][5] = wTabChn[i][4];

			}
			
		}
		
//		wDB.close();			
		
		return wReturn;
		
	}
	
	public String [][] getLookup () {
		
		if ( wLookupArg.equals("") ) return new String[0][5];
		
		String [][] wReturn = new String[wLookupCount][5];
		// tag, flag-private, flag-privcode, briefing, icon
		
		if ( !wDB.isOpen() ) wDB = wHelper.getReadableDatabase();
		
		for ( int i = 0; i < wLookupCount; i++ ) {
			
			String wIcon = wZupIconPb;
			
			String wQuery = "SELECT icon FROM icons WHERE keyicon = '" + wLookupTab[i][3] + "-" + wLookupTab[i][0] + "';";
			
			Cursor wCursor = wDB.rawQuery(wQuery, null);

			int wCount = wCursor.getCount();
			
			if ( wCount > 0 ) {
				
				wCursor.moveToFirst();
				wIcon = wCursor.getString(0);
				
			}
			
			wCursor.close();
			
			wReturn[i][0] = wLookupTab[i][0];
			wReturn[i][1] = wLookupTab[i][1];
			wReturn[i][2] = wLookupTab[i][2];
			wReturn[i][3] = wLookupTab[i][4];
			wReturn[i][4] = wIcon;

		}

//		wDB.close();
		
		return wReturn;
		
	}
	
	public String [][] getMessages () {
		
		if ( wSelectTag.equals("") ) return new String[0][5];
		
		if ( !wDB.isOpen() ) wDB = wHelper.getReadableDatabase();
	    
		String wQuery = "SELECT * FROM " +
				            "(SELECT idmessage, dateincl, msgtext, msgurl " + 
		                       "FROM messages " + 
				              "WHERE channeltag = '" + wSelectTag + "' and subscode = '" + wSelectCode + "' and regstatus = 'a' " + 
		                      "ORDER BY idmessage DESC LIMIT 200) " +
		                "ORDER BY 1;";
		
		Cursor wCursor = wDB.rawQuery(wQuery, null);

		int wCount = wCursor.getCount();
		
		String [][] wReturn = new String[wCount][5];
		// idmessage, datahora-envio, null, msgtext, msgurl
		
		if ( wCount > 0 ) wCursor.moveToFirst();

		for ( int i = 0; i < wCount; i++ ) {
			
			
    		String wMsg = wCursor.getString(2);
    		String wUrl = wCursor.getString(3);

    		byte[] bMsg = Base64.decode(wMsg, Base64.DEFAULT);
    		wMsg = new String(bMsg);  		
			    		
    		byte[] bUrl = Base64.decode(wUrl, Base64.DEFAULT);
    		wUrl = new String(bUrl);  		

    		wMsg = wMsg.replace("USERID",   wIdUser);
    		wMsg = wMsg.replace("SUBSCODE", wSelectCode);
    		wUrl = wUrl.replace("USERID",   wIdUser);
    		wUrl = wUrl.replace("SUBSCODE", wSelectCode);
    		
//    		bMsg = wMsg.getBytes();
//          wMsg = Base64.encodeToString(bMsg, Base64.DEFAULT);
			
			wReturn[i][0] = wCursor.getString(0);
			wReturn[i][1] = convertDate(wCursor.getString(1));
			wReturn[i][2] = "";
			wReturn[i][3] = wMsg;
			wReturn[i][4] = wUrl;

			wCursor.moveToNext();
			
		}
		
		wCursor.close();

//		wDB.close();

		return wReturn;
		
	}
	
//  ==============================================================
//  PUBLIC METHODS
//  ==============================================================

 	public int registerDevice (String wEmail, String wPass) {

		int wRet = 0;

		if ( wRegistered ) { unregisterDevice(); }
		
		if ( !deviceIsOnline() ) return 8001;
		
        String wSubmit = "<stp>" + wStamp + "</stp>" +
        		         "<eml>" + wEmail + "</eml>" + 
                         "<pwd>" + wPass  + "</pwd>";

		wRet = submit(wIdInterface, wSubmit);
		if ( wRet != 0 ) return wRet;
		
        DocumentBuilderFactory wDocFactory = DocumentBuilderFactory.newInstance();

        DocumentBuilder wDocBuilder = null;
		try { wDocBuilder = wDocFactory.newDocumentBuilder(); } 
		catch (ParserConfigurationException e) { return 8101; }

        org.w3c.dom.Document wDoc = null;
		try { wDoc = wDocBuilder.parse(new ByteArrayInputStream(wSubmitRetFrame.trim().getBytes())); } 
		catch (SAXException e) { return 8102; } 
		catch (IOException e) {	return 8102; }

        NodeList wNodeUid = wDoc.getElementsByTagName("uid");
        NodeList wNodeDid = wDoc.getElementsByTagName("did");
        NodeList wNodeMod = wDoc.getElementsByTagName("mod");
        NodeList wNodePux = wDoc.getElementsByTagName("pux");

		if ( wNodeUid.getLength() == 0 ) return 8103;
		if ( wNodeDid.getLength() == 0 ) return 8103;
		if ( wNodeMod.getLength() == 0 ) return 8103;
		if ( wNodePux.getLength() == 0 ) return 8103;
		
		wIdUser             = wNodeUid.item(0).getTextContent();
		wIdDevice           = wNodeDid.item(0).getTextContent();
		String wModDevice64 = wNodeMod.item(0).getTextContent();
		String wPuxDevice64 = wNodePux.item(0).getTextContent();

		if ( wIdUser.equals("") )      return 8103;
		if ( wIdDevice.equals("") )    return 8103;
		if ( wModDevice64.equals("") ) return 8103;
		if ( wPuxDevice64.equals("") ) return 8103;

		wPubKey = pubFactory (wModDevice64, wPuxDevice64);
	    wStringPubKey = objectToString(wPubKey);

		wRegistered = true;
	    
	    SharedPreferences.Editor wLocalEditor;
	    wLocalEditor = wLocalPrefs.edit();
	    
    	wLocalEditor.putString  ("PUBKEY",wStringPubKey);
    	wLocalEditor.putString  ("IDDEVICE",wIdDevice);
    	wLocalEditor.putString  ("IDUSER",wIdUser);
    	wLocalEditor.putBoolean ("REGISTERED",wRegistered);
    	wLocalEditor.commit();
		
		return check(true);
		
	}

	public int unregisterDevice () {
		
		if ( wRegistered ) {
			
	        String wSubmit = "<stp>" + wStamp + "</stp><com>ureg</com>";

	        submit(wIdDevice, wSubmit);
			
		}
		
    	SharedPreferences.Editor wLocalEditor;
	    wLocalEditor = wLocalPrefs.edit();

		wPubKey = pubFactory (wModInst64, wPuxInst64);
	    wStringPubKey = objectToString(wPubKey);
	    
		wTabChn = new String[0][0];
	    wStringTabChn = objectToString(wTabChn);
	    
	    wLastMsg = 0;
	    wLastSeq = 0;
	    wChannelCount = 0;
	    wMD5List = "";
	    wDeviceTag = "";
	    wIdDevice = "";
	    wIdUser = "";
	    wRegistered = false;
	    
    	wLocalEditor.putString  ("PUBKEY",wStringPubKey);
    	wLocalEditor.putString  ("TABCHN",wStringTabChn);
    	wLocalEditor.putInt     ("LASTMSG",wLastMsg);
    	wLocalEditor.putInt     ("LASTSEQ",wLastSeq);
    	wLocalEditor.putInt     ("CHANNELCOUNT",wChannelCount);
    	wLocalEditor.putString  ("MD5LIST",wMD5List);
    	wLocalEditor.putString  ("DEVICETAG",wDeviceTag);
    	wLocalEditor.putString  ("IDDEVICE",wIdDevice);
    	wLocalEditor.putString  ("IDUSER",wIdUser);
    	wLocalEditor.putBoolean ("REGISTERED",wRegistered);

    	wLocalEditor.commit();
    	
		return 0;
		
	}
	
	public int check (boolean wFlag) {
		
		int wRet = 0;

		if ( !wFlag ) return 0;
		if ( !wRegistered ) return 8171;
		if ( !deviceIsOnline() ) return 8001;
		
        String wSubmit = "<stp>" + wStamp + "</stp><com>chck</com><msg>" + wLastMsg + "</msg>";

		wRet = submit(wIdDevice, wSubmit);
		if ( wRet != 0 ) return wRet;

		String wStringCheck = wSubmitRetFrame;
		
        DocumentBuilderFactory wDocFactory = DocumentBuilderFactory.newInstance();

        DocumentBuilder wDocBuilder = null;
		try { wDocBuilder = wDocFactory.newDocumentBuilder(); } 
		catch (ParserConfigurationException e) { return 8181; }

        org.w3c.dom.Document wDoc = null;
		try { wDoc = wDocBuilder.parse(new ByteArrayInputStream(wStringCheck.trim().getBytes())); } 
		catch (SAXException e) { return 8182; } 
		catch (IOException e) {	return 8182; }

        NodeList wNodeMD5 = wDoc.getElementsByTagName("md5");

		if ( wNodeMD5.getLength() == 0 ) return 8183;
		
		String wMD5 = wNodeMD5.item(0).getTextContent();
		
		if ( wMD5.equals("") ) return 8183;
		
		if ( !wMD5.equals(wMD5List) ) {
			
			HashMap<String, String> wMap = new HashMap<String, String>();
			
			for ( int i = 0; i < wChannelCount; i++ ) {
				
				wMap.put(wTabChn[i][0], wTabChn[i][3]);
				
			}
			
	        String wSubmit2 = "<stp>" + wStamp + "</stp><com>lsub</com>";

			wRet = submit(wIdDevice, wSubmit2);
			if ( wRet != 0 ) return wRet;
			
	        DocumentBuilderFactory wDocFactory2 = DocumentBuilderFactory.newInstance();

	        DocumentBuilder wDocBuilder2 = null;
			try { wDocBuilder2 = wDocFactory2.newDocumentBuilder(); } 
			catch (ParserConfigurationException e) { return 8184; }

	        org.w3c.dom.Document wDoc2 = null;
			try { wDoc2 = wDocBuilder2.parse(new ByteArrayInputStream(wSubmitRetFrame.trim().getBytes())); } 
			catch (SAXException e) { return 8185; } 
			catch (IOException e) {	return 8185; }

	        NodeList wNodeTag = wDoc2.getElementsByTagName("tag");
	        NodeList wNodeCnt = wDoc2.getElementsByTagName("cnt");

			if ( wNodeTag.getLength() == 0 ) return 8185;
			if ( wNodeCnt.getLength() == 0 ) return 8185;
			
			wDeviceTag = wNodeTag.item(0).getTextContent();
			wChannelCount = Integer.parseInt(wNodeCnt.item(0).getTextContent());
			
			if ( wDeviceTag.equals("") ) return 8186;
						
			wTabChn = new String[wChannelCount][5];
	    	
	    	NodeList wNodeTab = wDoc2.getElementsByTagName("chn");
	    	
	    	if ( !wDB.isOpen() ) wDB = wHelper.getReadableDatabase();
			
	    	String wIconList = "";
			
			for ( int i = 0; i < wChannelCount; i++ ) {

				Node wNode = wNodeTab.item(i);
				Element eElement = (Element) wNode;
				String wDetail = eElement.getTextContent();
				String[] wTokens = wDetail.split(";",4);
				wTabChn[i][0] = wTokens[0];
				wTabChn[i][1] = wTokens[1];
				wTabChn[i][2] = wTokens[2];
				wTabChn[i][4] = wTokens[3];

				if ( wMap.containsKey(wTokens[0]) ) wTabChn[i][3] = wMap.get(wTokens[0]);
				else                                wTabChn[i][3] = "0";
				
				String wQuery = "SELECT icon FROM icons WHERE keyicon = '" + wTokens[2] + "-" + wTokens[0] + "';";
				
				Cursor wCursor = wDB.rawQuery(wQuery, null);
				
				if ( wCursor.getCount() == 0 ) { wIconList += "," + wTokens[0]; }
				
				wCursor.close();
				
			}
			
//			wDB.close();
			
		    wStringTabChn = objectToString(wTabChn);

			wMD5List = wMD5;
			
		    SharedPreferences.Editor wLocalEditor;
		    wLocalEditor = wLocalPrefs.edit();
		    
	    	wLocalEditor.putString  ("DEVICETAG",wDeviceTag);
	    	wLocalEditor.putString  ("TABCHN",wStringTabChn);
	    	wLocalEditor.putString  ("MD5LIST",wMD5List);
	    	wLocalEditor.putInt     ("CHANNELCOUNT",wChannelCount);
	    	wLocalEditor.commit();
	    	
	    	if ( !wIconList.equals("") ) { getIcons(wIconList.substring(1)); }
	    		
		}
		
        NodeList wNodeMsg = wDoc.getElementsByTagName("msg");
        
        wNewMsgs = wNodeMsg.getLength();
        
        if ( wNewMsgs > 0 ) {
        	
        	String xTag = "";
        	String wSub = "";
        	int wIndex  = 0;
        	
        	wDBw = wHelper.getWritableDatabase();
        
    		for ( int i = 0; i < wNewMsgs; i++ ) {

				Node wNode = wNodeMsg.item(i);
				Element eElement = (Element) wNode;
				String wDetail = eElement.getTextContent();
				String[] wTokens = wDetail.split(";",5);
				String wTag = wTokens[0];
				String wMsg = wTokens[1];
				String wDat = wTokens[2];
				String wTxt = wTokens[3];
				String wUrl = wTokens[4];

				if ( !wTag.equals(xTag) ) {

					xTag = wTag;
					wIndex = -1;
					
					for ( int j = 0; j < wChannelCount; j++ ) {

						if ( wTabChn[j][0].equals(wTag) ) {
							wSub = wTabChn[j][1];
							wIndex = j;
							break;
						}
						
					}
					
				}
				
				if ( wIndex >= 0 ) {
					int wTotal = Integer.parseInt(wTabChn[wIndex][3]) + 1;
					wTabChn[wIndex][3] = String.valueOf(wTotal);
				}
				
    			ContentValues newValues = new ContentValues();
    		    newValues.put("idmessage", wMsg);
    		    newValues.put("channeltag", wTag);
    		    newValues.put("subscode", wSub);
    		    newValues.put("dateincl", wDat);
    		    newValues.put("msgtext", wTxt);
    		    newValues.put("msgurl", wUrl);
    		    newValues.put("regstatus", "a");
    		       
    		    wDBw.insert("messages", null, newValues);
    		    
    		    int wID = Integer.parseInt(wMsg);
    		    
    		    if ( wID > wLastMsg ) { wLastMsg = wID; };
    		    
    		}
    		
    	    wDBw.close();
    	    
    	    wStringTabChn = objectToString(wTabChn);
    	    
		    SharedPreferences.Editor wLocalEditor;
		    wLocalEditor = wLocalPrefs.edit();
		    
	    	wLocalEditor.putInt    ("LASTMSG",wLastMsg);
	    	wLocalEditor.putString ("TABCHN",wStringTabChn);
	    	wLocalEditor.commit();
        	
        }
		
		return 0;
		
	}

	public int selectChannel (String wTag) {
		
		wSelectTag  = "";
		wSelectCode = "";
		wSelectIcon = "";
		wSelectMD5  = "";
		wSelectFlag = "";
		
		if ( !wRegistered ) return 8152;

		if ( !wDB.isOpen() ) wDB = wHelper.getReadableDatabase();
	    
		for ( int i = 0; i < wChannelCount; i++ ) {

			if ( wTabChn[i][0].equals(wTag) ) {
				
				wSelectTag  = wTag;
				wSelectCode = wTabChn[i][1];
				wSelectMD5  = wTabChn[i][2];
				wSelectFlag = wTabChn[i][4];
				wTabChn[i][3] = "0";

				wSelectIcon = wZupIconPb;
				
				String wQuery = "SELECT icon FROM icons WHERE keyicon = '" + wSelectMD5 + "-" + wSelectTag + "';";
				
				Cursor wCursor = wDB.rawQuery(wQuery, null);

				int wCount = wCursor.getCount();
				
				if ( wCount > 0 ) {
					
					wCursor.moveToFirst();
					wSelectIcon = wCursor.getString(0);
					
				}
				
				wCursor.close();
			    
				break;
				
			}
			
		}
		
//	    wDB.close();
    	
   	    wStringTabChn = objectToString(wTabChn);
	    
		SharedPreferences.Editor wLocalEditor;
		wLocalEditor = wLocalPrefs.edit();
		    
	    wLocalEditor.putString ("TABCHN",wStringTabChn);
	    wLocalEditor.commit();

		if ( wSelectTag.equals("") ) return 8153;
	    
		return 0;
		
	}
	
 	public int lookupChannel (String wArg, String wMore) {
		
		int wRet = 0;
		
		wLookupArg   = "";
		wLookupMore  = "";
		wLookupCount = 0;
		
		if ( !wRegistered ) return 8121;
		if ( !deviceIsOnline() ) return 8001;
	
        String wSubmit = "<stp>" + wStamp + "</stp><com>lkup</com><chn>" + wArg + "</chn><mor>" + wMore + "</mor>";

		wRet = submit(wIdDevice, wSubmit);
		if ( wRet != 0 ) return wRet;
		
        DocumentBuilderFactory wDocFactory = DocumentBuilderFactory.newInstance();

        DocumentBuilder wDocBuilder = null;
		try { wDocBuilder = wDocFactory.newDocumentBuilder(); } 
		catch (ParserConfigurationException e) { return 8122; }

        org.w3c.dom.Document wDoc = null;
		try { wDoc = wDocBuilder.parse(new ByteArrayInputStream(wSubmitRetFrame.trim().getBytes())); } 
		catch (SAXException e) { return 8123; } 
		catch (IOException e) {	return 8123; }

        NodeList wNodeMore = wDoc.getElementsByTagName("mor");

		if ( wNodeMore.getLength() == 0 ) return 8123;

		wLookupArg = wArg;
		wLookupMore = wNodeMore.item(0).getTextContent();
		    	
    	NodeList wNodeTab = wDoc.getElementsByTagName("chn");
    	
    	wLookupCount = wNodeTab.getLength();
    	
		wLookupTab = new String[wLookupCount][5];
    	
		if ( !wDB.isOpen() ) wDB = wHelper.getReadableDatabase();
		
    	String wIconList = "";
		
		for ( int i = 0; i < wLookupCount; i++ ) {

			Node wNode = wNodeTab.item(i);
			Element eElement = (Element) wNode;
			String wDetail = eElement.getTextContent();
			
			String[] wTokens = wDetail.split(";",5);
			wLookupTab[i][0] = wTokens[0];
			wLookupTab[i][1] = wTokens[1];
			wLookupTab[i][2] = wTokens[2];
			wLookupTab[i][3] = wTokens[3];
			wLookupTab[i][4] = wTokens[4];

			String wQuery = "SELECT icon FROM icons WHERE keyicon = '" + wTokens[3] + "-" + wTokens[0] + "';";
			
			Cursor wCursor = wDB.rawQuery(wQuery, null);
			
			int wCount = wCursor.getCount();

            if ( wCount == 0 ) { wIconList += "," + wTokens[0]; }
            
            wCursor.close();
            
		}
    	
//    	wDB.close();
    	
    	if ( !wIconList.equals("") ) { return getIcons(wIconList.substring(1)); }	
		
		return 0;
		
	}
	
	public int subscribeChannel (String wTag, String wPrivCode) {
		
		int wRet = 0;
		
		if ( !wRegistered ) return 8131;
		if ( !deviceIsOnline() ) return 8001;
		
        String wSubmit = "<stp>" + wStamp + "</stp><com>schn</com><chn>" + wTag + "</chn><pvc>" + wPrivCode + "</pvc>";

		wRet = submit(wIdDevice, wSubmit);
		if ( wRet != 0 ) return wRet;
		
		return check(true);
		
	}

	public int unsubscribeChannel (String wTag) {
		
		int wRet = 0;
		
		if ( !wRegistered ) return 8141;
		if ( !deviceIsOnline() ) return 8001;
		
        String wSubmit = "<stp>" + wStamp + "</stp><com>uchn</com><chn>" + wTag + "</chn>";

		wRet = submit(wIdDevice, wSubmit);
		if ( wRet != 0 ) return wRet;
		
		return check(true);
		
	}
	
	public int deleteMessage (String wIdMessage) {
		
		int wRet = 0;
		
		if ( wIdMessage.equals("") ) return 8161;
		if ( !wRegistered ) return 8162;
		if ( !deviceIsOnline() ) return 8001;
		
        String wSubmit = "<stp>" + wStamp + "</stp><com>dmsg</com><msg>" + wIdMessage + "</msg>";

		wRet = submit(wIdDevice, wSubmit);
		if ( wRet != 0 ) return wRet;
		
		wDBw = wHelper.getWritableDatabase();
	    
	    String wQuery = "UPDATE messages SET regstatus = 'd' WHERE idmessage = '" + wIdMessage + "';";
	    
		wDBw.execSQL(wQuery);

	    wDBw.close();
	    
	    return 0;
		
	}

	public int sendResponse (String wResponse) {
		
		int wRet = 0;
		
		if ( wResponse.equals("") ) return 7316;
		if ( wSelectTag.equals("") ) return 7316;
		if ( !wRegistered ) return 8162;
		if ( !deviceIsOnline() ) return 8001;
		
        String wSubmit = "<stp>" + wStamp + "</stp><com>resp</com><chn>" + wSelectTag + "</chn><res>" + wResponse + "</res>";

		wRet = submit(wIdDevice, wSubmit);
		if ( wRet != 0 ) return wRet;
		
	    return 0;
		
	}
	
//  ==============================================================
//  PRIVATE METHODS
//  ==============================================================
	
	@SuppressLint("SimpleDateFormat") 
	private String convertDate (String wDateIn) {
		
		Date wDate = null;
		
	    SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd hh:mm:ss");
	    dateFormat.setTimeZone(TimeZone.getTimeZone("UTC"));
	    
		try { wDate = dateFormat.parse(wDateIn); } 
		catch (ParseException e) { e.printStackTrace();	}
		
		DateFormat formater = DateFormat.getDateTimeInstance();
		String wDateOut = formater.format(wDate);
		
		return wDateOut;
		
	}
	
	private int getIcons (String wList) {
		
		int wRet = 0;
		
	    String wSubmit = "<stp>" + wStamp + "</stp><com>icon</com><chn>" + wList + "</chn>";

		wRet = submit(wIdDevice, wSubmit);
		if ( wRet != 0 ) return wRet;
		
		String[] wTabList = wList.split(",");
		String[] wTabIcon = wSubmitRetIcons.split(";");
		
		wDBw = wHelper.getWritableDatabase();

		int wLength = wTabList.length;
		
		for ( int i = 0; i < wLength; i++ ) {

			if ( !wTabIcon[i].equals("null") ) {
				
				String wMD5 = md5(wTabIcon[i]);
				
				ContentValues newValues = new ContentValues();
		        newValues.put("keyicon", wMD5 + "-" + wTabList[i]);
		        newValues.put("icon", wTabIcon[i]);
		       
		        wDBw.insert("icons", null, newValues);
						
			}
			
		}
		
	    wDBw.close();

 		return 0;
		
	}
		
	private boolean deviceIsOnline () {
		
		ConnectivityManager cm = (ConnectivityManager) wContext.getSystemService(Context.CONNECTIVITY_SERVICE);
	    return cm.getActiveNetworkInfo() != null && cm.getActiveNetworkInfo().isConnectedOrConnecting();

	}
	
	private RSAPublicKey pubFactory (String wMod64, String wPux64) {
		
		RSAPublicKey wPublicKey = null;
		
		BigInteger wModulus = new BigInteger(wMod64,16);
		BigInteger wExpoent = new BigInteger(wPux64,16);
		RSAPublicKeySpec wkeySpec = new RSAPublicKeySpec(wModulus,wExpoent);  

		KeyFactory wkeyFactory = null;

		try { wkeyFactory = KeyFactory.getInstance("RSA"); } 
		catch (NoSuchAlgorithmException e) { e.printStackTrace(); }

		try { wPublicKey = (RSAPublicKey) wkeyFactory.generatePublic(wkeySpec);	} 
		catch (InvalidKeySpecException e) {	e.printStackTrace(); }

		return wPublicKey;
		
	}

	private String objectToString (Serializable myObject) {
		
		String myString  = null;
		ObjectOutputStream outObject = null;
		
		ByteArrayOutputStream byteStream = new ByteArrayOutputStream();
		
		try { outObject = new ObjectOutputStream (byteStream); } 
		catch (IOException e) {	e.printStackTrace(); }

		try { outObject.writeObject (myObject);	} 
		catch (IOException e) {	e.printStackTrace(); }

		try { outObject.close(); } 
		catch (IOException e) {	e.printStackTrace(); }
		
		myString = Base64.encodeToString (byteStream.toByteArray(),Base64.DEFAULT);
		
		return myString;
		
	}
	
	private Object stringToObject (String myString) {
		
		ObjectInputStream inputStream = null;
		Object myObject = null;
		
		byte [] bSource = Base64.decode(myString, Base64.DEFAULT);

		try { inputStream = new ObjectInputStream (new ByteArrayInputStream (bSource) ); } 
		catch (StreamCorruptedException e) { e.printStackTrace(); } 
		catch (IOException e) {	e.printStackTrace(); }

		try { myObject = inputStream.readObject(); } 
		catch (OptionalDataException e) { e.printStackTrace(); } 
		catch (ClassNotFoundException e) { e.printStackTrace();	} 
		catch (IOException e) {	e.printStackTrace(); }

		try { inputStream.close(); } 
		catch (IOException e) {	e.printStackTrace(); }
		
		return myObject;
		
	}

	private String md5 (String wSource) {
		
		if ( wSource.equals("") ) return "";
		        
		MessageDigest wDigest = null;
		
		try { wDigest = java.security.MessageDigest.getInstance("MD5"); } 
		catch (NoSuchAlgorithmException e) { e.printStackTrace(); } 
		
		wDigest.update(wSource.getBytes()); 
		byte bDigest[] = wDigest.digest();
 
		StringBuffer hexString = new StringBuffer();
		
		for ( int i = 0; i < bDigest.length; i++ ) {
			String h = Integer.toHexString(0xFF & bDigest[i]);
	        while (h.length() < 2) h = "0" + h;
	        hexString.append(h);
	    }
		
	    return hexString.toString();
 	
	}
	
 	@SuppressLint("TrulyRandom") 
 	private int submit (String wID, String wSubmit) {
		
		String wRetCode = "";
		String wRetMD5  = "";
		wSubmitRetFrame = "";
		wSubmitRetIcons = "";
		
        do {
        	
    		if ( !deviceIsOnline() ) return 8001;
      
        	String wXML = wSubmit;
        	
        	if ( wID.equals(wIdDevice) ) {
        	
        		wLastSeq++;
        		wXML = wXML + "<seq>" + Integer.toString(wLastSeq) + "</seq>";
        		
        	    SharedPreferences.Editor wLocalEditor;
    		    wLocalEditor = wLocalPrefs.edit();
    	    	wLocalEditor.putInt("LASTSEQ", wLastSeq);
    	    	wLocalEditor.commit();
        	
        	}
        	
            wXML = "<?xml version=\"1.0\" encoding=\"utf-8\"?><followzup>" + wXML + "</followzup>";
        	
            byte[] nPlain = wXML.getBytes();        

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

            Cipher aesCipher = null;
			try { aesCipher = Cipher.getInstance("AES/CBC/NoPadding"); } 
			catch (NoSuchAlgorithmException e) { e.printStackTrace(); } 
			catch (NoSuchPaddingException e) { e.printStackTrace();	}

            try { aesCipher.init(Cipher.ENCRYPT_MODE, sKey, sVector); } 
            catch (InvalidKeyException e) {	e.printStackTrace(); } 
            catch (InvalidAlgorithmParameterException e) { e.printStackTrace();	}
            
            byte[] bPlainEncrypted = null;
			try { bPlainEncrypted = aesCipher.doFinal(bPlain); } 
			catch (IllegalBlockSizeException e) { e.printStackTrace();	} 
			catch (BadPaddingException e) {	e.printStackTrace(); }

            // encrypt AES key with RSA PublicKey
            Cipher rsaCipher = null;
			try { rsaCipher = Cipher.getInstance("RSA/ECB/PKCS1Padding"); } 
			catch (NoSuchAlgorithmException e) { e.printStackTrace(); } 
			catch (NoSuchPaddingException e) { e.printStackTrace();	}
            
            try { rsaCipher.init(Cipher.ENCRYPT_MODE, wPubKey);	} 
            catch (InvalidKeyException e) {	e.printStackTrace(); }
            
            byte[] bKeyEncrypted = null;
			try { bKeyEncrypted = rsaCipher.doFinal(bKey); } 
			catch (IllegalBlockSizeException e) { e.printStackTrace(); } 
			catch (BadPaddingException e) {	e.printStackTrace(); }

            // encode encrypted AES key and encrypted xml text        
            String wKey64 = Base64.encodeToString(bKeyEncrypted, Base64.DEFAULT);
            
            String wKeyURL = null;
			try { wKeyURL = URLEncoder.encode(wKey64,"UTF-8"); } 
			catch (UnsupportedEncodingException e) { e.printStackTrace();	} 
            
            String wPlainEncrypted64 = Base64.encodeToString(bPlainEncrypted, Base64.DEFAULT);
            
            String wPlainEncryptedURL = null;
			try { wPlainEncryptedURL = URLEncoder.encode(wPlainEncrypted64,"UTF-8"); } 
			catch (UnsupportedEncodingException e) { e.printStackTrace(); } 
           
            // build post request
            String url = "http://" + wServer + "/wsdevice";

            URL obj = null;
			try { obj = new URL(url); } 
			catch (MalformedURLException e) { return 8241; }
            
            HttpURLConnection wConnect = null;
			try { wConnect = (HttpURLConnection) obj.openConnection(); 
			      wConnect.setConnectTimeout(5000);
			      wConnect.setReadTimeout(5000); } 
			catch (IOException e) { return 8241; }

            // add request header 
            try { wConnect.setRequestMethod("POST"); } 
            catch (ProtocolException e) { return 8241; }
            
            wConnect.setRequestProperty("User-Agent", "interface: " + wIdInterface );

            String urlParameters = "id=" + wID + "&key=" + wKeyURL + "&frame=" + wPlainEncryptedURL;
    
            // send post request 
            wConnect.setDoOutput(true);
            
            DataOutputStream wr = null;
			try { wr = new DataOutputStream(wConnect.getOutputStream()); } 
			catch (IOException e) {	return 8241; }
            
            try { wr.writeBytes(urlParameters);	} 
            catch (IOException e) {	return 8241; }
            
            try { wr.flush(); } 
            catch (IOException e) {	return 8241; }
            
            try { wr.close(); } 
            catch (IOException e) {	return 8241; }

            // get post response 
            BufferedReader in = null;
			try { in = new BufferedReader(new InputStreamReader(wConnect.getInputStream()));	} 
			catch (IOException e) {	return 8241; }

            String inputLine;
            StringBuffer getPost = new StringBuffer();

            try { while ( (inputLine = in.readLine()) != null ) { getPost.append(inputLine); } } 
            catch (IOException e) {	return 8241; }
            
            try { in.close(); } 
            catch (IOException e) {	return 8241; }
    
            // extract response tag        
            DocumentBuilderFactory wDocFactory = DocumentBuilderFactory.newInstance();
            
            DocumentBuilder wDocBuilder = null;
			try { wDocBuilder = wDocFactory.newDocumentBuilder(); } 
			catch (ParserConfigurationException e) { return 8242; }
            
            org.w3c.dom.Document wDoc = null;
			try { wDoc = wDocBuilder.parse(new ByteArrayInputStream(getPost.toString().getBytes())); } 
			catch (SAXException e) { return 8242; } 
			catch (IOException e) {	return 8242; }

            // extract RETCODE 
            NodeList nRetcode = wDoc.getElementsByTagName("retcode");
            wRetCode = nRetcode.item(0).getTextContent();

            // extract RETMD5 
            NodeList nRetMD5 = wDoc.getElementsByTagName("retmd5");
            wRetMD5 = nRetMD5.item(0).getTextContent();

            // extract RETICONS
            NodeList nRetIcons = wDoc.getElementsByTagName("reticons");
            wSubmitRetIcons = nRetIcons.item(0).getTextContent();

            // extract RETFRAME
            NodeList nRetframe = wDoc.getElementsByTagName("retframe");
            String wRetframeEncrypted64 = nRetframe.item(0).getTextContent();
            String wMD5 = md5(wRetframeEncrypted64);
            
            if ( !wRetMD5.equals(wMD5)) wRetMD5 = "error";
            
            else {
            
            	if ( !wRetframeEncrypted64.equals("") ) {
            	
            		// decrypt RETFRAME with AES key 
            		byte[] bRetframeEncrypted = Base64.decode(wRetframeEncrypted64, Base64.DEFAULT);
            
            		Cipher resCipher = null;
            		try { resCipher = Cipher.getInstance("AES/CBC/NoPadding"); } 
            		catch (NoSuchAlgorithmException e) { return 8243; } 
            		catch (NoSuchPaddingException e) { return 8243;	}
            
            		try { resCipher.init(Cipher.DECRYPT_MODE, sKey, sVector); } 
            		catch (InvalidKeyException e) {	return 8243; } 
            		catch (InvalidAlgorithmParameterException e) { return 8243;	}
            
            		byte[] bResponse = null;
            		try { bResponse = resCipher.doFinal(bRetframeEncrypted); } 
            		catch (IllegalBlockSizeException e) { return 8243; } 
            		catch (BadPaddingException e) {	return 8243; }
            
            		wSubmitRetFrame = new String(bResponse);    
            	
            		// if out of sequence, extract last used sequence
            		if ( wRetCode.equals("7101") ) {

                		// extract SEQ tag        
                		DocumentBuilderFactory wDocFactory2 = DocumentBuilderFactory.newInstance();

                		DocumentBuilder wDocBuilder2 = null;
						try { wDocBuilder2 = wDocFactory2.newDocumentBuilder();	} 
						catch (ParserConfigurationException e) { return 8244; }
                
                		org.w3c.dom.Document wDoc2 = null;
						try { wDoc2 = wDocBuilder2.parse(new ByteArrayInputStream(wSubmitRetFrame.trim().getBytes())); } 
						catch (SAXException e) { return 8244; } 
						catch (IOException e) {	return 8244; }

                		// extract lastseq
                		NodeList nLastseq = wDoc2.getElementsByTagName("seq");
                		String wStringLastseq = nLastseq.item(0).getTextContent();
                		wLastSeq = Integer.parseInt(wStringLastseq);
            		} 
                    	
            	}
            
            }

        // repeat request while out of sequence or MD5 error
        } while ( wRetCode.equals("7101") || wRetMD5.equals("error") );
      
        return Integer.parseInt(wRetCode);
        
    }
	
	private class FzupHelper extends SQLiteOpenHelper {

		private static final String DBNAME = "FollowzupDB"; 
		private static final int VERSAO = 1;
		
		public FzupHelper (Context context) {
			super(context, DBNAME, null, VERSAO);
		}

		@Override
		public void onCreate(SQLiteDatabase wDB) {
			
			wDB.execSQL ( "CREATE TABLE messages " +
					      " (idmessage  BIGINT PRIMARY KEY," +
					      "  channeltag VARCHAR," +
					      "  subscode   CHAR(8)," +
					      "  dateincl   DATETIME," +
					      "  msgtext    VARCHAR(255)," +
					      "  msgurl     VARCHAR(255)," +
					      "  regstatus  char(1) );" );
			
			wDB.execSQL ( "CREATE TABLE icons " +
				          " (keyicon    VARCHAR PRIMARY KEY," +
				          "  icon       TEXT);" );
			
		}

		@Override
		public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
			
		}
		
	}
	
}

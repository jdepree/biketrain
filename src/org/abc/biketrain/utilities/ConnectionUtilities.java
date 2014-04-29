package org.abc.biketrain.utilities;

import android.app.Activity;
import android.content.Context;
import android.content.SharedPreferences;
import android.content.pm.PackageInfo;
import android.content.pm.PackageManager;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.GooglePlayServicesUtil;
import com.google.android.gms.gcm.GoogleCloudMessaging;
import com.google.gson.Gson;
import org.abc.biketrain.controller.MenuActivity;
import org.abc.biketrain.model.WebService;
import org.apache.http.NameValuePair;

import java.io.*;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLEncoder;
import java.util.List;
import java.util.concurrent.atomic.AtomicInteger;

public class ConnectionUtilities {
    private static final String TAG = "ConnectionUtilities";

    public static final String GET = "GET";
    public static final String POST = "POST";
    private static final String POST_REG_ID = "";
    private static final String EXTRA_MESSAGE = "message";
    private static final String PROPERTY_REG_ID = "registration_id";
    private static final String PROPERTY_USER_ID = "user_id";
    private static final String PROPERTY_APP_VERSION = "appVersion";
    private static final int PLAY_SERVICES_RESOLUTION_REQUEST = 9000;
    private static String SENDER_ID = "21088864750";

    private static GoogleCloudMessaging gcm;
    private static AtomicInteger msgId = new AtomicInteger();

    public static byte[] getUrlBytes(String urlSpec, String verb, List<NameValuePair> params, List<NameValuePair> headers) throws IOException {
        URL url = new URL(urlSpec);
        HttpURLConnection connection = (HttpURLConnection)url.openConnection();
        if (headers != null) {
            for (NameValuePair entry : headers) {
                connection.addRequestProperty(entry.getName(), entry.getValue());
            }
        }

        try {
            ByteArrayOutputStream out = new ByteArrayOutputStream();

            if (params != null) {
                connection.setRequestMethod(verb);
                if (verb.equals(POST)) {
                    connection.setDoInput(true);
                    connection.setDoOutput(true);
                }

                OutputStream os = connection.getOutputStream();
                BufferedWriter writer = new BufferedWriter(
                        new OutputStreamWriter(os, "UTF-8"));
                writer.write(getQueryString(params));
                writer.flush();
                writer.close();
                os.close();
            }

            connection.connect();

            InputStream in = connection.getInputStream();

            if (connection.getResponseCode() != HttpURLConnection.HTTP_OK) {
                return null;
            }

            int bytesRead = 0;
            byte[] buffer = new byte[1024];
            while ((bytesRead = in.read(buffer)) > 0) {
                out.write(buffer, 0, bytesRead);
            }
            out.close();
            return out.toByteArray();
        } finally {
            connection.disconnect();
        }
    }

    public static String getUrlString(String urlSpec, String verb, List<NameValuePair> params, List<NameValuePair> headers) throws IOException {
        return new String(getUrlBytes(urlSpec, verb, params, headers));
    }

    public static Object sendRequest(String requestUrl, String verb, List<NameValuePair> params, List<NameValuePair> headers, Class classObj) throws IOException {
        String result = getUrlString(requestUrl, verb, params, headers);
        System.out.println("RESULT: " + result);
        if (classObj == null) {
            return null;
        } else {
            return ((new Gson().fromJson(result, classObj)));
        }
    }


    // Start Server-Side Events Section

    public static boolean checkPlayServices(Activity activity) {
        int resultCode = GooglePlayServicesUtil.isGooglePlayServicesAvailable(activity);
        if (resultCode != ConnectionResult.SUCCESS) {
            if (GooglePlayServicesUtil.isUserRecoverableError(resultCode)) {
                GooglePlayServicesUtil.getErrorDialog(resultCode, activity,
                        PLAY_SERVICES_RESOLUTION_REQUEST).show();
            } else {
                Log.i(TAG, "This device is not supported.");
            }
            return false;
        }
        return true;
    }

    public static String getRegistrationId(Context context) {
        final SharedPreferences prefs = getPreferences(context);
        String registrationId = prefs.getString(PROPERTY_REG_ID, "");
        if (registrationId.isEmpty()) {
            Log.i(TAG, "Registration not found.");
            return "";
        }
        // Check if app was updated; if so, it must clear the registration ID
        // since the existing regID is not guaranteed to work with the new
        // app version.
        int registeredVersion = prefs.getInt(PROPERTY_APP_VERSION, Integer.MIN_VALUE);
        int currentVersion = getAppVersion(context);
        if (registeredVersion != currentVersion) {
            Log.i(TAG, "App version changed.");
            return "";
        }
        return registrationId;
    }

    public static String getUserId(Context context) {
        final SharedPreferences prefs = getPreferences(context);
        String userId = prefs.getString(PROPERTY_USER_ID, "");

        return userId;
    }

    public static void storeUserId(Context context, String id) {
        final SharedPreferences prefs =getPreferences(context);
        SharedPreferences.Editor editor = prefs.edit();
        editor.putString(PROPERTY_USER_ID, id);
        editor.commit();
    }

    public static SharedPreferences getPreferences(Context context) {
        // This sample app persists the registration ID in shared preferences, but
        // how you store the regID in your app is up to you.
        return context.getSharedPreferences(MenuActivity.class.getSimpleName(),
                Context.MODE_PRIVATE);
    }

    public static int getAppVersion(Context context) {
        try {
            PackageInfo packageInfo = context.getPackageManager()
                    .getPackageInfo(context.getPackageName(), 0);
            return packageInfo.versionCode;
        } catch (PackageManager.NameNotFoundException e) {
            // should never happen
            throw new RuntimeException("Could not get package name: " + e);
        }
    }

    public static void registerInBackground(final Context context) {
        System.out.println("LAUNCHING TASK TO REGISTER ID");
        new AsyncTask<Void, Void, String>() {
            @Override
            protected String doInBackground(Void... params) {
                String msg = "";
                try {
                    if (gcm == null) {
                        gcm = GoogleCloudMessaging.getInstance(context);
                    }
                    String regid = gcm.register(SENDER_ID);
                    msg = "Device registered, registration ID=" + regid;

                    // You should send the registration ID to your server over HTTP,
                    // so it can use GCM/HTTP or CCS to send messages to your app.
                    // The request to your server should be authenticated if your app
                    // is using accounts.
                    System.out.println("REGISTRATION ID: " + regid);
                    WebService.sendRegistrationIdToBackend(regid);

                    // For this demo: we don't need to send it because the device
                    // will send upstream messages to a server that echo back the
                    // message using the 'from' address in the message.

                    // Persist the regID - no need to register again.
                    storeRegistrationId(context, regid);
                } catch (IOException ex) {
                    msg = "Error :" + ex.getMessage();
                    ex.printStackTrace();
                    // If there is an error, don't just keep trying to register.
                    // Require the user to click a button again, or perform
                    // exponential back-off.
                }
                return msg;
            }

            @Override
            protected void onPostExecute(String msg) {
                System.out.println(msg + "\n");
            }
        }.execute(null, null, null);
    }

    public static void storeRegistrationId(Context context, String regId) {
        final SharedPreferences prefs =getPreferences(context);
        int appVersion = getAppVersion(context);
        Log.i(TAG, "Saving regId on app version " + appVersion);
        SharedPreferences.Editor editor = prefs.edit();
        editor.putString(PROPERTY_REG_ID, regId);
        editor.putInt(PROPERTY_APP_VERSION, appVersion);
        editor.commit();
    }

    public static void sendMessage() {
        String msg = "";
        try {
            Bundle data = new Bundle();
            data.putString("my_message", "Hello World");
            data.putString("my_action",
                    "com.google.android.gcm.demo.app.ECHO_NOW");
            String id = Integer.toString(msgId.incrementAndGet());
            gcm.send(SENDER_ID + "@gcm.googleapis.com", id, data);
            msg = "Sent message";
        } catch (IOException ex) {
            msg = "Error :" + ex.getMessage();
        }
    }


    // End Server-Side Events Section

    private static String getQueryString(List<NameValuePair> params) throws UnsupportedEncodingException
    {
        StringBuilder result = new StringBuilder();
        boolean first = true;

        for (NameValuePair pair : params)
        {
            if (first)
                first = false;
            else
                result.append("&");

            result.append(URLEncoder.encode(pair.getName(), "UTF-8"));
            result.append("=");
            result.append(URLEncoder.encode(pair.getValue(), "UTF-8"));
        }

        return result.toString();
    }

}

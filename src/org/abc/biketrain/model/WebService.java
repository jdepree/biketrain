package org.abc.biketrain.model;

import android.content.Context;
import android.util.Base64;
import org.abc.biketrain.utilities.ConnectionUtilities;
import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;

import java.io.IOException;
import java.net.URLEncoder;
import java.util.ArrayList;
import java.util.List;

public class WebService {
    private static final String URL_WEB_SERVER = "http://biketrain.foodnextdoor.org/";
    private static final String URL_REGISTER_ID = "registerId.php";
    private static final String URL_REGISTER_USER = "registerUser.php";
    private static final String URL_LOGOUT = "logout.php";
    private static final String URL_ROUTES = "rest/routes.php";
    private static final String URL_PATHS = "rest/paths.php";
    private static final String URL_LOCATION = "rest/location.php";

    /*public static boolean login(Context context, String userName, String password) throws IOException {
        String userPassword = userName + ":" + password;
        String encoded = Base64.encodeToString(userPassword.getBytes(), Base64.DEFAULT);
        ArrayList<NameValuePair> headerMap = new ArrayList<NameValuePair>();
        headerMap.add(new BasicNameValuePair("Authorization", "Basic " + encoded));
        String result = new String(ConnectionUtilities.getUrlString(URL_LOGIN, ConnectionUtilities.POST, null, headerMap));
        JsonParser parser = new JsonParser();
        JsonElement element = parser.parse(result);

        JsonElement token = null;
        if ((element.isJsonArray()) && ((element = ((JsonArray)element).get(0)) != null)
                && element.isJsonObject() && ((token = ((JsonObject)element).get("token")) != null)) {
            System.out.println("SUCCESSFULLY LOGGED IN");
            ConnectionUtilities.storeUserId(context, token.getAsString());
            return true;
        }
        System.out.println("FAILED TO LOG IN: " + result);
        return false;
    }*/

    public static void registerWithService(String userId, String serviceId) throws IOException {

    }

    public static void sendRegistrationIdToBackend(String regId) throws IOException {
        ConnectionUtilities.sendRequest(URL_REGISTER_ID + "?regId=" + URLEncoder.encode(regId, "UTF-8"), ConnectionUtilities.GET, null, null, null);
    }
}

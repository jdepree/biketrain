package org.abc.biketrain.controller;

import android.app.IntentService;
import android.app.PendingIntent;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.location.Location;
import android.location.LocationManager;

public class LocationReportingService extends BroadcastReceiver {
    private static final String ACTION_LOCATION = "org.abc.biketrain.ReportingService";

    private static LocationReportingService sService;

    private Context mAppContext;
    private LocationManager mLocationManager;

    public static final int POLL_INTERVAL = 20 * 1000;

    private LocationReportingService(Context appContext) {
        mAppContext = appContext;
        mLocationManager = (LocationManager)appContext.getSystemService(Context.LOCATION_SERVICE);
    }

    public static LocationReportingService getInstance(Context context) {
        if (sService == null) {
            sService = new LocationReportingService(context.getApplicationContext());
        }
        return sService;
    }

    private PendingIntent getLocationPendingIntent(boolean shouldCreate) {
        Intent broadcast = new Intent(ACTION_LOCATION);
        int flags = shouldCreate ? 0 : PendingIntent.FLAG_NO_CREATE;
        return PendingIntent.getBroadcast(mAppContext, 0, broadcast, flags);
    }

    public void startLocationUpdates() {
        String provider = LocationManager.GPS_PROVIDER;

        PendingIntent pi = getLocationPendingIntent(true);
        mLocationManager.requestLocationUpdates(provider, POLL_INTERVAL, 0, pi);
    }

    public void stopLocationUpdates() {
        PendingIntent pi = getLocationPendingIntent(false);
        if (pi != null) {
            mLocationManager.removeUpdates(pi);
            pi.cancel();
        }
    }

    public boolean isTracking() {
        return getLocationPendingIntent(false) != null;
    }

    @Override
    public void onReceive(Context context, Intent intent) {
        Location loc = (Location)intent.getParcelableExtra(LocationManager.KEY_LOCATION_CHANGED);
        if (loc != null) {
            onLocationReceived(context, loc);
            return;
        }

        if (intent.hasExtra(LocationManager.KEY_PROVIDER_ENABLED)) {
            boolean enabled = intent.getBooleanExtra(LocationManager.KEY_PROVIDER_ENABLED, false);
            onProviderEnabledChanged(enabled);
        }
    }

    protected void onLocationReceived(Context context, Location loc) {

    }

    protected void onProviderEnabledChanged(boolean enabled) {

    }
}

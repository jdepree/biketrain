package org.abc.biketrain.controller;

import android.app.IntentService;
import android.content.Intent;

public class ReportingService extends IntentService {

    public static final int POLL_INTERVAL = 20 * 1000;

    public ReportingService() { super(""); }

    public ReportingService(String name) {
      super(name);
    }

    @Override
    public void onHandleIntent(Intent intent) {

    }
}

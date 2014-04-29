package org.abc.biketrain.controller;

import android.app.Activity;
import android.content.Intent;
import android.os.Build;
import android.os.Bundle;
import android.os.Handler;
import org.abc.biketrain.R;
import org.abc.biketrain.utilities.ConnectionUtilities;

public class SplashActivity extends Activity {
    private static final int DISPLAY_LENGTH_MS = 2000;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_splash_screen);
    }

    @Override
    protected void onResume() {
        super.onResume();
        new Handler().postDelayed(new Runnable() {
            @Override
            public void run() {
                startNextActivity();
            }
        }, DISPLAY_LENGTH_MS);
    }

    protected void startNextActivity() {
        Intent intent;
        String userId = ConnectionUtilities.getUserId(this);
        if (userId.equals("")) {
            intent = new Intent(this, LoginActivity.class);
        } else {
            intent = MenuActivity.newIntent(this);
        }
        if (isLaunchExternal()) {
            intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
            if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.HONEYCOMB) {
                intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK);
            }
        }
        startActivity(intent);
        finish();
    }

    private boolean isLaunchExternal() {
        String action = getIntent().getAction();
        return Intent.ACTION_VIEW.equals(action);
    }
}


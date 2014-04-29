package org.abc.biketrain.controller;

import android.support.v4.app.Fragment;

public class LoginActivity extends SingleFragmentActivity {
    @Override
    public Fragment createFragment() {
        return LoginFragment.newInstance();
    }
}

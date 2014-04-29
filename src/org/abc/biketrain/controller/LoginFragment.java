package org.abc.biketrain.controller;

import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;
import org.abc.biketrain.R;
import org.abc.biketrain.model.WebService;

public class LoginFragment extends Fragment {
    private EditText mUsernameEdit;
    private EditText mPasswordEdit;
    private Button mLoginButton;

    public static LoginFragment newInstance() {
        return new LoginFragment();
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_login, container, false);

        mUsernameEdit = (EditText)view.findViewById(R.id.fragment_login_username_edit);
        mUsernameEdit.setText("jdepree");

        mPasswordEdit = (EditText)view.findViewById(R.id.fragment_login_password_edit);

        mLoginButton = (Button)view.findViewById(R.id.fragment_login_button);
        mLoginButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                new AsyncTask<Void, Void, Boolean>() {
                    @Override
                    public Boolean doInBackground(Void... params) {
                        try {
                            return true;//return WebService.login(getActivity(), mUsernameEdit.getText().toString(), mPasswordEdit.getText().toString());
                        } catch (Exception e) {
                            e.printStackTrace();
                        }
                        return false;
                    }

                    @Override
                    public void onPostExecute(Boolean loggedIn) {
                        if (!loggedIn) {
                            Toast.makeText(getActivity(), R.string.fragment_login_error, Toast.LENGTH_SHORT);
                        } else {
                            Intent intent = MenuActivity.newIntent(getActivity());
                            startActivity(intent);
                            getActivity().finish();
                        }
                    }
                }.execute(null, null, null);

            }
        });

        return view;
    }
}

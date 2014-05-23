package org.abc.biketrain.controller;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.widget.Button;
import android.widget.ListView;
import org.abc.biketrain.R;

public class MenuActivity extends Activity {

    private ListView mJoinTrainList;
    private ListView mStartTrainList;
    private Button mStartTrainButton;
    private Button mJoinTrainButton;

    public static Intent newIntent(Context context) {
        return new Intent(context, MenuActivity.class);
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.layout_main);

        mJoinTrainList = (ListView)findViewById(R.id.main_join_train_list);
        mJoinTrainList.setEmptyView(findViewById(R.id.main_join_existing_trains_none));

        mStartTrainList = (ListView)findViewById(R.id.main_start_train_list);

    }
}

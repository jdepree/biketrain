package org.abc.biketrain;

import android.app.Activity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.ListView;
import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.MapFragment;
import com.google.android.gms.maps.model.LatLng;

public class MenuActivity extends Activity {

    private ListView mJoinTrainList;
    private ListView mStartTrainList;
    private Button mStartTrainButton;
    private Button mJoinTrainButton;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.layout_main);

        mJoinTrainList = (ListView)findViewById(R.id.main_join_train_list);

        mJoinTrainButton = (Button)findViewById(R.id.main_join_train_button);
        mJoinTrainButton.setOnClickListener(new View.OnClickListener() {
            public void onClick(View v) {

            }
        });

        mStartTrainList = (ListView)findViewById(R.id.main_start_train_list);


        mStartTrainButton = (Button)findViewById(R.id.main_start_train_button);
        mStartTrainButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

            }
        });

    }
}

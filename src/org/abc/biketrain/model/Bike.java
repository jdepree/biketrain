package org.abc.biketrain.model;

public class Bike {
    private float mLatitude;
    private float mLongitude;
    private String mName;
    private float mSpeed;
    private int mDirection;
    private long mLastUpdated;

    public Bike(float lat, float lng, String name, float speed, int direction, long lastUpdated) {
        mLatitude = lat;
        mLongitude = lng;
        mName = name;
        mSpeed = speed;
        mDirection = direction;
    }
}

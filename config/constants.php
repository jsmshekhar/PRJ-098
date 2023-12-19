<?php

return [
    'NOTIFICATION_PARAMETER' => [
        'Subscription_Based' => 1,
        'Distance_Limit_Based' => 2,
        "Schedule_Notification" => 3,
        "Instant_Notification" => 4,
    ],
    'DISTANCE_REMAINING_TO_NOTIFY' => [
        '25.0 Km' => 25,
        '20.0 Km' => 20,
        '15.0 Km' => 15,
        '10.0 Km' => 10,
        '5.0 Km' => 5,
        '0.0 Km' => 0,
        '-5.0 Km' => -5,
        '-10.0 Km' => -10,
        '-15.0 Km' => -15,
        '-20.0 Km' => -20,
        '25.0 Km' => -25,
    ],
    'DAYS_REMAINING_TO_NOTIFY' => [
        '1 Days' => 1,
        '2 Days' => 2,
        '3 Days' => 3,
        '4 Days' => 4,
        '5 Days' => 5,
        '6 Days' => 6,
        '7 Days' => 7,
        '8 Days' => 8,
        '9 Days' => 9,
        '10 Days' => 10,
        '0 Days' => 0,
        '-1 Days' => -1,
        '-2 Days' => -2,
        '-3 Days' => -3,
        '-4 Days' => -4,
        '-5 Days' => -5,
        '-6 Days' => -6,
        '-7 Days' => -7,
        '-8 Days' => -8,
        '-9 Days' => -9,
        '-10 Days' => -10,
    ],

    'EV_CATEGORIES' => [
        'TWO_WHEELER' => 1,
        'THREE_WHEELER' => 2,
    ],

    'PROFILE_CATEGORIES' => [
        'CORPORATE' => 1,
        'INDIVIDUAL' => 2,
        'STUDENT' => 3,
        'VENDER' => 4,
    ],

    'COMPLAIN_STATUS' => [
        1 => 'Resolved',
        2 => 'Pending',
    ],
    'RENT_CYCLE' => [
        "15_DAYS" => 15,
        "30_DAYS" => 30,
    ],
    'BATTERY_TYPE' => [
        "SWAPPABLE" => 1,
        "FIXED" => 2,
    ],


];

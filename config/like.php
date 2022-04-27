<?php
/**
 * Configuration For Related to AmrNRD/Likable Package
 */
return [
    /**
     * Define the foregin key located inside the likable table to reference user ID
     */
    'user_foreign_key' => 'user_id',

    /**
     * Define Likable Table name
     */
    'likes_table' => 'likes',

    /**
     * Define Model That Will referece Likeable Table [ MorhpTo ]
     */
    'like_model' => \AmrNRD\Likeable\Like::class,
];

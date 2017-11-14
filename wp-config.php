<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         's9(_=Dv7D3K)!TY>V}24ci$-tJ;p2^rDBHw/p<~2xWagiF.CT-#*gT;+6Mtb&OMb');
define('SECURE_AUTH_KEY',  '=&f~!JL_k!A$ $[B{rfAwZ(}5RH?*h6zHG 8j0(n7{xV^Gz#F:`*J$^e5b Z$DhS');
define('LOGGED_IN_KEY',    'w1aPcl6/e96//o;}{,G8h68b=W:}e+:}CYoGd>G`R#{gW/n!G3f rio3$<ki6Mi;');
define('NONCE_KEY',        'D|dkd>/ckuq2w/ydZNU$E]S!0$%9AW?CbF7]BY1~inU6(B34@/xECHm_|~nLl@k<');
define('AUTH_SALT',        '|!$tNM[@80@A: &k]sfq5fY`F2.F/T3s?d7EWF,n3z1P7S&i]gH}mEFRA$KWmj`J');
define('SECURE_AUTH_SALT', '#%.qGvK6O)Pm6f99s&~e5]D6q;} UN_<@3/7@}lJV98f5x7Pd={d9b1-~>tzNp)3');
define('LOGGED_IN_SALT',   '& )v eVdNq[`#YV{r&Y[4+UJcJ,}l$H5H4(GYF3J>K1DxoE%QS}?&n@)%=f,l;FC');
define('NONCE_SALT',       'i+)_0kyt@#,.N~5ZXB+yq&_(~SfM6G0#RQ;mF9HP?8GK@uZC7O<eU3.ill3-gaSA');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'w4p__';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

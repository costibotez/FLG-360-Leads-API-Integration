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
define('DB_NAME', 'wp_flg360');

/** MySQL database username */
define('DB_USER', 'usr_flg360');

/** MySQL database password */
define('DB_PASSWORD', 'usr_flg360');

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
define('AUTH_KEY',         '6FxiP8h<Mc!yV|EohBaDmZpfa;8EJnf:E-| %O)S6G|a8Q3QT&+ bW_n%`=eh)DF');
define('SECURE_AUTH_KEY',  '4dN<.8[&( f|``V89#+HtX)sB.>K/;yw)L.w=5yI1 ozr!hn;xCH{^z-0;xml,Ea');
define('LOGGED_IN_KEY',    '|s[`q1G6Q-fHDY2qN>G8^J<|@vnt!/Ryf<~U328yW9:#C_xX~S3{h86U[4G54yds');
define('NONCE_KEY',        '=`8H.I2s3kVpJeDZxng`yrw]#1A`rBf&!TPy[4vm?^ki| #iY^D%hE!JNG{hUSTq');
define('AUTH_SALT',        '0|cVKRaYB1v[,+WRYr%otVeKC8=DLWqMM?0-9<W *Jhz=0nhi^%2`%&P8-<^%*(r');
define('SECURE_AUTH_SALT', 'Vem}7^:l*>pPK[~fw[1wTmWoU07hBP+jEe~gl!%P%Xl5>:/c_xe$vTm{&|JMPNHg');
define('LOGGED_IN_SALT',   '5.~%tY!IMP:Yrzci}:EQl$#g[THu7,,RX8WT%`#:(-}l4B!ZfhE)IQu/]9:<)?0w');
define('NONCE_SALT',       'rWt5r;,*./])BxUVfu9R!1S:PCAThZybR|exc^d$7jA5pe$(Je<c^7{J0~eHn%qC');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

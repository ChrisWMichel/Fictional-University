<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */
require_once('wp-config-private.php');
// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          '>9N2ce-BfqZy%r]l|@m0`3b-Y{}%zM2V`(/iEKgKg:O#TK#i@V$[pE$u5MuORa&k' );
define( 'SECURE_AUTH_KEY',   'r4[iz/KRc4Z2q@nUdmpFl0`-2sK?`]a`1;zDOgH)aMn2S/m;4<q1{27;>~s|&_e]' );
define( 'LOGGED_IN_KEY',     '+I!*oTO7jrFM:s-C06SHrQu^tS>XI5m]Rpi eqSXK:EDI.w-]+,7R[CUmgSL,(i:' );
define( 'NONCE_KEY',         '[(yvv?B[GrhY0-1yP.<`!>{ace91YxtF RT^b|si^!P%tWu0rgG3+gsnq{yAzn2a' );
define( 'AUTH_SALT',         'g-0T01%MVA}ONW*Fnw93b)JR4(DV~;d.YK{wOPk@Sg`M=9ptEYit8*{ Z.; Pru,' );
define( 'SECURE_AUTH_SALT',  'tiE~Opg.J~::A]+<M_h>gQ<$cw4G;L?+}`Ib[13o!3BrRmG~(J=r%74a*RP:?|jN' );
define( 'LOGGED_IN_SALT',    ',#P^H3`G3pq;d!y>EEQ`o}AYVz42c-z=mcBq&F(*(i.,M!XKG?0aE-DN&?+!TF@%' );
define( 'NONCE_SALT',        ':U_1M8QF|;L4d1ZN6$jUQo&?=R6IP5ZKRw8RSIH5a:6:oMN^{^P,>~*8L{CE=.Jy' );
define( 'WP_CACHE_KEY_SALT', '`]#qfStUZq;h*6[^uN-31%oZfp14 5)Hj5yf]{uW1w86)[8-IN<+JXJ^uV8E,cfV' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

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
define( 'DB_NAME', 'vancutravel' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'b%?7/u]lKq~7K@A8s#e=(P%c{0Z8865REi/yxq;T~9#=jdiH0D.-JjYPDcrTVLeE' );
define( 'SECURE_AUTH_KEY',  'H|wF3>_KKEtkN5S=`M9SyYZoWoO`5aZb*0vdcmY.BDIoUa+HIpyI^V~65eq[F)Rd' );
define( 'LOGGED_IN_KEY',    '.1=WavjGi3lt5dra&U##*DVDEuDH9-9PQ+u[]^+z89!dj]iY7V1LFO_*,CGY*!Vh' );
define( 'NONCE_KEY',        'I)q7?=vtynuzrhB [FjM!GNEmeo^dg>Eu0#hU%%R3<ZUx6/Be#!`XGBz8H-4Y+L`' );
define( 'AUTH_SALT',        ' AAD6w<#J9`J-QZ@ciQg!$<x5)8FbQw:ze%u1~`xh.}0&#[n%[1qEu;|U_o#Fe.O' );
define( 'SECURE_AUTH_SALT', 'W@+AibCg-1|8s_SMK+!L4$Lb{zqp+*{j`;ZwSMqq-z_$3]i2NN@>Z}1LqAW$ZGR|' );
define( 'LOGGED_IN_SALT',   '.}u%hrIUhdg&89MDcOF>e8dh;2Cve-Q0jT%y00jtt<(%EGw6^6yRXHONxl@I<2#X' );
define( 'NONCE_SALT',       '6JJ_Z>^So$x(590W/-gV{EBrdk!}!ZM7=N]4+B?~u/?u{p%;##F8`n`]UE:G%WFW' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );

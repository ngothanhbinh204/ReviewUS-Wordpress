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
define( 'AUTH_KEY',          '<5_s~b@e>zEmQC@W9w+eSGx+:%)OC=vY^ 65k8D@W4A:5O[%}~.frOOU8D%6OnHa' );
define( 'SECURE_AUTH_KEY',   '`PwIuWJVB.5v+&ujMwc6alV_#0w`nf-jfGOwZ,4]%g,6Fa*l)85Hq]^%mx-|:tE,' );
define( 'LOGGED_IN_KEY',     'LHtSerZdhv8zT.>Z,0D)M|q<?xI`_ $s};9mNf}WP;zSU @qXi]E%*++6LJpNq|s' );
define( 'NONCE_KEY',         'D#D1QZ`R/Vfy!bIU&vh=CfyG~MBl DYA7iwNIKB4SB=HZ50q%k<+|h33s/RM?)sc' );
define( 'AUTH_SALT',         'rt:b>|${)-1PM-&WO*{<t/6T*#]eB8`Wi/ ?-g[:O0s3}&vLRZA2pN:@r7`xkIA2' );
define( 'SECURE_AUTH_SALT',  'l2/w)[=MuXn|Jf[v$!G8%SnU#U9]pwsw#u*BJnU&q.7al4zB!~u=mkdP:Bd~_r93' );
define( 'LOGGED_IN_SALT',    '(.`d8CEk`Zf8x33IlB_O)1I!]VpNduP@wR+>Fa*ZLbz Fhnt2|=SIIdJi@nyM_C)' );
define( 'NONCE_SALT',        'X`NFk^q~(xiFlg/BxFf)T8Hrzy] G[!SyG%]wROrLa3B^_f`y,cc$mzi<Ve$V)lj' );
define( 'WP_CACHE_KEY_SALT', '!?hmVYOXB/PPdmZwy,3Hyl1k~}*fq+*X! +?UMf~3<o!`gTzAZK?pk;G7dcL=~ 3' );


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
// if ( ! defined( 'WP_DEBUG' ) ) {
// 	define( 'WP_DEBUG', false );
// }
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors', 0);
define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
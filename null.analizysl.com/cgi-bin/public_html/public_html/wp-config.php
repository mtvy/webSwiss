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
define( 'DB_NAME', 'swissl01_null' );

/** MySQL database username */
define( 'DB_USER', 'swissl01_null' );

/** MySQL database password */
define( 'DB_PASSWORD', 'Jkbt54D9gD1b' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         'W+L?By|_N0r5,>.B;djQFgM2jaCLp;JL)Tm0! [,+#Q~#|2hfZFfIE.]v,|`K_lu' );
define( 'SECURE_AUTH_KEY',  'Y{=4?cmxZ+}=#B0:7*1so~B2+>c@2!2)!I|xx=Ggi0A_;o:SqduR)*J0-eh9Wq|!' );
define( 'LOGGED_IN_KEY',    '|5{D.05lkwZPBR:{@8:I]4P,`Mqo%u;RXXHcp:Mbdk<~FAUc{D25u~Jc6*@_]&@p' );
define( 'NONCE_KEY',        'NS,~B^! n$M&.g][Hwy`an2&OAUpCq2!NI.mi$~ycH#UY<80WuD/1 pjza7<:cr2' );
define( 'AUTH_SALT',        'b:x* $:pP1ot!|ljJP,p:M4 ix=G46n>#n1c/w7aC-U]#hrL8s5>&]|#IX6Awdgb' );
define( 'SECURE_AUTH_SALT', 'DO0lo!k tCmV[$CW*QE?-iWG-Dio,&GwQrvB~%2UGL9FaSL5J)?^h0]m*wWl*&;,' );
define( 'LOGGED_IN_SALT',   'zW[3^&+Z-VNpGfq~Yu]gy()V5(]=AC5@]2zfEJ2EpS]49X9$g/[#.I}]=.rU1SIy' );
define( 'NONCE_SALT',       'Pm]0#YzR>l>[g &ML~=&DWb9*PNi?.*ae-w|}B?d+3;%wD^2Pt`6U8fF7Y=4|g;v' );

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
define( 'WP_DEBUG', true );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );

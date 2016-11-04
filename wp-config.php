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
define('DB_CHARSET', 'utf8');

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
 define('AUTH_KEY',         '3bf$U47B&,q)SF|8ad1t8n-g(q}6m:}L>i1,bzc($)0x3%7/W!U3V-AbN,#?x`;;');
 define('SECURE_AUTH_KEY',  '~0k)D%9@kbc-,+~VYy(}#WBJ&q~(Ss|un6rt_-?IF% pccp0I})0wp>+vUBSNm0K');
 define('LOGGED_IN_KEY',    'U,QsIHHtpDrV5OD0D6(Br52bZuN?WvW<W+GYd^E$El+H,tZA}|]%2/U+|MC41x_D');
 define('NONCE_KEY',        '2|iOc&LV+m@rjYwT>CTB>w0)=muV-]XQ6bPG /l{|td1I4 KV-s@txCf%WlPGps$');
 define('AUTH_SALT',        'wsd5RkyFnVh}L-jy?X oZoA2x9Iiih:oE@0}Xn#It<WzF6,z|Q%,,h3L<KW4+qn~');
 define('SECURE_AUTH_SALT', 'iCHj):GFa|[ib[Txw:Xl#aR|,24+`!;23Y]p#fCrhZ29C*E,IzD9 o>@K,g-|Q/k');
 define('LOGGED_IN_SALT',   '+HFEs$CU4t~|2G3DgK{00N%_R{Z=ZKBZBsrSN<L3aH;e5HR+Ri3KTS)Y3y)=m|K3');
 define('NONCE_SALT',       '~~i|]7pj $5eJ|Xwj[8yEI6p*Z~HQFw.Y:R)oA q-|(]kQ1W-&}{BeI@t~kx<<2i');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'ddt32_';

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

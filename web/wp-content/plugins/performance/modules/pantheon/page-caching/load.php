<?php
/**
 * Module Name: Pantheon Page Cache Health Check
 * Description: Adds a page cache health check for sites hosted on Pantheon.
 * Experimental: Yes
 *
 * @package performance-lab
 */

/**
 * Adds a health check testing for and suggesting a persistent object cache backend.
 *
 * @since 1.0.0
 *
 * @param array $tests An associative array of direct and asynchronous tests.
 * @return array An associative array of direct and asynchronous tests.
 */
function perflab_pantheon_page_caching_add_tests( $tests ) {
	if ( wp_get_environment_type() === 'production' ) {
		$tests['direct']['pantheon_page_caching'] = array(
			'label' => __( 'Pantheon page caching', 'performance-lab' ),
			'test'  => 'perflab_pantheon_page_caching',
		);
	}

	return $tests;
}

add_filter( 'site_status_tests', 'perflab_pantheon_page_caching_add_tests' );

/**
 * Callback for `pantheon_page_caching` health check.
 *
 * @since 1.0.0
 *
 * @return array The health check result suggesting page caching.
 */
function perflab_pantheon_page_caching() {

	$result = array(
		'test'        => 'pantheon_page_caching',
		'status'      => 'good',
		'badge'       => array(
			'label' => __( 'Performance', 'performance-lab' ),
			'color' => 'blue',
		),
		'label'       => __( 'Page is caching and advanced control is enabled.', 'performance-lab' ),
		'description' => sprintf(
			'<p>%s</p>',
			__( "The homepage and stylesheets are cacheable. The Pantheon Advanced Caching plugin is enabled.", 'performance-lab' )
		),
		'actions'     => sprintf(
			'<p><a href="%s" target="_blank" rel="noopener">%s <span class="screen-reader-text">%s</span><span aria-hidden="true" class="dashicons dashicons-external"></span></a></p>',
			esc_url( 'https://pantheon.io/docs/wordpress-cache-plugin' ),
			__( 'Learn more about page caching.', 'performance-lab' ),
			/* translators: Accessibility text. */
			__( '(opens in a new tab)', 'performance-lab' )
		),
	);

	if ( ! perflab_oc_health_should_suggest_persistent_object_cache() ) {
		$result['label'] = __( 'A persistent object cache is not required', 'performance-lab' );

		return $result;
	}

	$result['status']       = 'recommended';
	$result['label']        = __( 'You should use full page caching', 'performance-lab' );
	$result['description'] .= sprintf(
		'<p>%s</p>',
		'It will speed up your site load time.'
	);

	return $result;
}

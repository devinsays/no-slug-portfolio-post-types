<?php
/**
 * No Slug Portfolio Post Types
 *
 * @package   No_Slug_Post_Types
 * @author    Devin Price
 * @license   GPL-2.0+
 * @link      http://vip.wordpress.com/documentation/remove-the-slug-from-your-custom-post-type-permalinks/
 *
 * @wordpress-plugin
 * Plugin Name: No Slug Portfolio Post Types
 * Plugin URI:  http://wptheming.com/
 * Description: Removes the portfolio slug from custom post types.
 * Version:     1.0.0
 * Author:      Devin Price
 * Author URI:  http://www.wptheming.com/
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Remove the slug from custom post type permalinks.
 */
function nsppt_remove_cpt_slug( $post_link, $post, $leavename ) {

    if ( ! in_array( $post->post_type, array( 'portfolio' ) ) || 'publish' != $post->post_status )
        return $post_link;

    $post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );

    return $post_link;
}
add_filter( 'post_type_link', 'nsppt_remove_cpt_slug', 10, 3 );

/**
 * Some hackery to have WordPress match postname to any of our public post types
 * All of our public post types can have /post-name/ as the slug, so they better be unique across all posts
 * Typically core only accounts for posts and pages where the slug is /post-name/
 */
function nsppt_parse_request_tricksy( $query ) {

    // Only noop the main query
    if ( ! $query->is_main_query() )
        return;

    // Only noop our very specific rewrite rule match
    if ( 2 != count( $query->query )
        || ! isset( $query->query['page'] ) )
        return;

    // 'name' will be set if post permalinks are just post_name, otherwise the page rule will match
    if ( ! empty( $query->query['name'] ) )
        $query->set( 'post_type', array( 'post', 'portfolio', 'page' ) );
}
add_action( 'pre_get_posts', 'nsppt_parse_request_tricksy' );
<?php
/**
 * Plugin Name: Remove noreferrer
 * Plugin URI: https://www.wpabcs.com/
 * Description: Filter and remove noreferrer from the rel attribute with DOMXPath
 * Version: 1.0
 * Author: wpabcs
 */

function wpabcs_remove_noreferrer( $content ) {
    if( empty( $content ) ) {
        return $content;
    }
    
    // Create new PHP DOM object
    $dom = new \DOMDocument();

    // Load the $content into the DOM object, LIBXML_HTML_NOIMPLIED turns off the automatic adding of html/body elements | LIBXML_HTML_NODEFDTD prevents a default doctype being added when one is not found.
    $content = $dom->loadHTML( $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD ); 

    // Create an instance of DOMXpath
    $xpath = new \DOMXpath( $dom );

    // Find me the anchor element with the rel attribute
    $anchor_attribute = $xpath->query( "/descendant::a[@rel]" );

    foreach ( $anchor_attribute as $anchor_rel ) {
        $rel_value = str_replace( 'noreferrer', '', $anchor_rel->getAttribute( 'rel' ) );
        $anchor_rel->setAttribute( 'rel', trim( $rel_value ) );
    }

    // Save the updated HTML
    $content = $dom->saveHTML();

    return $content;
}
add_filter( 'the_content', 'wpabcs_remove_noreferrer' );
?>

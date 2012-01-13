<?php
/**
 * Contains all hook wrappers.
 *
 * @package Shiword
 * @since Shiword 3.0
 */

 
function shiword_hook_before_header() {
	do_action('shiword_hook_before_header');
}

function shiword_hook_after_header() {
	do_action('shiword_hook_after_header');
}

function shiword_hook_before_posts() {
	do_action('shiword_hook_before_posts');
}

function shiword_hook_after_posts() {
	do_action('shiword_hook_after_posts');
}

function shiword_hook_before_post() {
	do_action('shiword_hook_before_post');
}

function shiword_hook_after_post() {
	do_action('shiword_hook_after_post');
}

function shiword_hook_before_post_title() {
	do_action('shiword_hook_before_post_title');
}

function shiword_hook_after_post_title() {
	do_action('shiword_hook_after_post_title');
}

function shiword_hook_before_comments() {
	do_action('shiword_hook_before_comments');
}

function shiword_hook_after_comments() {
	do_action('shiword_hook_after_comments');
}

function shiword_hook_before_right_sidebar_content() {
	do_action('shiword_hook_before_right_sidebar_content');
}

function shiword_hook_after_right_sidebar_content() {
	do_action('shiword_hook_after_right_sidebar_content');
}

function shiword_hook_before_footer() {
	do_action('shiword_hook_before_footer');
}

function shiword_hook_footer() {
	do_action('shiword_hook_footer');
}

function shiword_hook_after_footer() {
	do_action('shiword_hook_after_footer');
}

function shiword_hook_statusbar() {
	do_action('shiword_hook_statusbar');
}

function shiword_hook_before_footer_sidebar_content() {
	do_action('shiword_hook_before_footer_sidebar_content');
}

function shiword_hook_after_footer_sidebar_content() {
	do_action('shiword_hook_after_footer_sidebar_content');
}

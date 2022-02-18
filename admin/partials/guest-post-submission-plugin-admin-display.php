<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/sachin160290/Guest-Post-Submission-Plugin
 * @since      1.0.0
 *
 * @package    Guest_Post_Submission_Plugin
 * @subpackage Guest_Post_Submission_Plugin/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <h2>Instructions</h2><hr>
    <p>Once you download and install this plugin you have to follow the following steps to see the output.</p>
    <ol>
        <li><p>Use <strong>[guest_post_submission_form]</strong> this shortcode to print the Ajax Form for frontend to submit guest post.</p></li>
        <li><p>Use <strong>[show_pending_guest_posts]</strong> this shortcode to print the list of all unpublished or pending posts posted by the Guest User.</p></li>
        <li><p>Use <strong>[show_all_posts_by_guest]</strong> this shortcode to print the list of all posts posted by the Guest User.</p></li>
        <li>
            <p>Guest User Login credentials are below:</p>
            <p>Username: <strong>guest_user</strong></p>
            <p>Email: <strong>guest_user@example.com</strong></p>
            <p>Password: <strong>guest_user@123</strong></p>
        </li>
    </ol>    
</div>  
<?php
/**
 * Theme Starter Widget Template
 *
 * IMPORTANT: Before deploying to production, remove any placeholder/debug code below.
 * Do NOT leave error_log(), var_dump(), print_r(), or other debugging output in production.
 */
$uu_theme_starter_widget_text = $instance['uu_theme_starter_widget_text'] ?? '';

if (!empty($uu_theme_starter_widget_text)) {
    echo esc_html($uu_theme_starter_widget_text); 
  }
?>
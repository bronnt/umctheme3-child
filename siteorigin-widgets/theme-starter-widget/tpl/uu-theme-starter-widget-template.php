<?php
  $uu_theme_starter_widget_text = $instance['uu_theme_starter_widget_text'] ?? '';
	// $uniqid = uniqid("uu_sample_widget_");
  if (!empty($uu_theme_starter_widget_text)) { 
    echo esc_html($uu_theme_starter_widget_text); 
  }
?>
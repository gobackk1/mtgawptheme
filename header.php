<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header class="Header scrollbarFix">
  <h1 class="Header__ttl"><a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a></h1>
</header>
<?php get_template_part('nav'); ?>
<?php if(is_active_sidebar('nav')): ?>
<?php dynamic_sidebar('drawer'); ?>
<?php endif ?>

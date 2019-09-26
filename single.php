<?php get_header(); ?>
<main class="main">
<div class="Page-ttl"><h1><?php the_title(); ?></h1></div>
<div class="Archive-bg">
<div class="Container">
<div class="Layout-01">

  <?php if(have_posts()): while(have_posts()):the_post(); ?>
  <article <?php post_class('blocks'); ?>>
  <?php if(!has_block('image')): ?>
  <?php if(has_post_thumbnail()): ?>
  <figure class="p-thumbnail">
    <?php the_post_thumbnail(); ?>
  </figure>
  <?php endif; ?>
  <?php endif; ?>
  <?php the_category(); ?>
  <h1><?php the_title(); ?></h1>
  <time class="p-time" datetime="<?php echo esc_attr(get_the_date(DATE_W3C)); ?>">
    <i class="far fa-clock"></i>
    <?php echo esc_html(get_the_date()); ?>
  </time>
  <?php the_content(); ?>
  </article>
  <?php endwhile; endif; ?>
  <?php the_post_navigation(); ?>
</div>
</div>
</div>
</main>
<?php get_sidebar(); ?>
<?php get_footer(); ?>

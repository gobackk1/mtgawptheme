<?php get_header(); ?>
<main class="main">
  <?php if(have_posts()): while(have_posts()):the_post(); ?>
  <article <?php post_class('blocks'); ?>>
  <?php if(has_post_thumbnail()): ?>
  <figure class="p-thumbnail">
    <?php the_post_thumbnail(); ?>
  </figure>
  <?php endif; ?>
  <h1><?php the_title(); ?></h1>
  <?php the_content(); ?>
  </article>
  <?php endwhile; endif; ?>
</main>
<?php get_sidebar(); ?>
<?php get_footer(); ?>

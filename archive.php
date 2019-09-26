<?php get_header(); ?>
<main class="postlist main">
<div class="Page-ttl"><h1><?php the_archive_title(); ?></h1></div>
<div class="Archive-bg">
<div class="Container">
<div class="Layout-01">
<?php if(have_posts()): while(have_posts()):the_post(); ?>
<article <?php post_class('Card-02'); ?>>
<a href="<?php the_permalink(); ?>">
<?php if(has_post_thumbnail()): ?>
<figure>
<?php the_post_thumbnail(); ?>
</figure>
<?php else: ?>
<figure>
<img src="<?php echo get_template_directory_uri(); ?>/img/default.jpg" alt="">
</figure>
<?php endif; ?>
<div class=""><?php echo get_the_date(); ?></div>
<h2 class="Card-02__ttl"><?php the_title(); ?></h2>
</a>
</article>
<?php endwhile; endif; ?>
<?php the_posts_pagination(); ?>
</div>
</div>
</div>
</main>
<?php get_sidebar(); ?>
<?php get_footer(); ?>

<?php if (post_custom('footer')) : ?>
<?php echo get_post_meta($post->ID, 'footer', true); ?>
<?php endif; ?>
<?php wp_footer(); ?>
</body>
</html>

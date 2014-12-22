<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article">

  <header class="article-header">


    <?php echo responsive_image_thumbnail(null, 'feed-image'); ?> <?php // ignore markup for now ?>

    <h1 class="h2 entry-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
    <p class="byline vcard">
                          <?php printf( __( 'Posted <time class="updated" datetime="%1$s" pubdate>%2$s</time> by <span class="author">%3$s</span>', 'bonestheme' ), get_the_time('Y-m-j'), get_the_time(get_option('date_format')), get_the_author_link( get_the_author_meta( 'ID' ) )); ?>
    </p>

  </header>

  <section class="entry-content cf">
    <?php the_content(); ?>
  </section>

  <footer class="article-footer cf">
    <p class="footer-comment-count">
      <?php comments_number( __( '<span>No</span> Comments', 'bonestheme' ), __( '<span>One</span> Comment', 'bonestheme' ), __( '<span>%</span> Comments', 'bonestheme' ) );?>
    </p>


    <?php printf( '<p class="footer-category">' . __('filed under', 'bonestheme' ) . ': %1$s</p>' , get_the_category_list(', ') ); ?>

    <?php the_tags( '<p class="footer-tags tags"><span class="tags-title">' . __( 'Tags:', 'bonestheme' ) . '</span> ', ', ', '</p>' ); ?>


  </footer>

</article>
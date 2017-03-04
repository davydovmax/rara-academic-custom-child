<?php
    $target_category = $instance['target_category'];
    $number_of_rows = $instance['number_of_rows'];

    $posts = new WP_Query( array(
        'posts_per_page'      => -1,
        'post_type'           => 'post',
        'ignore_sticky_posts' => true,
        'cat'                 => $target_category,
    ) );
    $counter = 0;
?>

<?php if($posts->have_posts()) : ?>
<section class="popular-courses">
    <div class="container">

<?php while( $number_of_rows > 0 && $posts->have_posts() )
{
    $posts->the_post();
    $emmit_row_start = ($counter % 3) == 0;
    $emmit_row_end = ($counter % 3) == 2;
    ++$counter;
?>

<?php if ( $emmit_row_start ) { ?>
        <div class="row">
<?php } ?>

            <div class="col-3">
                <article class="post">
                    <?php
                        if( has_post_thumbnail() ){ ?>
                            <a href="<?php the_permalink(); ?>" class="post-thumbnail">
                                  <?php the_post_thumbnail( 'rara-academic-courses-blog' ); ?>
                            </a>
                    <?php
                        } ?>
                        <header class="entry-header">
                            <h3 class="entry-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title() ;?></a>
                            </h3>
                        </header>

                        <div class="entry-content">
                            <?php the_excerpt(); ?>
                        </div>
                </article>
            </div>

<?php if ( $emmit_row_end ) { --$number_of_rows; ?>
        </div>
<?php } ?>

<?php
    }
    wp_reset_postdata();
?>
    </div>
</section>

<?php endif; ?>

<div class="mod_posts">
    <h3 class="mod-caption"><?php echo $title;?></h3>
    <div class="posts-mod-wr">
    <?php
        foreach ($posts as $post){ ?>
            <div class="posts-mod_item">
                <div class="bgimg" style="background-image: url(<?php echo get_the_post_thumbnail_url($post->ID);?>)">
                    <a href="<?php echo get_permalink($post->ID); ?>"></a>
                </div>
                <div class="info-bl">
                    <div class="date"><?php echo date('d.m.y',strtotime($post->post_date));?></div>
                    <h4>
                        <a href="<?php echo get_permalink($post->ID);?>"><?php echo $post->post_title ?></a>
                    </h4>
                    <div class="pre-text"><?php echo $post->post_excerpt ?></div>
                </div>
             </div>

    <?php } ?>
    </div>
</div>
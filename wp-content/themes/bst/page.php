<?php get_template_part('parts/header'); ?>
<div class="container">
  <div class="row">
  
    <div class="col-xs-12 col-sm-3"><?php  //wp_nav_menu() ?>
    <ul id="lhs-menu">
    <?php 
        global $post;
        $ancestors = get_post_ancestors( $post );
        $menupost = get_post($ancestors[count($ancestors) - 1]);
        $args = array(
            'authors'      => '',
            'child_of'     => $menupost->ID,
            'date_format'  => get_option('date_format'),
            'depth'        => 0,
            'echo'         => 1,
            'exclude'      => '',
            'include'      => '',
            'link_after'   => '',
            'link_before'  => '',
            'post_type'    => 'page',
            'post_status'  => 'publish',
            'show_date'    => '',
            'sort_column'  => 'menu_order, post_title',
            'sort_order'   => '',
            'title_li'     => __('<a href="' . $menupost->guid .'" id="lhs-menu-title" name="' . $menupost->ID . '">' .$menupost->post_title . '</a>'), 
            'walker'       => ''
        );
        wp_list_pages( $args );
    ?>
    </ul>
    </div>
    
    <div class="col-xs-12 col-sm-9">
      <div id="content" role="main">
        <?php if(have_posts()): while(have_posts()): the_post();?>
        <article role="article" id="post_<?php the_ID()?>" <?php post_class()?>>
          <header>
            <h2><?php the_title()?></h2>
            <hr/>
          </header>
          <?php the_content()?>
        </article>
        <?php endwhile; ?> 
        <?php else: ?>
        <?php wp_redirect(get_bloginfo('siteurl').'/404', 404); exit; ?>
        <?php endif;?>
      </div><!-- /#content -->
    </div>
    
    <!--<div class="col-xs-6 col-sm-2" id="sidebar" role="navigation">
      <?php //get_template_part('parts/sidebar'); ?>
    </div>-->
    
  </div><!-- /.row -->
</div><!-- /.container -->

<?php get_template_part('parts/footer'); ?>

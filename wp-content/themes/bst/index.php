<?php get_template_part('parts/header'); ?>

<!-- start carousel -->
<div id="main-carousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <li data-target="#main-carousel" data-slide-to="0" class="active"></li>
        <li data-target="#main-carousel" data-slide-to="1"></li>
        <li data-target="#main-carousel" data-slide-to="2"></li>
        <li data-target="#main-carousel" data-slide-to="3"></li>
        <li data-target="#main-carousel" data-slide-to="4"></li>
        <li data-target="#main-carousel" data-slide-to="5"></li>
        <li data-target="#main-carousel" data-slide-to="6"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner">
        <div class="item active">
            <div class="carousel-image-wrapper"><img src="/wp-content/themes/bst/img/carousel-1.jpg" alt="Background image 1"></div>
            <div class="carousel-caption">
                <h2>Helping you find the right biodiversity information from SANBI</h2>
                <p>Access information held by SANBI on South Africa's biodiversity. Use our resources for decision-making, planning and research.</p>
            </div>
        </div>
        <div class="item">
            <div class="carousel-image-wrapper"><img src="/wp-content/themes/bst/img/carousel-2.jpg" alt="Background image 2"></div>
            <div class="carousel-caption">
                <h2>Planning &amp; assessment tools to safeguard South Africa's biodiversity</h2>
                <p>South Africa has a wealth of valuable, natural resources which are under threat of degradation by competing land-uses.</p>
                <div class="linkset"></div>
            </div>
        </div>
        <div class="item">
            <div class="carousel-image-wrapper"><img src="/wp-content/themes/bst/img/carousel-3.jpg" alt="Background image 3"></div>
            <div class="carousel-caption">
                <h2>Biodiversity &amp; industry, working hand in hand.</h2>
                <p>The primary sectors of industry (agriculture, mining and other activities) need not negatively impact conservation. </p>
                <div class="linkset"></div>
            </div>
        </div>
        <div class="item">
            <div class="carousel-image-wrapper"><img src="/wp-content/themes/bst/img/carousel-4.jpg" alt="Background image 4"></div>
            <div class="carousel-caption">
                <h2>Checklists &amp; modelling; working with species and specimen information</h2>
                <p>Providing guidance on using biodiversity data models to map the distribution of species based on habitat suitability.</p>
                <div class="linkset"></div>
            </div>
        </div>
        <div class="item">
            <div class="carousel-image-wrapper"><img src="/wp-content/themes/bst/img/carousel-5.jpg" alt="Background image 5"></div>
            <div class="carousel-caption">
                <h2>Learning networks to train talent, and build citizen science initiatives</h2>
                <p>Learn about Biodiversity Forums and international and Africa-wide regional engagement.</p>
                <div class="linkset"></div>
            </div>
        </div>
        <div class="item">
            <div class="carousel-image-wrapper"><img src="/wp-content/themes/bst/img/carousel-6.jpg" alt="Background image 6"></div>
            <div class="carousel-caption">
                <h2>Biodiversity data</h2>
                <p>Access online and offline information resources and data from SANBI's projects.</p>  
                <div class="linkset"></div>
            </div>
        </div>
        <div class="item">
            <div class="carousel-image-wrapper"><img src="/wp-content/themes/bst/img/carousel-7.jpg" alt="Background image 7"></div>
            <div class="carousel-caption">
                <h2>Documents &amp; links</h2>
                <p>Useful documents, manuals and links concerning biodiversity information at SANBI.</p>  
                <div class="linkset"></div>
            </div>
        </div>
    </div>

    <!-- Controls -->
    <a class="left carousel-control" href="#main-carousel" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left"></span>
    </a>
    <a class="right carousel-control" href="#main-carousel" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right"></span>
    </a>
</div>
<!-- end carousel -->

<div class="container">
  <div class="row row-offcanvas">
    
    <div class="col-xs-12 col-sm-8 pages-list">
      <div id="content" role="main">
        <h2>Recently updated <small>Biodiversity Advisor pages</small></h2>
      <?php 
        $args = array( 
            //'pagename' => 'about',
            //'orderby' => 'title'
            //'post_status' => 'publish'
            'post_type' => 'page',
            'posts_per_page' => 3,
            'orderby' => 'post_modified'
        );
        $the_query = new WP_Query( $args );
        //echo $the_query->request;
        if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
        <article class="row">
            <div class="col-sm-3"><?php the_post_thumbnail('thumbnail'); ?></div>
            <div class="col-sm-9">
                <h3><?php the_title(); ?></h3>			
                <p class="date">Updated on <?php the_modified_date(); ?></p>
                <?php the_excerpt(); ?>
                <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-main-link">Visit page</a>
            </div>
         </article>
    <?php endwhile; else: ?>
        <p>There are no posts to display</p>
    <?php endif; ?>
      </div><!-- /#content -->
    </div>
    
    <div class="col-xs-6 col-sm-4 rss" id="sidebar">
        <h2>Latest news <small>from  <a href="http://www.sanbi.org/news">sanbi.org</a></small></h2>
       <?php //get_template_part('parts/sidebar'); ?>
       <?php 
       // Get the RSS feed
       $rss = simplexml_load_file('http://www.sanbi.org/news/science/feed');
       
       // Set the number of words we want in the description for each item
       $wordlength = 250; 
       
       foreach ($rss->channel->item as $item) {
       
            // Get the full description (this is all the text in the node)
            $desc = $item->description;
            
            // Retrieve the first image
            preg_match('/<img.+src=[\'"]\/sites\/default(?P<src>.+?)[\'"].*>/i', $desc, $image);
            $image = $image['src'];
            
            // Remove all of the HTML tags and truncate the description
            $desc = strip_tags($desc);
            $desc = preg_replace('/\s+?(\S+)?$/', '', substr($desc, 0, $wordlength));
            $desc = explode("CommentsAdd a comment", $desc)[0];
            
            // Add all the other elements into vars
            $link = $item->link;
            $title = $item->title;
            $date = date('d F Y', strtotime($item->pubDate));
            
            // Print out the RSS item
            ?>
            <article>
                <h1><a href="<?php echo $link ?>" title="<?php echo $title ?>'"><?php echo $title ?></a> <small><?php echo $date ?></small></h1>
                
                <?php if($image) { ?><img src="http://www.sanbi.org/sites/default<?php echo  $image ?>" alt="News item image" class="img-thumbnail"><?php } ?>
                <p><?php echo $desc ?></p>
            </article>
            <?php
        }
     ?>
     <p class="rss-footer">Read more news on <a href="http://www.sanbi.org/news">sanbi.org</a></p>
    </div>
    
  </div><!-- /.row -->
</div><!-- /.container -->

<?php get_template_part('parts/footer'); ?>

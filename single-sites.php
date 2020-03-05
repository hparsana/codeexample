<?php
/*
This is the custom post type post template.
If you edit the post type name, you've got
to change the name of this template to
reflect that name change.

i.e. if your custom post type is called
register_post_type( 'bookmarks',
then your single template should be
single-bookmarks.php

*/
?>

<?php get_header(); ?>

<div class="content">


  <?php // password protection
  if(!empty($post->post_password) && post_password_required()) : ?>

    <div class="box-wr box-QQQ text-center bg-gray">
      <div class="container wow fadeIn" data-wow-delay="0.2s">
        <div class="row">
          <div class="col-sm-12">
            <?php echo get_the_password_form(); ?>
          </div>
        </div>
      </div>
    </div>
  
  <?php else : ?>

    <?php

    // are we on the "home" of this client site?

      $hash = get_field('hash');
      $client_name = get_field('client_name');

      $url = sanitize_title($client_name.'-'.$hash.'-home');

      $home = get_page_by_path( $url, OBJECT, 'sites' );

      if(get_the_id() == $home->ID) : ?>

      <?php require_once( 'library/page-builder.php' ); ?>

      <?php else : ?>

      <?php if(get_field('whitelabel', get_the_ID()) && get_field('whitelabel_logo', get_the_ID())) :

      // we're on a client site and whitelabel is active, but we dont want to change the "home page"

      $hash = get_field('hash');
      $client_name = get_field('client_name');

      $url = sanitize_title($client_name.'-'.$hash.'-home');

      //echo $url;

      $home = get_page_by_path( $url, OBJECT, 'sites' );

      if($home->ID != get_the_ID()) :

      ?>


        <div class="text-center">

          <?php
            $attachment_id = get_field('whitelabel_logo');
            $size = "medium";
            $image = wp_get_attachment_image_src( $attachment_id, $size );
            echo '<img src="' . $image[0] . '" border="0" alt="'.get_the_title().'" />';
          ?>

        </div>


      <?php endif; endif; ?>


        <div class="box-wr box-info text-left bg-theme-color">
          <div class="container container-lg wow fadeIn" data-wow-delay="0.2s">
            <div class="row">
              <div class="col-sm-4">
                <div class="text-wr">
                  <h2 class="wow fadeInUp" data-wow-delay="0.4s"><?php the_field('custom_page_title'); ?></h2>
                </div>
              </div>
              <div class="col-sm-8 suite-description">
                <div class="text-wr">
                  <p><?php echo $post->post_content ?></p>
                </div>
              </div>
            </div>
          </div><!-- END of .container -->
        </div><!-- END of .box-wr -->

        <div class="box-wr box-grid tabs-wr text-left bg-transparent">
          <div class="container container-lg wow fadeIn" data-wow-delay="0.2s">
            <div class="row isotope-grid-wr">
              <div class="col-md-4">
                <div class="grid-sidebar-left fixed-left">
                  <a href="javascript:void(0);" class="btn-toggle-sidebar visible-xs visible-sm">+</a>
                  <div class="grid-sidebar-left-inner" <?php if(count(get_field('videos')) < 4) { echo 'style="margin-top: 20px;"';}?> >
                    <!-- <div class="box-info bg-theme-color">
                      <h2 class="wow fadeInUp" data-wow-delay="0.4s">Recruiting The Right People</h2>
                    </div> -->
                    <h4 class="hidden-xs hidden-sm">Choose from the following videos:</h4>
                    <p class="hidden-xs hidden-sm">Click a video to play:</p>
                    <?php 
                    $clients_vid_category = array();
                    if( have_rows('videos') ): //check for ACF 
                      $video_items = ''; // for details of right panel
                        
                      while( have_rows('videos') ): the_row(); 

                        if(!in_array(get_sub_field('category'),$clients_vid_category)){ // separate categories

                          $clients_vid_category[get_sub_field('category')][] = array(
                            'title' =>  get_sub_field('title'),
                            'video_id' =>get_sub_field('video_id'),
                            'vimeo_url' =>  get_sub_field('vimeo_url'),
                            'short_description' =>  get_sub_field('short_description'),
                            'display' => get_sub_field('display'),
                            'placeholder' => get_sub_field('placeholder_image'),
                            'policy_link' => get_sub_field('policy_link'),
                          ); // set data for each category
                        }

                      endwhile; 

                    endif; ?>

                    <div class="overflow-scroll">
                    <?php foreach ($clients_vid_category as $category => $value) { //loop thorugh categories?>
                    
                      <div class="video-thumb-items-group">
                        <?php if($category) : ?>
                        <div class="video-thumb-items-group-header">
                          <h4><?php echo $category; // category name ?>:</h4>
                          <a href="javascript:void(0);" class="btn-read-more" data-filter=".<?php echo sanitize_title(preg_replace('/\W+/', '_', $category)); // isotop filter for groups?>">
                            <strong>FIND OUT MORE</strong>
                            <i class="fa fa-caret-right"></i>
                          </a>
                        </div>
                        <?php endif; ?>
                        <div class="video-thumb-items-wr">
                        <?php foreach ($clients_vid_category[$category]  as  $data) {   $video_item_locked='';?>

                          <?php if(empty($data['display']) || !in_array('menu',$data['display']) && !in_array('hidden',$data['display']) ) : ?>
                          
                          <div class="video-thumb-item">
                            <div class="video-nav-item">
                            <a href="javascript:void(0)" data-filter=".<?php echo hyphenize($data['video_id']); // unique video id?>">
                              <?php  if(!empty($data['display'])){ ?>
                                <?php if(in_array('locked',$data['display'])){ 
                                $video_item_locked='video-item-locked';
                                ?>
                                <span class="video-status-label bg-theme-color">
                                  <i class="fa fa-lock"></i>
                                  <span>LOCKED</span>
                                </span>
                              <?php } 
                              }else { ?>
                                <span class="video-status-label bg-theme-color">
                                  <i class="fa fa-play-circle"></i>
                                  <span>PLAY</span>
                                </span>
                              <?php } // check for locked videos?>
                              
                              <h5><?php echo $data['title']; //  card title?></h5>
                              <p><?php echo mb_strimwidth(strip_tags($data['short_description']), 0, 100, "..."); // Short description to 100 characters?></p>
                            </a>

                            <?php 

                            $link = $data['policy_link'];

                            if( $link ): 
                              $link_url = $link['url'];
                              $link_title = $link['title'];
                              $link_target = $link['target'] ? $link['target'] : '_self';
                              ?>
                              <a href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
                                <span class="btn-small bg-theme-color">
                                  <span><?php echo esc_html($link_title); ?></span>
                                  <i class="fa fa-caret-right"></i>
                                </span>
                              </a>
                            <?php endif; ?>
                            </div>
                          </div>

                          <?php endif; ?>

                          <?php if(empty($data['display']) || (!in_array('hidden',$data['display']) && !in_array('main',$data['display'])) ) : ?>

                          <?php ob_start();?>
                          <div class="isotope-grid-item <?php echo hyphenize($data['video_id']); //video id reference ?> <?php echo sanitize_title(preg_replace('/\W+/', '_', $category)); // isotop group reference ?> " id="<?php echo hyphenize($data['video_id']); // unique video id?>">
                            <div class="video-item embed-responsive <?php echo $video_item_locked; ?> embed-responsive-16by9">
                            <?php if($data['placeholder']) :?>
                              <?php $image = wp_get_attachment_image_src( $data['placeholder'], "800x450" );
                              echo '<img src="' . $image[0] . '" border="0" />'; ?>
                            <?php  elseif(strpos($data['vimeo_url'], 'soundcloud')) : // sadly, we very very rarely have to use soundcloud, so instead of building something completely new, Im just fudging what's already in place. Sorry, future me. ?>
                              <?php echo $data['vimeo_url'];?>
                            <?php  else : ?>
                              <iframe src="https://player.vimeo.com/video/<?php echo getVimeoVideoIdFromUrl($data['vimeo_url']);// extracted vimeo id from url ?>?color=642f6c&title=0&byline=0&portrait=0" allow="autoplay; fullscreen" allowfullscreen></iframe>
                            <?php endif; ?>
                            </div>
                            <h2><?php echo $data['title']; // video title?></h2>
                            <h5><?php echo $data['short_description']; // entire description?></h5>
                            <?php if(get_field('whitelabel')) : ?>


                              <a href="javascript:window.open('','_self').close();" class="btn-main bg-theme-color">close browser window to return</a>


                            <?php endif;?>
                          </div>
                          <?php $video_items .= ob_get_clean(); // stored in the variable ?>

                          <?php endif; ?>

                        <?php }; ?>
                          
                        </div>
                      </div>
                      <?php }

                      ?>
                      <?php the_field('menu_footer_text');?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-8">
                <div class="grid-main-area">
                  <div class="tabs-items-wr isotope-grid">
                  <?php echo $video_items; // show all right panel data ?>
                  </div>
                  <div class="btns-wr">

                    <?php

                    if(!get_field('whitelabel')) :

                    if($home->ID) :

                    ?>

                    <a href="<?php echo get_permalink($home->ID);?>" class="btn-main bg-theme-color">View other suites</a>

                    <?php endif; ?>

                    <!--a href="<?php echo get_permalink(419);?>" class="btn-main bg-theme-color">Contact us</a-->

                    <?php endif; ?>
                    
                  </div>
                </div>
              </div>
            </div>
          </div><!-- END of .container -->
        </div><!-- END of .box-wr -->

        
      <?php endif; ?>

    <?php /* end password protection */ endif; ?>

    </div><!-- END of .content -->

<?php get_footer(); ?>
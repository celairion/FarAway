<?php



function projecttheme_front_page_slider_function_new_2023($hero_image, $main_tagline, $sub_tagline, $button1_caption, $button1_link, $button2_caption, $button2_link, $top_padding, $bottom_padding, $show_searchbar, $search_bar_background, $show_tint, $gradient1, $gradient2)
{


 


		$gradient1 = str_replace("#", "", $gradient1); $gradient1 = "#".$gradient1;
		$gradient2 = str_replace("#", "", $gradient2); $gradient2 = "#".$gradient2;

		list($r1, $g1, $b1) = sscanf($gradient1, "#%02x%02x%02x");
		list($r2, $g2, $b2) = sscanf($gradient2, "#%02x%02x%02x");

		if(empty($gradient1))
		{
			$r1 = '103';
			$g1 = '92';
			$b1 = '255';
		}

		if(empty($gradient2))
		{
			$r2 = '103';
			$g2 = '92';
			$b2 = '255';
		}



    ?>


<style>
          .sm-gdns
          {
            padding-top: <?php echo $top_padding ?>px;
            padding-bottom: <?php echo $bottom_padding ?>px;

          }

          .header_search
          {
            background: #<?php echo str_replace("#", "", $search_bar_background); ?>;
						background: rgb(255 255 255 / 28%)
          }

					<?php if($show_tint == "yes")
					{ ?>

					.header_content::before
					{
						background: -webkit-linear-gradient(left, rgba(<?php echo $r1 ?>, <?php echo $g1 ?>, <?php echo $b1 ?>, 0.9) 40%, rgba(<?php echo $r1 ?>, <?php echo $g1 ?>, <?php echo $b1 ?>, 0.3) 100%);
						background: linear-gradient(to right, rgba(<?php echo $r2 ?>, <?php echo $g2 ?>, <?php echo $b2 ?>, 0.9) 40%, rgba(<?php echo $r2 ?>, <?php echo $g2 ?>, <?php echo $b2 ?>, 0.3) 100%)
					}

					<?php } else { ?>

						.header_content::before
						{
							background: none;
							background: none
						}

					<?php } ?>

					.header_search
					{
						padding: 2px 5px 15px
					}

     </style>



<div class="header_content bg_cover sm-gdns" style="background-image: url(<?php echo $hero_image ?>); background-position: center top">
               <div class="container ">
                   <div class="row">
                       <div class="col-lg-8">
                           <div class="content_wrapper">
                               <h3 class="title"><?php echo $main_tagline ?></h3>
                            <?php if(!empty($sub_tagline)) { ?>   <p><?php echo $sub_tagline ?></p> <?php } ?>

                            <?php

                                    if(!empty($button1_caption) or !empty($button2_caption) )
                                    {

                             ?>
                               <ul class="header_btn">
                                <?php if(!empty($button1_caption)) { ?>   <li><a class="main-btn" href="<?php echo $button1_link ?>"  ><?php echo $button1_caption ?></a></li> <?php } ?>
                                <?php if(!empty($button2_caption)) { ?>   <li><a class="main-btn" href="<?php echo $button2_link ?>"  ><?php echo $button2_caption ?></a></li> <?php } ?>
                               </ul>

                             <?php } ?>
                           </div>
                       </div>
                   </div>
                   <?php if($show_searchbar == "yes") { ?>
                   <div class="header_search">
                       <form method="get" action="<?php echo get_permalink(get_option('ProjectTheme_advanced_search_page_id')) ?>">
                           <div class="search_wrapper d-flex flex-wrap">
                               <div class="search_column d-flex flex-wrap">

                                   <div class="search_select mt-15">

                                    <input type="text" class="form-control" name="keyword" placeholder="<?php _e('Type Your Keyword','ProjectTheme') ?>">
                                   </div>
                               </div>
                               <div class="search_column d-flex flex-wrap">
                                   <div class="search_form mt-15">
                                    <select class="form-control" name="project_cat_cat"><option value=""><?php _e('Select Category','ProjectTheme') ?></option>
                                             <?php

																						 $args = "orderby=name&order=ASC&hide_empty=0&parent=0";
																						 $terms = get_terms( 'project_cat', $args );


																						 foreach ( $terms as $term )
																						 {
																							 		echo '<option value="'.$term->slug.'">'.$term->name.'</option>';
																						 }

																						  ?>
                                         </select>
                                   </div>
                                   <div class="search_btn mt-15">
                                       <button class="main-btn"><?php _e('Search','ProjectTheme') ?></button>
                                   </div>
                               </div>
                           </div>
                       </form>

                   </div><?php } ?>
               </div>
           </div>




    <?php
}


function projecttheme_front_page_slider_function()
{

 
$ProjectTheme_align_elements_of_slider = get_option('ProjectTheme_align_elements_of_slider');
    if($ProjectTheme_align_elements_of_slider != "center")
    {
      echo '<style>';
    
    
      echo '
      .main_tagLine, .sub_tagLine,  .buttons_box_main
      {
          text-align: '. $ProjectTheme_align_elements_of_slider.';
      }
    
    
    
      @media screen and (max-width: 500px) {
    
          .main_tagLine, .sub_tagLine,  .buttons_box_main { text-align: center }
    
    
      .main_tagLine
      {
        letter-spacing: 0
      } }
    
      </style>';
    }
 
    
    if($ProjectTheme_align_elements_of_slider == "center")
    {
      echo '<style>.wrps
      {
        margin:auto;
      } </style>';
    }

    

$ProjectTheme_main_blur_margin_top = get_option('ProjectTheme_main_blur_margin_top');
if(empty($ProjectTheme_main_blur_margin_top)) $ProjectTheme_main_blur_margin_top = 100;


if(!empty($ProjectTheme_main_blur_margin_top))
{
  echo '<style> #container-home-blur { margin-top:'.$ProjectTheme_main_blur_margin_top.'px; }</style>';
}



?>


<div class="home_blur">
        <div class="container" id="container-home-blur">  <div class="row">
       		<div class="main_tagLine col-12"> <?php echo get_option('ProjectTheme_main_tagline') ?></div>
            <div class="sub_tagLine col-12"><div class="wrps"><?php echo get_option('ProjectTheme_sec_tagline') ?></div></div>

            <!--
            <form method="get" action="<?php echo get_permalink(get_option('ProjectTheme_advanced_search_page_id')) ?>">
            <div class="search_box_main">
            	<div class="search_box_main2">
                    <div class="rama1"><input type="text" placeholder="<?php _e('What service do you need? (e.g. website design)','ProjectTheme'); ?>" id="findService" name="term"></div>
                    <div class="rama1 rama2"><input type="image" src="<?php echo get_template_directory_uri(); ?>/images/sear1.png" width="44" height="44" /></div>
                </div>
            </div>
            </form> -->

            <div class="buttons_box_main col-12">
            	<ul class="regular_ul">
                <?php

                    $capt1 = strip_tags(get_option('ProjectTheme_button1_caption'));
                    $capt2 = strip_tags(get_option('ProjectTheme_button2_caption'));

                 ?>
                	<li><a class="btn btn-primary btn-lg btn-block" href="<?php echo get_option('ProjectTheme_button1_link') ?>"><?php echo stripslashes($capt1) ?></a></li>
                	<li><a class="btn btn-primary btn-lg btn-block" href="<?php echo get_option('ProjectTheme_button2_link') ?>"><?php echo stripslashes($capt2) ?></a></li>
                </ul>

            </div>


<?php

      $ProjectTheme_enable_searchbar = get_option('ProjectTheme_enable_searchbar');
      if($ProjectTheme_enable_searchbar == 1)
      {
 ?>
<div class='col-12'><div id='search-box'>
<form action='<?php echo site_url(); ?>'  method='get' target='_top'><input type="hidden" value='<?php echo get_option('ProjectTheme_advanced_search_page_id') ?>' name="page_id" />

  <div class="input-group mb-3">
    <input type="text" class="form-control" id="search-box-m1" placeholder='<?php _e('Start search...','ProjectTheme') ?>'  name="term" />
    <div class="input-group-append">
      <button class="btn btn-primary" id="search-button-m1" type="submit"><?php _e('Search Now','ProjectTheme') ?></button>
    </div>
  </div>





</form>
</div></div> <?php } ?>



</div>

        </div>
       	</div>



<?php



}



?>
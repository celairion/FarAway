<?php
 

//pt_template_page_1

$pt_template_page_1 = get_option('ProjectTheme_template_header_bg1');
$header_bg_solid = get_option('ProjectTheme_template_header_bg_solid1');

$color1 = get_option('ProjectTheme_template_header_color_1');
$size1 = get_option('ProjectTheme_template_header_size_1');
$height = get_option('ProjectTheme_template_header_height_1');
$margin_top = get_option('ProjectTheme_template_title_margintop_1'); if(empty($margin_top)) $margin_top = 33;
$ProjectTheme_color_for_bk = get_option('ProjectTheme_color_for_bk');
$ProjectThemePrimaryColor = get_option('ProjectThemePrimaryColor');
$ProjectThemePrimaryColor2 = get_option('ProjectThemePrimaryColor2');


$ProjectTheme_color_main_tag = get_option('ProjectTheme_color_main_tag');
$ProjectTheme_color_second_tag = get_option('ProjectTheme_color_second_tag');


//fonts


$projecttheme_font = get_option('projecttheme_font');
if(empty($projecttheme_font))
{
    $font = "Source Sans Pro";
}
else {
  // code...
  $font = $projecttheme_font;
}

?>

    body
    {
        font-family: "<?php echo $font ?>";
    }

<?php


//-----------


if(!empty($ProjectTheme_color_second_tag))
{
    ?>

    .sub_tagLine
   {
        color: #<?php echo $ProjectTheme_color_second_tag ?>

   }

    <?php

}

if(!empty($ProjectTheme_color_main_tag))
{

?>

.main_tagLine
{
      color: #<?php echo $ProjectTheme_color_main_tag ?>
}

<?php } ?>

@media only screen and (min-width: 768px) {

  .main_tagLine
  {

    <?php

          $ProjectTheme_text_size_main_tag = get_option('ProjectTheme_text_size_main_tag');
          if(!empty($ProjectTheme_text_size_main_tag))
          {
            ?>
                  font-size: <?php echo $ProjectTheme_text_size_main_tag ?>px;
            <?php
          }

    ?>

  }


   .sub_tagLine
  {

    <?php

          $ProjectTheme_text_size_second_tag = get_option('ProjectTheme_text_size_second_tag');
          if(!empty($ProjectTheme_text_size_second_tag))
          {
            ?>
                  font-size: <?php echo $ProjectTheme_text_size_second_tag ?>px;
            <?php
          }

    ?>

  }

}

<?php

if(!empty($ProjectThemePrimaryColor)) {

  ?>




      .h3-name { color: #<?php echo $ProjectThemePrimaryColor ?>; }
      .h3-name:after { border-color: #<?php echo $ProjectThemePrimaryColor ?>; }

      h3.category-heading
      {
        background: #<?php echo $ProjectThemePrimaryColor ?>;
      }

      .navbar_btn ul li .sign-up, .main-btn
      {
        background: #<?php echo $ProjectThemePrimaryColor ?>;
      }
      .main-btn:hover
      {
          background-color: #<?php echo $ProjectThemePrimaryColor2 ?>;
      }

          .btn-primary,  .btn-info {

                background-color: #<?php echo $ProjectThemePrimaryColor ?>;
                border-color: #<?php echo $ProjectThemePrimaryColor2 ?>;
            }

              .btn-primary:hover, .btn-info:hover
              {
                  background-color: #<?php echo $ProjectThemePrimaryColor2 ?>;
                  border-color: #<?php echo $ProjectThemePrimaryColor  ?>;
              }

              .btn-primary:not(:disabled):not(.disabled).active, .btn-primary:not(:disabled):not(.disabled):active, .show>.btn-primary.dropdown-toggle
              {
                background-color: #<?php echo $ProjectThemePrimaryColor ?>;
                border-color: #<?php echo $ProjectThemePrimaryColor2 ?>;
              }

              .dropdown-item.active, .dropdown-item:active
              {
                  background-color: #<?php echo $ProjectThemePrimaryColor ?>;
              }


              .sidebar-nav #sidebarnav .sidebar-item.selected>.sidebar-link
              {
                background: #<?php echo $ProjectThemePrimaryColor ?>;
              }

              #user-tabs .nav-tabs .nav-item.show .nav-link, #user-tabs .nav-tabs .nav-link.active {
                background-color: #<?php echo $ProjectThemePrimaryColor ?>;
              }

              .btn-outline-primary {
                    color: #<?php echo $ProjectThemePrimaryColor ?>;
                    border-color: #<?php echo $ProjectThemePrimaryColor ?>;
                }

                .btn-outline-primary:hover {

                      background-color: #<?php echo $ProjectThemePrimaryColor2 ?>;
                      border-color: #<?php echo $ProjectThemePrimaryColor2 ?>;
                  }

                  a {
                        color:  #<?php echo $ProjectThemePrimaryColor  ?>;
                    }

          .page-item.active .page-link {

            background-color: #<?php echo $ProjectThemePrimaryColor  ?>;
            border-color: #<?php echo $ProjectThemePrimaryColor  ?>;
          }


  <?php

 }


//------------

if(empty($color1)) { $color1 = "color: #fff;"; } else  { $color1 = "color: #" . $color1. " !important;"; }
if(empty($size1)) $size1 = "40px"; else $size1 = $size1."px";
if(empty($height)) $height = "min-height:150px";
else $height = "min-height: " . $height . "px";

if(!empty($margin_top)) { $margin_top = "padding-top:" . $margin_top . "px; padding-bottom:" . $margin_top . "px;";


}

if(!empty($ProjectTheme_color_for_bk)) $ProjectTheme_color_for_bk = "body { background: #".$ProjectTheme_color_for_bk." !important }";
echo $ProjectTheme_color_for_bk;


$ProjectTheme_color_for_post_box            = get_option('ProjectTheme_color_for_post_box');
$ProjectTheme_template_header_pgttl_align_1 = get_option('ProjectTheme_template_header_pgttl_align_1');
if(empty($ProjectTheme_template_header_pgttl_align_1)) $ProjectTheme_template_header_pgttl_align_1 = "left";
 ?>

.post-account-box, .my_box3, .account-sidebar-component
{
    <?php if(!empty($ProjectTheme_color_for_post_box)) { echo 'background:#' . $ProjectTheme_color_for_post_box; } ?>
}

 .pt_template_page_1
 {
   <?php if(!empty($pt_template_page_1)) { ?> background: <?php echo !empty( $pt_template_page_1) ? "url('" . $pt_template_page_1 . "')" : "" ?> #f2f2f2 !important; <?php } ?>
  <?php if(!empty($header_bg_solid)) { ?> background:    #<?php echo $header_bg_solid ?> !important; <?php } ?>
    background-repeat: no-repeat;
  background-size: 100% !important;
 }


 .pt_template_page_1 .mm_inn h1
{
  <?php echo $color1 ?>
  font-size:  <?php echo $size1; ?> ;
  font-weight:500;

  <?php if($ProjectTheme_template_header_pgttl_align_1 != "left") echo "text-align: " . $ProjectTheme_template_header_pgttl_align_1; ?>
}

#x_templ_1_pt .mm_inn
{
    color: #<?php echo get_option('ProjectTheme_template_header_text_color') ?> !important
}


.my_box3_breadcrumb
{
    <?php if($ProjectTheme_template_header_pgttl_align_1 != "left") echo "text-align: " . $ProjectTheme_template_header_pgttl_align_1; ?>
}


.page_heading_me_inner
{
  width:100%;
  <?php echo $margin_top ?>
}

body #pt_template_page_1 .breadcrumb-wrap a:link, body #pt_template_page_1 .breadcrumb-wrap a:visited, body #breadcrumb-wrap  .mm_inn a:link , body #breadcrumb-wrap  .mm_inn a:visited
{
  <?php echo $color1; ?>
}

body .my_box3_breadcrumb .post
{
  background: none !important
}

#pt_template_page_1 .breadcrumb-wrap
{
    <?php echo $color1; ?>
}

#pt_template_page_1
{
  <?php //echo $height

  ?>
}

<?php

    $PT_footer_image_or_color = get_option('PT_footer_image_or_color');
    $PT_footer_image_or_color2 = get_option('PT_footer_image_or_color2');
    if(!empty($PT_footer_image_or_color))
    {
       ?>

          #footer
          {
            background-image: url(<?php echo $PT_footer_image_or_color ?> )
          }

       <?php
    }
    elseif(!empty($PT_footer_image_or_color2))
    {
      ?>

         #footer
         {
           background-image: none;
           background:#<?php echo $PT_footer_image_or_color2 ?>
         }

      <?php
    }


 ?>

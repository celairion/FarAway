<?php

class project_listng_fees
{
  private $pid;

  public function __construct($pid)
  {
      $this->pid = $pid;
  }


  public function calculate_listing_fees()
  {
    $pid = $this->pid;

  	//-------

  	$featured	 = get_post_meta($pid, 'featured', true);
  	$feat_charge = get_option('projectTheme_featured_fee');

  	if($featured != "1" ) $feat_charge = 0;




  	$custom_set = get_option('projectTheme_enable_custom_posting');
  	if($custom_set == 'yes')
  	{
  		$posting_fee = get_option('projectTheme_theme_custom_cat_'.$catid);
  		if(empty($posting_fee)) $posting_fee = 0;
  	}
  	else
  	{
  		$posting_fee = get_option('projectTheme_base_fee');
  	}

  	$projectTheme_hide_project_fee = empty($projectTheme_hide_project_fee) ? 0 : $projectTheme_hide_project_fee;
  	$posting_fee = empty($posting_fee) ? 0 : $posting_fee;
  	$feat_charge = empty($feat_charge) ? 0 : $feat_charge;
  	$ProjectTheme_get_images_cost_extra = empty($ProjectTheme_get_images_cost_extra) ? 0 : $ProjectTheme_get_images_cost_extra;

  	$total = $feat_charge + $posting_fee  + $projectTheme_hide_project_fee + $ProjectTheme_get_images_cost_extra;

  	//-----------------------------------------------

  		$payment_arr = array();

  		$base_fee_paid 	= get_post_meta($pid, 'base_fee_paid', true);

  		if($base_fee_paid != "1" and $posting_fee > 0)
  		{
  			$my_small_arr = array();
  			$my_small_arr['fee_code'] 		= 'base_fee';
  			$my_small_arr['show_me'] 		= true;
  			$my_small_arr['amount'] 		= $posting_fee;
  			$my_small_arr['description'] 	= __('Base Fee','ProjectTheme');
  			array_push($payment_arr, $my_small_arr);
  		}
  		//-----------------------


  		$my_small_arr = array();
  		$my_small_arr['fee_code'] 		= 'extra_img';
  		$my_small_arr['show_me'] 		= true;
  		$my_small_arr['amount'] 		= $ProjectTheme_get_images_cost_extra;
  		$my_small_arr['description'] 	= __('Extra Images Fee','ProjectTheme');
  		array_push($payment_arr, $my_small_arr);
  		//------------------------

  		$featured_paid  	= get_post_meta($pid,'featured_paid',true);
  		$opt 				= get_post_meta($pid,'featured',true);


  		if($feat_charge > 0 and $featured_paid != 1 and $opt == 1)
  		{
  			$my_small_arr = array();
  			$my_small_arr['fee_code'] 		= 'feat_fee';
  			$my_small_arr['show_me'] 		= true;
  			$my_small_arr['amount'] 		= $feat_charge;
  			$my_small_arr['description'] 	= __('Featured Fee','ProjectTheme');
  			array_push($payment_arr, $my_small_arr);
  			//------------------------
  		}

  		$payment_arr 	= apply_filters('ProjectTheme_filter_payment_array', $payment_arr, $pid);
  		$new_total 		= 0;

  		foreach($payment_arr as $payment_item):
  			if($payment_item['amount'] > 0):
  				$new_total += $payment_item['amount'];
  			endif;
  		endforeach;

  	//-----------------------------------------------




  	$total = apply_filters('ProjectTheme_filter_payment_total', $new_total, $pid);
    return $total;
  }

}
 ?>

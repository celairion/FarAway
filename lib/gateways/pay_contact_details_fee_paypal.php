<?php
session_start();
get_template_part('lib/gateways/paypal.class');

global $wp_query;

$action = $_GET['action'];


$p = new paypal_class;             // initiate an instance of the class
$bus = trim(get_option('projectTheme_payPal_email'));
if(empty($bus)) die('ERROR. Please Admin, add your paypal address in backend.');

$p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';   // testing paypal url

	$sandbox = get_option('ProjectTheme_paypal_enable_sdbx');

	if($sandbox == "yes")
	$p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';     // paypal url


global $wpdb;
$this_script = home_url().'/?p_action=paypal_deposit_pay';

if(empty($action)) $action = 'process';

switch ($action) {



   case 'process':      // Process and order...
   $total = trim($_POST['amount']);


	  $current_user = wp_get_current_user();
	  $uid = $current_user->ID;

		$total = get_option('project_theme_contact_details_fee');
    $pid = $_GET['pid'];

    $cc = get_option('projectTheme_currency');
		$cc = apply_filters('pt_paypal_deposit_currency', $cc);

//---------------------------------------------

      //$p->add_field('business', 'sitemile@sitemile.com');
      $p->add_field('business', $bus);
	  $p->add_field('currency_code', $cc);
	  $p->add_field('return', $this_script.'&action=success');
	  $p->add_field('bn', 'SiteMile_SP');
      $p->add_field('cancel_return', $this_script.'&action=cancel');
      $p->add_field('notify_url', $this_script.'&action=ipn');
      $p->add_field('item_name', __('Payment for Reveal Contact Details',"ProjectTheme"));
	  $p->add_field('custom', $uid.'|'.current_time('timestamp',0).'|'.$pid."|reveal_contact" );
      $p->add_field('amount', ProjectTheme_formats_special($total,2));

      $p->submit_paypal_post(); // submit the fields to paypal

      break;

   case 'success':      // Order was successful...
	case 'ipn':



	if(isset($_POST['custom']))
	{


	$cust 						= $_POST['custom'];
	$cust 						= explode("|",$cust);
	$uid							= $cust[0];
	$datemade 				= $cust[1];
	$pid 							= $cust[2];
	$dep 							= $cust[3];

	$op = get_option('ProjectTheme_reveal_'.$uid.$datemade);


		if($op != "1" and $dep == "reveal_contact")
		{

			update_option('ProjectTheme_reveal_'.$uid.$datemade, "1");
		   update_post_meta($pid,'reveal_details_' . $uid, "paid");


		   do_action('projecttheme_on_paying_contact_details', $uid, $mc_gross);

			//-------------------------------

		}

	}

	$sss = $_SESSION['redir1'];
	if(!empty($sss))
	{
		$_SESSION['redir1'] = '';
		wp_redirect($sss);
	}
	else
	wp_redirect(get_permalink(get_option('ProjectTheme_my_account_payments_id')));

   break;

   case 'cancel':       // Order was canceled...

	wp_redirect(ProjectTheme_get_payments_page_url('deposit'));

       break;




 }

?>

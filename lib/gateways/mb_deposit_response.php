<?php

if($_POST['status'] > -1)
{

		$c  	= $_POST['field1'];
		$c 		= explode('|',$c);

		$uid				= $c[0];
		$datemade 			= $c[1];

		//---------------------------------------------------

		$amount = $_POST['amount'];

		$op = get_option('ProjectTheme_deposit_'.$uid.$datemade);


		if($op != "1")
		{
			$mc_gross = $amount;

			$cr = projectTheme_get_credits($uid);
			projectTheme_update_credits($uid,$mc_gross + $cr);

			update_option('ProjectTheme_deposit_'.$uid.$datemade, "1");
			$reason = __("Deposit through Moneybookers.","ProjectTheme");
			projectTheme_add_history_log('1', $reason, $mc_gross, $uid);

			$user = get_userdata($uid);

			$finds = array('##amount##','##method_deposit##');
			$replacements = array($mc_gross,'Skrill');
			projecttheme_send_email_with_tags_new('deposit_money_wallet', $finds, $replacements, $uid);

			// for affiliate

			do_action('projecttheme_on_deposit_am', $uid, $mc_gross);

			//-------------------------------

		}


		//---------------------------
}

?>

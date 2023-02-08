<?php


class project_orders
{
    private $oid;
    private $escrow_obj = false;
    private $payment_obj = false;
    private $order_obj = false;

    public function __construct($oidn = '')
    {
        $this->oid = $oidn;
    }

    public function get_order()
    {
      global $wpdb;
      $s = "select * from ".$wpdb->prefix."project_orders where id='{$this->oid}'";
      $r = $wpdb->get_results($s);

      if(count($r) > 0) { $this->order_obj = $r[0]; return $r[0]; }

      return false;

    }

    public function get_orderid_of_project($pid)
    {
      global $wpdb;
      $s = "select * from ".$wpdb->prefix."project_orders where pid='{$pid}'";
      $r = $wpdb->get_results($s);

      if(count($r) > 0) return $r[0]->id;

    }



    public function is_order_freelancer_delivered()
    {
        $rw = $this->get_order();
        if($rw->done_freelancer == 1) return true;
        return false;
    }


    public function is_order_buyer_completed()
    {
        $rw = $this->get_order();
        if($rw->done_buyer == "1") return true;
        return false;
    }

    public function mark_buyer_completed()
    {
        global $wpdb;

        $wpdb->show_errors = true;

        $tm = current_time('timestamp');
        $s = "update ".$wpdb->prefix."project_orders set done_buyer='1', order_status='2', marked_done_buyer='$tm' where id='{$this->oid}'";
        $wpdb->query($s);

    }


    public function mark_buyer_completed_and_delivered()
    {
        global $wpdb;

        $wpdb->show_errors = true;

        $tm = current_time('timestamp');
        $s = "update ".$wpdb->prefix."project_orders set done_buyer='1', order_status='2', marked_done_buyer='$tm' where id='{$this->oid}'";
        $wpdb->query($s);

        $tm = current_time('timestamp');
        $s = "update ".$wpdb->prefix."project_orders set done_freelancer='1', order_status='1', marked_done_freelancer='$tm' where id='{$this->oid}'";
        $wpdb->query($s);

    }


    public function mark_freelancer_completed()
    {
        global $wpdb;

        $obj = $this->get_order();
        if($obj != false)
        {
          $uid = get_current_user_id();
          $pid = $obj->pid;

          if($obj->done_freelancer == 0 and $obj->freelancer == $uid)
          {

              $tm = current_time('timestamp');
              $s = "update ".$wpdb->prefix."project_orders set done_freelancer='1', order_status='1', marked_done_freelancer='$tm' where id='{$this->oid}'";
              $wpdb->query($s);

              ProjectTheme_send_email_on_delivered_project_to_bidder($pid, $uid);
        			ProjectTheme_send_email_on_delivered_project_to_owner($pid);
          }

        }

    }

    public function insert_escrow($args = array())
    {
      global $wpdb;
      if(count($args) == 0) return;

      $method       = $args['method'];
      $fromid       = $args['sending_user'];
      $toid         = $args['receiving_user'];
      $amount       = $args['amount'];
      $datemade     = current_time('timestamp');
      $releasedate  = 0;


      $s = "insert into ".$wpdb->prefix."project_escrows (fromid, toid, oid, amount, datemade, releasedate, method) values('$fromid','$toid','{$this->oid}','$amount', '$datemade', '$releasedate', '$method')";
      $wpdb->query($s);


      do_action('projettheme_on_putting_money_into_escrow', $fromid,  $toid, $this->oid, $amount);


    }

    public function release_escrow()
    {

      global $wpdb; $tm = current_time('timestamp');
      $s = "update ".$wpdb->prefix."project_escrows set releasedate='$tm', released='1' where oid={$this->oid}";
      $wpdb->query($s);

      $this->get_escrow_object_active();
      do_action('projettheme_on_releasing_escrow', $this->escrow_obj->fromid,  $this->escrow_obj->toid, $this->escrow_obj->id, $this->escrow_obj->amount, $this->oid);

    }

    public function is_escrow_released()
    {
        global $wpdb;

        $s = "select * from ".$wpdb->prefix."project_escrows where oid='{$this->oid}'";
        $r = $wpdb->get_results($s);


        if(count($r) == 0) return true;

        $row = $r[0];

        if($row->released == 1) return true;
        return false;

    }

    public function get_escrow_object()
    {
          if($this->escrow_obj == false) return NULL;
          return  $this->escrow_obj;
    }

    public function get_escrow_object_active()
    {
          $this->has_escrow_deposited();
          return $this->escrow_obj;
    }

    public function get_marketplace_payment_object()
    {
          if($this->escrow_obj == false) return NULL;

                    return  $this->escrow_obj;
    }

    public function has_escrow_deposited()
    {
        global $wpdb;
        $s = "select * from ".$wpdb->prefix."project_escrows where oid='{$this->oid}'";
        $r = $wpdb->get_results($s);

        $cnt = count($r);
        if($cnt > 0) { $this->escrow_obj = $r[0]; return true; }
        return false;
    }

    //**************************************************************************
    //
    //  function
    //
    //**************************************************************************

    public function has_marketplace_payment_been_deposited()
    {
        global $wpdb;
        $s = "select * from ".$wpdb->prefix."project_marketplace_payments where oid='{$this->oid}'";
        $r = $wpdb->get_results($s);

        $cnt = count($r);
        if($cnt > 0) { $this->$payment_obj = $r[0]; return true; }
        return false;
    }

    //**************************************************************************
    //
    //  function
    //
    //**************************************************************************

    public function insert_marketplace_payment($fromid, $toid, $amount, $comm, $method)
    {
        global $wpdb;
        $tm = current_time('timestamp');

        $s = "insert into ".$wpdb->prefix."project_marketplace_payments (fromid, toid, amount, method, datemade, oid) values('$fromid','$toid','$amount', '$method', '$tm', '{$this->oid}')";
        $r = $wpdb->get_results($s);

        $wpdb->query($s);

        $this->insert_marketplace_payment_commission($comm);
        $this->insert_marketplace_payment_freelancer($amount - $comm);

    }


   private function insert_marketplace_payment_commission($amount)
    {
        global $wpdb;
        $tm = current_time('timestamp');

        $s = "insert into ".$wpdb->prefix."project_marketplace_payments_commissions (amount, datemade, oid) values( '$amount',  '$tm', '{$this->oid}')";
        $r = $wpdb->get_results($s);

        $wpdb->query($s);

    }

   private function insert_marketplace_payment_freelancer($amount)
    {
        global $wpdb;
        $tm = current_time('timestamp');
        $uid = $this->order_obj->freelancer;

        $s = "insert into ".$wpdb->prefix."project_marketplace_payments_freelancers (amount, datemade, oid, uid) values( '$amount',  '$tm', '{$this->oid}', '$uid')";
        $r = $wpdb->get_results($s);

        $wpdb->query($s);

    }
    //**************************************************************************
    //
    //  this function is adding a new order when the winner freelancer is chosen
    //
    //**************************************************************************

    public function insert_order($args = array())
    {
      if(count($args) == 0) return;
      global $wpdb;
      $tm = current_time('timestamp');

      // order status: 0 - open, 1 - freelancer delivered, 2 - buyer accepted(success), 3 - order cancelled

      $completion_date  = $args['completion_date'];
      $buyer            = $args['buyer'];
      $freelancer        = $args['freelancer'];
      $pid              = $args['pid'];
      $sts              = 0;

      $order_net_amount = $args['order_net_amount'];
      $order_total_amount = $args['order_total_amount'];

      $s1 = "insert into ".$wpdb->prefix."project_orders (completion_date, buyer, freelancer, pid, datemade, order_status, done_freelancer, marked_done_freelancer, done_buyer, marked_done_buyer, order_net_amount, order_total_amount)
      values('$completion_date','$buyer','$freelancer', '$pid', '$tm', '$sts' , '0', '0', '0', '0', '$order_net_amount', '$order_total_amount')";
      $wpdb->query($s1);
      $lastid = $wpdb->insert_id;


      do_action('projettheme_on_choosing_winner', $buyer,  $freelancer, $pid, $order_net_amount);
      $inv = get_option('ProjectTheme_payment_model');

      if($inv == "invoice_model_pay_outside")
      {
            //-------------
            $projectTheme_fee_after_paid = get_option('projectTheme_fee_after_paid');
            if(!empty($projectTheme_fee_after_paid))
            {
                $dm = time();
                $uid = get_current_user_id();
                $am = round($projectTheme_fee_after_paid * 0.01 * $order_net_amount, 2);

                global $wpdb;
                $s = "insert into ".$wpdb->prefix."project_bills_site (uid, pid, datemade, amount) values('$uid','$pid','$dm','$am')";
                $r = $wpdb->query($s);

            }

            //------------

            $projectTheme_fee_after_paid_bidder = get_option('projectTheme_fee_after_paid_bidder');
            if(!empty($projectTheme_fee_after_paid_bidder))
            {
                $dm = time();
                $am = round($projectTheme_fee_after_paid_bidder * 0.01 * $order_net_amount, 2);

                global $wpdb;
                $s = "insert into ".$wpdb->prefix."project_bills_site (uid, pid, datemade, amount) values('$freelancer','$pid','$dm','$am')";
                $r = $wpdb->query($s);

            }

      }

      return $lastid;

    }


    public function get_number_of_open_orders_for_buyer($uid)
    {
      global $wpdb;
      $s = "select count(id) sumx from ".$wpdb->prefix."project_orders orders where orders.buyer='$uid' and order_status='0'";
      $r = $wpdb->get_results($s);

      $row = $r[0];
      return $row->sumx;

    }

    public function get_number_of_delivered_orders_for_buyer($uid)
    {
      global $wpdb;
      $s = "select count(id) sumx from ".$wpdb->prefix."project_orders orders where orders.buyer='$uid' and order_status='1'";
      $r = $wpdb->get_results($s);

      $row = $r[0];
      return $row->sumx;

    }


    public function get_number_of_pending_projects_as_freelancer($uid)
    {
      global $wpdb;
      $s = "select count(id) sumx from ".$wpdb->prefix."project_orders orders where orders.freelancer='$uid' and order_status='0'";
      $r = $wpdb->get_results($s);

      $row = $r[0];
      return $row->sumx;

    }


}


 ?>

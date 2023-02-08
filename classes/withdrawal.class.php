<?php

class project_withdrawal
{
    private $id;

    public function __construct($id = '')
    {
        $this->id = $id;
    }

    public function get_withdrawal_request()
    {
      global $wpdb;
      $s = "select * from ".$wpdb->prefix."project_withdraw where id='{$this->id}'";
      $r = $wpdb->get_results($s);

      if(count($r) == 0)
      return false;

      return $r[0];
    }

    public function delete_withdrawal_request()
    {
      global $wpdb;
      $row = $this->get_withdrawal_request();

      if($row != false)
      {
        $amount = $row->amount;

        $cr = projectTheme_get_credits($row->uid);
        projectTheme_update_credits($row->uid, $cr + $amount);

        $s = "delete from ".$wpdb->prefix."project_withdraw where id='{$this->id}'";
        $wpdb->query($s);
      }
    }

}

 ?>

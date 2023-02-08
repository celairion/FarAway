<?php

class own_pagination
{
   public $total_results;
   public $posts_per_page;
   public $cur_page;

   public function __construct($posts_per_page, $total_results, $cur_page)
   {
     $this->posts_per_page = $posts_per_page;
     $this->total_results = $total_results;
     $this->cur_page = $cur_page;
   }

   //----------------------------------

   public function get_total_pages()
   {
      $total_pages = ceil($total_results / $posts_per_page);
      return $total_pages;
   }

  //----------------------------------

   public function display_pagination()
   {

     if($this->total_results == 0) return '';

      $total_pages = ceil($this->total_results / $this->posts_per_page);
      $cp = $_GET['pj'];

      if(empty($cp) or !is_numeric($cp)) $cp = 1;



      $s = '<nav aria-label="Page navigation example">
            <ul class="pagination">';

        for($i=1; $i<=$total_pages; $i++)
        {
              if($cp == $i)
              $s .= "<li class='page-item active'><a href='#' class='page-link'>".$i."</a></li>";
              else {
                $s .= "<li class='page-item'><a class='page-link' href='".$this->cur_page."pj=".$i."'>".$i."</a></li>";
              }
        }


      return $s.'</ul>
    </nav>';
   }


}



 ?>

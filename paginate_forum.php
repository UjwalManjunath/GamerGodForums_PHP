<?php
$limit = $_SESSION['no_of_pages']; 
$targetpage = "view_forum.php";
//	echo $targetpage;
$countq= $conn->query("Select count(*) from topics_proj4 where topic_cat=".$cat_id." and user_deleted=0");
$count = $countq->fetch_row();
$total_pages = $count[0];
	
$stages = 3;
$page = mysql_escape_string($_GET['page']);
	//echo $page;
if($page)
{
    $start = ($page - 1) * $limit;
}
else
{
    $start = 0;
}
		//echo $start;
    //	echo $limit;
	
    // Get page data
	//$result = $conn->query("SELECT * FROM topics_proj4 where topic_cat=".$cat_id." LIMIT $start, $limit");
	//$result = mysql_query($query1);
	
	// Initial page num setup
if ($page == 0)
{
    $page = 1;
}
$prev = $page - 1;
$next = $page + 1;
$lastpage = ceil($total_pages/$limit);
	//echo $lastpage;
$LastPagem1 = $lastpage - 1;
$paginate = '';
if($lastpage > 1)
{
	$paginate .= "<div class='paginate'>";
		// Previous
    if ($page > 1)
    {
        $paginate.= "<a href='$targetpage?catid=$cat_id&page=$prev'>previous</a>";
    }
    else
    {
        $paginate.= "<span class='disabled'>previous</span>";
    }
        // Pages
    if ($lastpage < 7 + ($stages * 2))	// Not enough pages to breaking it up
    {
        for ($counter = 1; $counter <= $lastpage; $counter++)
        {
            if ($counter == $page)
            {
                $paginate.= "<span class='current'>$counter</span>";
            }
            else
            {
					$paginate.= "<a href='$targetpage?catid=$cat_id&page=$counter'>$counter</a>";
            }
        }
    }
    elseif($lastpage > 5 + ($stages * 2))	// Enough pages to hide a few?
    {
			// Beginning only hide later pages
        if($page < 1 + ($stages * 2))
        {
            for ($counter = 1; $counter < 4 + ($stages * 2); $counter++)
            {
                if ($counter == $page)
                {
                    $paginate.= "<span class='current'>$counter</span>";
                }
                else
                {
						$paginate.= "<a href='$targetpage?catid=$cat_id&page=$counter'>$counter</a>";
                }
            }
            $paginate.= "...";
            $paginate.= "<a href='$targetpage?catid=$cat_id&page=$LastPagem1'>$LastPagem1</a>";
            $paginate.= "<a href='$targetpage?catid=$cat_id&page=$lastpage'>$lastpage</a>";
        }
			// Middle hide some front and some back
        elseif($lastpage - ($stages * 2) > $page && $page > ($stages * 2))
        {
            $paginate.= "<a href='$targetpage?catid=$cat_id&page=1'>1</a>";
            $paginate.= "<a href='$targetpage?catid=$cat_id&page=2'>2</a>";
            $paginate.= "...";
            for ($counter = $page - $stages; $counter <= $page + $stages; $counter++)
            {
                if ($counter == $page)
                {
                    $paginate.= "<span class='current'>$counter</span>";
                }
                else
                {
						$paginate.= "<a href='$targetpage?catid=$cat_id&page=$counter'>$counter</a>";
                }
            }
            $paginate.= "...";
            $paginate.= "<a href='$targetpage?catid=$cat_id&page=$LastPagem1'>$LastPagem1</a>";
            $paginate.= "<a href='$targetpage?catid=$cat_id&page=$lastpage'>$lastpage</a>";
        }
			// End only hide early pages
        else
        {
            $paginate.= "<a href='$targetpage?catid=$cat_id&page=1'>1</a>";
            $paginate.= "<a href='$targetpage?catid=$cat_id&page=2'>2</a>";
            $paginate.= "...";
            for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++)
            {
                if ($counter == $page)
                {
                    $paginate.= "<span class='current'>$counter</span>";
                }
                else
                {
                    $paginate.= "<a href='$targetpage?catid=$cat_id&page=$counter'>$counter</a>";}
				}
			}
		}
					// Next
		if ($page < $counter - 1)
        {
			$paginate.= "<a href='$targetpage?catid=$cat_id&page=$next'>next</a>";
		}
        else
        {
			$paginate.= "<span class='disabled'>next</span>";
        }
        $paginate.= "</div>";
}
echo $paginate;
?>
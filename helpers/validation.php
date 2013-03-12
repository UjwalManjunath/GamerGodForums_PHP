<?php
    require_once 'config/config.php';
    //error_reporting(E_ALL);
    //ini_set('display_errors', '1');
    /**
     * Some useful functions to validate data and
     * convert data to insert into our database
     * In some cases a function wil create a
     * database object and use it
     */
    
    /**
     * Takes a string as input and creates a
     * human-friendly URL string.
     *
     * @param string $str The string
     * @return string $permalink
     */
    
    function post_count1($uid)
    {
        $conn1=new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB) or die ("Hey loser, check your server connection.");
        if($postcnt= $conn1->query("select count(*) from posts_proj4 where post_by =$uid") )
            if( mysqli_num_rows($postcnt) >0)
            {
                $pc= $postcnt->fetch_row();
                $postcount = $pc[0];
            }
            else
            {
                $postcount = 0;
            }
        $conn1->close();
        return $postcount;
    }
    
    function functionrole1($uid,$userlvl)
    {
        if($userlvl=="1")
        {
            $role = "Admin";
        }
        elseif($userlvl=="2")
        {
            $role="Moderator";
        }
        else
        {
            $cnt=post_count1($uid);
            if($cnt <=8)
                $role = "Apprentice";
            else if($cnt <=16)
                $role = "Warrior";
            else if($cnt<=24)
                $role = "Knight";
            else if($cnt<=32)
                $role = "King";
            else if($cnt<=40)
                $role = "Demi God";
            else
                $role = "Gamer God";
        }
        return $role;
    }
    
    function imagelimit($role)
    {
        $mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB) or die ("Hey loser, check your server connection.");
        
        if( $res = $mysqli-> query("select Maxattachments from Settings_proj2"))
        {
            if( mysqli_num_rows($res) >0)
            {
                $fetching_max_attach = $res->fetch_row();
                $max = $fetching_max_attach[0];
                //echo $id_topic;
                //$mysqli->close();
            }
            
        }
        $mysqli->close();
        
        if($role=="Admin")
            $lim=$max;
        elseif($role=="Moderator")
		$lim=$max;
        elseif($role=="Apprentice")
		$lim=1*($max/5);
        elseif($role=="Warrior")
		$lim=2*($max/5);
        elseif($role=="Knight")
		$lim=3*($max/5);
        elseif($role=="King")
		$lim=4*($max/5);
        elseif($role=="Demi God")
		$lim=5*($max/5);
        elseif($role=="Gamer God")
		$lim=$max;
        return $lim;
    }
    
    function messageBox($new, $errorHTML = null,$id)
    {
        $content = '<div id="box_message">';
        if (!empty($errorHTML))
        {
            $content .= '<div id="message_header"><p class="error">' . $errorHTML . '</p></div>';
        }
        if ($new)
        {
            $content .= '<h1>Post a Message</h1>';
        }
        else
        {
            $content .= '<h1>Post a Response</h1>';
        }
        $content .= '<form action="" method="post" id="post_message" enctype="multipart/form-data" accept-charset="utf-8">';
        if ($new)
        {
            $content .= '<input id="topic_subject" type="text" name="topic_subject"  size="20" placeholder="Title of the thread"/>';
        }
        $content .= '<input type="hidden" name="topic_cat" value='. $id . ' size="30" />';
        $content .= '<textarea name="topic_content" rows="8" cols="88" placeholder="Message"></textarea>';
        $content .= '<table >';
        $content .= '<tr>';
        //<form action="multiple_upload_ac.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
        $content .= '<td>';
        $content .= '<table>';
        $content .= '<tr>';
        $role = functionrole1($_SESSION['user_id'],$_SESSION['user_level']);
        //echo $role;
        $imgcnt = imagelimit($role);
        //echo $imgcnt;
        for($i=1;$i<=$imgcnt;$i++)
        {
            $content .= '<td>Select file ';
            $content .= '<input name="ufile[]" type="file" id="ufile[]" size="50" />';
            $content .= '</td>';
            if(($i % 2)==0)
            {
                $content .= '</tr>';
                $content .= '<tr>';
            }
        }
        $content .= '</tr>';
        $content .= '</table>';
        $content .= '</td>';
        $content .= '</tr>';
        $content .= '</table>';
        if ($new)
        {
            $content .= '<input type="submit" id="submit_box" value="Post the message" />';
        }
        else
        {
            $content .= '<input type="submit" id="submit_box" value="Post reply" />';
        }
        $content .= '</form>';
        $content .= '</div>';
        return $content;
    }
    
    function pageurl()
    {
        $pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
        if ($_SERVER["SERVER_PORT"] != "80")
        {
            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        }
        else
        {
            $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }
	
    function strToPermalink($str)
    {
        $permalink = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $permalink = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $permalink);
        $permalink = strtolower(trim($permalink, '-'));
        $permalink = preg_replace("/[\/_|+ -]+/", '_', $permalink);
        return $permalink;
    }
    
    /**
     * Clean a string so we can use it in queries
     * @param mixed $value
     */
    function clean($value)
    {
        $mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB) or die ("Hey loser, check your server connection.");
        
        // Stripslashes
        if (get_magic_quotes_gpc())
        {
            $value = stripslashes( $value );
        }
        
        // Quote if not a number or a numeric string
        if (!is_numeric($value) && !empty($value))
        {
            $value = $mysqli->real_escape_string($value);
        }
        $mysqli->close();
        return $value;
    }
    
    /**
     * Takes an array as input, checks if everything is correct
     * and inserts the post into the database.
     * If something is wrong an errorCode is analyzed and
     *  an error message is returned in an array.
     *
     * The errorCode can be:
     * 	-1 -> A field is empty
     *	-2 -> Title is duplicated
     * 	-3 -> Something is wrong with the INSERT query
     *
     * @param string $permalink The permalink we have to process
     * @param bool $newThread
     *
     * @return array $returnArray Return an array with some useful values to know what happened
     *
     */
    function isValidPost($input,  $newThread)
    {
        if ($newThread)
        {
            return !empty($input['topic_subject'])
            && !empty($input['topic_content']) ;
        }
        else
        {
            return !empty($input['topic_content']) ;
        }
    }
	
    function isValidAttachment($input2)
    {
        $valid_formats = array("jpg", "jpeg", "gif", "png");
    }
	
    function sendmail($uid,$newthread,$topic_id,$id)
    {
        $mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB) or die ("Hey loser, check your server connection.");
        
        
        $sql = "SELECT subscriber,notify,notify_key,keywords,f.firstname,f.lastname,u.user_email FROM `subscribers` join users_proj4 u on(subscriber = u.user_id) \n"
        . "join users_proj4 f on ( subscribed_to=f.user_id)\n"
        . "where f.user_id = $uid and subscribed_to=$uid LIMIT 0, 30 ";
        if($result2 = $mysqli->query($sql))
        {
            while($emaillist = $result2->fetch_assoc())
            {
                if($emaillist['notify']==1 )
                {
                    $to=$emaillist['user_email'];
                    $subject="Gamer God Forum Alerts";
                    if($newthread)
                    {
                        $message=$emaillist['firstname']." ".$emaillist['lastname']." has created a new thread\r\n";
                        $message.="Click on this link to view the thread \r\n";
                        $message.="https://mweigle418.cs.odu.edu/~umanjuna/proj4/view_thread.php?topic_id=$topic_id";
                    }
                    else
                    {
                        $message=$emaillist['firstname']." ".$emaillist['lastname']." just posted\r\n";
                        $message.="Click on this link to view the thread \r\n";
                        $message.="https://mweigle418.cs.odu.edu/~umanjuna/proj4/view_thread.php?topic_id=$topic_id#$id";
                    }
                    $headers .= 'From: <webmaster@gamer.com>' . "\r\n";
                    $sentmail = mail($to,$subject,$message,$headers);
                }
                elseif( $emaillist['notify_key']==1)
                {
                    
                }
                
                
            }
        }
        $mysqli->close();
    }
    
    function savePost($input, $newThread, $input2)
    {
        $mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB) or die ("error");
        $postOK = isValidPost($input, $newThread);
        //$post2OK = isValidAttachment($input2);
        if (!$postOK)
        {
            return -1;  // something missing
        }
        $dat = date(' Y-m-d H:i:s');
        if ($newThread)
        {
            $query = 'INSERT INTO topics_proj4 '
            . '(topic_subject, topic_content, topic_date, topic_cat, topic_by) VALUES'
            . '("%s","%s","'.$dat.'","'.$input['topic_cat'].'" ,"'.$_SESSION['user_id'].'" )';
            
            $query = sprintf($query,
                             clean(strip_tags($input["topic_subject"])),
                             clean(strip_tags($input["topic_content"])));
        }
        else
        {
            if( $res = $mysqli-> query("select * from topics_proj4 where topic_id= ".$input['topic_cat'].""))
            {
                if( mysqli_num_rows($res) >0)
                {
                    $query = 'INSERT INTO posts_proj4 '
					. '(post_content, post_date, post_topic,post_by) VALUES'
					. '("%s","'.$dat.'","'.$input['topic_cat'].'","'.$_SESSION['user_id'].'")';
					
                    $query = sprintf($query,clean(strip_tags($input['topic_content'])));
                }
                else
                {
                    echo '<script type="text/javascript">';
                    echo 'window.alert("I am sorry the thread does not exist anymore!!")';
                    echo 'window.location = "https://mweigle418.cs.odu.edu/~umanjuna/proj4/index.php";';
                    echo '</script>';
                }
            }
            
        }
        if ($results=$mysqli->query($query))
        {
            /* code for inserting image into message */
            if($newThread)
            {
                $topic_sub=clean(strip_tags($input["topic_subject"]));
				//get message id which was inserted
                $conn1=new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB) or die ("Hey loser, check your server connection.");
                if($tpic= $conn1->query("select topic_id,topic_content from topics_proj4 where topic_subject='$topic_sub'") )
                {
                    if( mysqli_num_rows($tpic) >0)
                    {
                        $fetching_topic_id = $tpic->fetch_row();
                        $id_topic = $fetching_topic_id[0];
                        $conn1->close();
                    }
                }
            }
            else
            {
                //get postid which was inserted
                $topic_con=clean(strip_tags($input["topic_content"]));
                $conn1=new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB) or die ("Hey loser, check your server connection.");
                if($ppic= $conn1->query("select post_id,post_content from posts_proj4 where post_content='$topic_con'") )
                {
                    if( mysqli_num_rows($ppic) >0)
                    {
                        $fetching_post_id = $ppic->fetch_row();
                        $id_post = $fetching_post_id[0];
                        $conn1->close();
                    }
                }
                else
                {
                    echo "error retreiving post id";
                }
                
            }
            $uid = $_SESSION['user_id'];
			
            /*	if($newThread)
             {
             $sql = "SELECT subscriber,f.firstname,f.lastname,u.user_email FROM `subscribers` join users_proj4 u on(subscriber = u.user_id) \n"
             . "join users_proj4 f on ( subscribed_to=f.user_id)\n"
             . "where f.user_id = $uid and subscribed_to=$uid LIMIT 0, 30 ";
             if($result2 = $mysqli->query($sql))
             {
             while($emaillist = $result2->fetch_assoc())
             {
             $to=$emaillist['user_email'];
             $subject="Gamer God Forum Alerts";
             
             $message=$emaillist['firstname']." ".$emaillist['lastname']." Created a new Thread\r\n";
             $message.="Click on this link to view the thread \r\n";
             $message.="https://mweigle418.cs.odu.edu/~umanjuna/proj4/view_thread.php?topic_id=$id_topic";
             $headers .= 'From: <webmaster@gamer.com>' . "\r\n";
             $sentmail = mail($to,$subject,$message,$headers);
             }
             }
             
             
             }
             else
             {*/
            $sql = "SELECT subscriber,notify,notify_key,keywords,f.firstname,f.lastname,u.user_email FROM `subscribers` join users_proj4 u on(subscriber = u.user_id) \n"
            . "join users_proj4 f on ( subscribed_to=f.user_id)\n"
            . "where f.user_id = $uid and subscribed_to=$uid LIMIT 0, 30 ";
            if($result2 = $mysqli->query($sql))
            {
                while($emaillist = $result2->fetch_assoc())
                {
                    if($emaillist['notify']==1 )
                    {
                        $to=$emaillist['user_email'];
                        $subject="Gamer God Forum Alerts";
                        if($newThread)
                        {
                            $message=$emaillist['firstname']." ".$emaillist['lastname']." Created a new Thread\r\n";
                            $message.="Click on this link to view the thread \r\n";
                            $message.="https://mweigle418.cs.odu.edu/~umanjuna/proj4/view_thread.php?topic_id=$id_topic";
                            $headers .= 'From: <webmaster@gamer.com>' . "\r\n";
                            $sentmail = mail($to,$subject,$message,$headers);
                        }
                        else
                        {
                            $message=$emaillist['firstname']." ".$emaillist['lastname']." just posted\r\n";
                            $message.="Click on this link to view the post \r\n";
                            $message.=pageurl();
                            $headers .= 'From: <webmaster@gamer.com>' . "\r\n";
                            $sentmail = mail($to,$subject,$message,$headers);
                        }
                    }
                    elseif( $emaillist['notify_key']==1)
                    {
                        $to=$emaillist['user_email'];
                        $subject="Gamer God Forum Alerts";
                        $searchstring= $emaillist['keywords'];
                        if($newThread)
                        {
                            $conn1=new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB) or die ("Hey loser, check your server connection.");
                            if($tpic_cnt_match= $conn1->query("SELECT * FROM topics_proj4 WHERE MATCH (topic_content) AGAINST ('$searchstring' IN BOOLEAN MODE) and topic_id=$id_topic") )
                            {
                                if( mysqli_num_rows($tpic_cnt_match) >0)
                                {
                                    $fetching_topic_id = $tpic_cnt_match->fetch_row();
                                    $sub_topic = $fetching_topic_id[1];
                                    $con_topic = $fetching_topic_id[2];
                                    $message=$emaillist['firstname']." ".$emaillist['lastname']." Created a new Thread. the messages mathches the keyword you requested\r\n";
                                    $message.="Topic Subject = ".$sub_topic."\r\n";
                                    $message.="Topic Message = ".$con_topic."\r\n";
                                    $message.="Click on this link to view the thread \r\n";
                                    $message.="https://mweigle418.cs.odu.edu/~umanjuna/proj4/view_thread.php?topic_id=$id_topic";
                                    $headers .= 'From: <webmaster@gamer.com>' . "\r\n";
                                    $sentmail = mail($to,$subject,$message,$headers);
                                    $conn1->close();
                                }
                            }
                            
                        }
                        else
                        {
                            $conn1=new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB) or die ("Hey loser, check your server connection.");
                            if($post_cnt_match= $conn1->query("SELECT * FROM posts_proj4 WHERE MATCH (post_content) AGAINST ('$searchstring' IN BOOLEAN MODE) and post_id=$id_post") )
                            {
                                if( mysqli_num_rows($post_cnt_match) >0)
                                {
                                    $fetching_post_id = $post_cnt_match->fetch_row();
                                    $con_post = $fetching_post_id[1];
                                    $topic_num = $fetching_post_id[3];
                                    $message=$emaillist['firstname']." ".$emaillist['lastname']." posted. the post mathches the keyword you requested\r\n";
                                    $message.="Post Message = ".$con_post."\r\n";
                                    $message.="Click on this link to view the post \r\n";
                                    $message.=pageurl();
                                    $headers .= 'From: <webmaster@gamer.com>' . "\r\n";
                                    $sentmail = mail($to,$subject,$message,$headers);
                                    $conn1->close();
                                }
                            }
                        }
                        
                    }
                    
                }
            }
			
			
            //	}
            $path = "images/uploads/postpics/";
            // GET value from databse and substitute for max limit.
            
            if( $res = $mysqli-> query("select Maxattachments from Settings_proj2"))
            {
                if( mysqli_num_rows($res) >0)
                {
                    $fetching_max_attach = $res->fetch_row();
                    $max = $fetching_max_attach[0];
                    //$mysqli->close();
                }
                
            }
            //echo $max;
            //$mysqli->close();
            for( $i=0; $i<$max ; $i++)
            {
                if(($input2['ufile']['size'][$i]) > 0)
                {
                    $uid = $_SESSION['user_id'];
                    $namefile = $input2['ufile']['name'][$i];
                    list($txt, $ext) = explode(".", $namefile);
                    $actual_image_name = time().substr(str_replace(" ", "_", $txt), 5).".".$ext;
                    if($newThread)
                    {
                        $uid = $_SESSION['user_id'];
                        if($result1= $mysqli->query("INSERT INTO attachments_proj4 (message_id,post_by,message_attachment) VALUES ($id_topic,$uid,'$actual_image_name')"))
                        {
                            $tmp = $input2['ufile']['tmp_name'][$i];
                            move_uploaded_file($tmp, $path.$actual_image_name);
                            echo "image inserted";
                        }
                        else
                        {
                            echo "inserting into messgae error";
                        }
                    }
                    else
                    {
                        if($result1= $mysqli->query("INSERT INTO attachments_proj4 (post_id,post_by,post_attachment) VALUES ($id_post,$uid,'$actual_image_name')"))
                        {
                            $tmp = $input2['ufile']['tmp_name'][$i];
                            move_uploaded_file($tmp, $path.$actual_image_name);
                        }
                        else
                        {
                            echo "insertion into post error";
                        }
                    }
                }
                
            }
            $mysqli->close();
            return 1;
            
        } elseif (mysql_errno() == 1062)
        {
            return -2;  // Duplicated title error
        }
        else
        {
            return -3;  // DB error
        }
    }	
	
	
	
    function processPost($newThread = true)
    {
        $input = $_POST;
        $input2 = $_FILES;
        //echo $input['topic_subject'];
        //echo $input['topic_content'];
        //echo $input['topic_cat'];
        
        //The array we'll return
        $returnArray = array('errorHTML' => NULL,
                             'okMessage' => NULL,
                             'showBox' => NULL);
        
        if (array_sum($input) > 0 )
        {
            //We have something in the $input and the Recaptcha response
            $saveResult = savePost($input,$newThread,$input2);
            switch ($saveResult)
            {
                case -1:  //A field is empty
                    $returnArray['errorHTML'] = 'All fields are required';
					break;
                case -2: //The title is already in use
					$returnArray['errorHTML'] = 'Duplicated title, please, choose another one.';
					break;
                case -3: //There has been an error with the query
					$returnArray['errorHTML'] = 'There has been an error with the Database, '.
                    'please, try again later.';
					break;				
                default: //Everything is OK
					$returnArray['okMessage'] = '<p>The post has been published.  </p>';
                    //.'<a href="view_thread.php?permalink=' . $permalink . '">here</a></p>';
            }
            
        }
        else
        {
            //We have something in the $input array but the recaptcha response is not
            $returnArray['errorHTML'] = 'All fields are required';
        }
        
        /* Anyway we compose a boolean value to know if we have to show 
         the box to write a message or not */
        if ($newThread)
        {
            $returnArray['showBox'] = !empty($returnArray['errorHTML']) 
            || !empty($returnArray['recaptchaError']) 
            || empty($input);		
        }
        
        return $returnArray;
    }
    ?>
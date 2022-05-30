<?php  
include './libs/database.php';


function breakCodeInsertion($total_user,$per_slot_user,$shift,$breakValue,$colum,$columData, $start ){
    include './libs/database.php';
    // echo $total_user."-".$per_slot_user."-".$shift."-".$breakValue;
    if($breakValue!="lunch_break"){
        echo "start:". $current=0;
        $slot_counter = 0;

        $number_of_loop=count($colum);
        $total_slot_user=$per_slot_user*$number_of_loop;
        while($slot_counter<$number_of_loop) {
            print_r($colum);
            print_r($columData);
            $number_of_slot=count($colum);
            for($counter = 0;$counter<$number_of_slot;$counter++){
                if($counter!=$slot_counter){
                    $now_field = $colum[$counter];
                    $id_list = array();
                    $id_qry = mysql_query("SELECT `id` FROM `db_shooz`.`fifteen_minute_schedul` WHERE (`shift_name`='$shift' AND `create_date`='$start') ORDER BY id DESC LIMIT $current,$per_slot_user");

                    echo "SELECT `id` FROM `db_shooz`.`fifteen_minute_schedul` WHERE (`shift_name`='$shift' AND `create_date`='$start') ORDER BY id DESC LIMIT $current,$per_slot_user";
                    
                    while ($id_data = mysql_fetch_assoc($id_qry)) {
                        array_push($id_list,$id_data['id']);
                    }
                    print_r($id_list);
                    $ssql = "UPDATE `db_shooz`.`fifteen_minute_schedul` SET `$now_field` = 'working'  WHERE id IN (" . implode(",", $id_list) . ")";
                        echo $ssql;
                        mysql_query($ssql);
                    }

                }
                $slot_counter +=1; 

                $current+=$per_slot_user;
                /*if ($total_slot_user<$total_user) 
                {
                    if(($current+$per_slot_user+$per_slot_user)>$total_user) {
                        $per_slot_user=$total_user-$current;
                    }

                }
                else
                {

                }*/

                // if(($current+$per_slot_user+$per_slot_user)>$total_user) {
                //     $per_slot_user=$total_user-$current;
                // }

                if(($slot_counter+1)==$number_of_slot) {
                    $per_slot_user=$total_user-$current;
                }
                

                echo "current: ".$current. "per_slot_user".$per_slot_user;
            }
        }
        if($breakValue=="lunch_break"){
            echo "start:". $current=0;
            $slot_counter = 0;
            $number_of_loop=count($colum);
            while($slot_counter<$number_of_loop) {
                print_r($colum);
                print_r($columData);
                $number_of_slot=count($colum);
                for($counter = 0;$counter<$number_of_slot;$counter+=2){
                    if($counter!=$slot_counter){
                        $now_field1 = $colum[$counter];
                        $now_field2 = $colum[$counter+1];
                        $id_list = array();
                        $id_qry = mysql_query("SELECT id FROM `db_shooz`.`fifteen_minute_schedul` WHERE (`shift_name`='$shift' AND `create_date`='$start') ORDER BY id DESC LIMIT $current,$per_slot_user");
                        echo "peruserslot".$per_slot_user;
                    //echo "SELECT id FROM `db_shooz`.`fifteen_minute_schedul` WHERE `shift_name`='$shift' ORDER BY id ASC LIMIT $current,$per_slot_user";
                        while ($id_data = mysql_fetch_assoc($id_qry)) {
                            array_push($id_list,$id_data['id']);
                        }
                        print_r($id_list);
                        $ssql = "UPDATE `db_shooz`.`fifteen_minute_schedul` SET `$now_field1` = 'working', `$now_field2`='working'  WHERE id IN (" . implode(",", $id_list) . ")";
                            echo $ssql;
                            mysql_query($ssql);
                        }

                    }
                    $slot_counter +=2; 

                    $current+=$per_slot_user;

                    if(($slot_counter+2)==$number_of_slot) {
                        $per_slot_user=$total_user-$current;
                    }
                    // if(($current+$per_slot_user+$per_slot_user)>$total_user) {
                    //     $per_slot_user=$total_user-$current;
                    // }

                    echo "current: ".$current;
                }
            }

        }




        $TYPE = $_POST['TYPE'];
        if ($TYPE == "SET_ROSTER") {
            $start = $_POST['start'];
            $shift=$_POST['shift_name'];
            $userType=$_POST['userType'];
            $campName=$_POST['campName'];
            $userId=$_POST['userName'];
            $userIds = rtrim($userId, ",");

            $dateArr=array();
            for ($d=0; $d < 7; $d++) { 
               $dateDif="+".$d." day";
               $endDate = date('Y-m-d',strtotime($dateDif, strtotime($start)));
               array_push($dateArr, $endDate);
            }

            foreach ($dateArr as $startDate) {
                 $user = mysql_query("SELECT * FROM `roster`.`users_info` WHERE `schedule_date`='$startDate' AND `active_status`=1 AND `user_type`='$userType' AND `campaign_name`='$campName' AND `user_name` IN (" .$userIds. ")");
                   echo "SELECT * FROM `roster`.`users_info` WHERE `schedule_date`='$startDate' AND `active_status`=1 AND `user_type`='$userType' AND `campaign_name`='$campName' AND `user_name` IN (" .$userIds. ")";

                $shift_result=mysql_fetch_assoc(mysql_query("SELECT * FROM `roster`.`tbl_shift` WHERE `shift_name`='$shift'"));
                $shift_info = json_decode($shift_result['shift_info'], true);
                $SixDigitRandomNumber = rand(100000,999999);
                while($row = mysql_fetch_assoc($user))
                {
                    mysql_query("INSERT INTO `db_shooz`.`fifteen_minute_schedul` (`user_id`, `shift_name`, `campaign_name`, `create_date`,`group_uid`) VALUES ('".$row['user_name']."', '".$shift."', '".$campName."', '".$startDate."', '".$SixDigitRandomNumber."')");
                    $lastId=mysql_fetch_assoc(mysql_query("SELECT id FROM `db_shooz`.`fifteen_minute_schedul` ORDER BY id DESC LIMIT 1"));
                    $updateId=$lastId['id'];

                    foreach ($shift_info as $key => $value) 
                    {
                        $dataUpdate=mysql_query("UPDATE `db_shooz`.`fifteen_minute_schedul` SET `$key`='".$value."' WHERE `id`='".$updateId."'");
                    }
                }
                $total_user=mysql_num_rows($user);
                echo "number of User: ".$total_user;
                $col=array();
                $workstatus=array();

                // workstatus
                $workCode=array();
                $workCodeCol=array();

                foreach ($shift_info as $key => $value) 
                {
                    if ($value!="working") 
                    {
                        array_push($workCode, $value);
                        array_push($workCodeCol, $key);
                    }

                    if ($value=="first_short_break") {
                        array_push($col, $key);
                        array_push($workCode, $value);
                    }
                }
                $breakCodeArray=array_unique($workCode);        

                foreach ($breakCodeArray as $breakValue) 
                {
                    $colum=array();
                    $columData=array();
                    foreach ($shift_info as $key => $value) 
                    {
                        if ($breakValue==$value) 
                        {
                            array_push($colum, $key);
                            array_push($columData, $value);
                        }
                    }
                    //echo $breakValue;
                    print_r($colum);
                    print_r($columData);
                    $number_of_break_slot=count($colum);

                    if($breakValue=="lunch_break"){
                        $number_of_break_slot=$number_of_break_slot/2;
                    }
                    echo "Number of Break: ".$number_of_break_slot;
                    $per_slot_user=round($total_user/$number_of_break_slot);
                    echo "Per slot user: ".$per_slot_user;
                    echo "<br><br>";
                    breakCodeInsertion($total_user,$per_slot_user,$shift,$breakValue,$colum,$columData,$startDate);
                    echo "<br><br>";
                }
            }
            // $user = mysql_query("SELECT * FROM `roster`.`users_info` WHERE `schedule_date`='$start' AND `active_status`=1 AND `user_type`='$userType' AND `campaign_name`='$campName' AND `user_name` IN (" .$userIds. ")");
            //     $shift_result=mysql_fetch_assoc(mysql_query("SELECT * FROM `roster`.`tbl_shift` WHERE `shift_name`='$shift'"));
            //     $shift_info = json_decode($shift_result['shift_info'], true);
            //     $SixDigitRandomNumber = rand(100000,999999);
            //     while($row = mysql_fetch_assoc($user))
            //     {
            //         mysql_query("INSERT INTO `db_shooz`.`fifteen_minute_schedul` (`user_id`, `shift_name`, `campaign_name`, `create_date`,`group_uid`) VALUES ('".$row['user_name']."', '".$shift."', '".$campName."', '".$start."', '".$SixDigitRandomNumber."')");
            //         $lastId=mysql_fetch_assoc(mysql_query("SELECT id FROM `db_shooz`.`fifteen_minute_schedul` ORDER BY id DESC LIMIT 1"));
            //         $updateId=$lastId['id'];

            //         foreach ($shift_info as $key => $value) 
            //         {
            //             $dataUpdate=mysql_query("UPDATE `db_shooz`.`fifteen_minute_schedul` SET `$key`='".$value."' WHERE `id`='".$updateId."'");
            //         }
            //     }
            //     $total_user=mysql_num_rows($user);
            //     echo "number of User: ".$total_user;
            //     $col=array();
            //     $workstatus=array();

            //     // workstatus
            //     $workCode=array();
            //     $workCodeCol=array();

            //     foreach ($shift_info as $key => $value) 
            //     {
            //         if ($value!="working") 
            //         {
            //             array_push($workCode, $value);
            //             array_push($workCodeCol, $key);
            //         }

            //         if ($value=="first_short_break") {
            //             array_push($col, $key);
            //             array_push($workCode, $value);
            //         }
            //     }
            //     $breakCodeArray=array_unique($workCode);        

            //     foreach ($breakCodeArray as $breakValue) 
            //     {
            //         $colum=array();
            //         $columData=array();
            //         foreach ($shift_info as $key => $value) 
            //         {
            //             if ($breakValue==$value) 
            //             {
            //                 array_push($colum, $key);
            //                 array_push($columData, $value);
            //             }
            //         }
            //         //echo $breakValue;
            //         print_r($colum);
            //         print_r($columData);
            //         $number_of_break_slot=count($colum);

            //         if($breakValue=="lunch_break"){
            //             $number_of_break_slot=$number_of_break_slot/2;
            //         }
            //         echo "Number of Break: ".$number_of_break_slot;
            //         $per_slot_user=round($total_user/$number_of_break_slot);
            //         echo "Per slot user: ".$per_slot_user;
            //         echo "<br><br>";
            //         breakCodeInsertion($total_user,$per_slot_user,$shift,$breakValue,$colum,$columData,$start );
            //         echo "<br><br>";
            //     }


            // echo $start;

                $arr = array("status" => "success", "data" => "Data Inserted!");
                    echo json_encode($arr);
                } else{
                    echo "not working";
                }

            ?>
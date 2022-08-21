<?php
//Connect to database
require'connectDB.php';

$output = '';

if(isset($_POST["To_Excel"])){
  
    if ( empty($_POST['date_sel'])) {

        $Log_date = date("Y-m-d");
    }
    else if ( !empty($_POST['date_sel'])) {

        $Log_date = $_POST['date_sel']; 
    }
        //$sql = "SELECT * FROM users_logs WHERE checkindate='$Log_date' ORDER BY id DESC";
		  $sql = "SELECT U.id,
					U.username,
					U.serialnumber,
					U.fingerprint_id,
					UL.checkindate,
					CASE WHEN UL.timein > 0 THEN '1' ELSE '0' END AS 'Status'
				FROM users U
				LEFT JOIN users_logs UL 
					ON UL.id = U.id
					AND UL.checkindate = '$Log_date'";
        $result = mysqli_query($conn, $sql);
        if($result->num_rows > 0){
            $output .= '
                        <table class="table" bordered="1">  
                          <TR>
                            <TH>S. No</TH>
                            <TH>Name</TH>
                            <TH>Reg. No</TH>
                            <TH>Attendance Date</TH>
                            <TH>Status</TH>
                          </TR>';
              while($row=$result->fetch_assoc()) {
                  $output .= '
                              <TR> 
                                  <TD> '.$row['id'].'</TD>
                                  <TD> '.$row['username'].'</TD>
                                  <TD> '.$row['serialnumber'].'</TD>
                                  <TD> '.$row['checkindate'].'</TD>
                                  <TD> '.$row['Status'].'</TD>
                              </TR>';
              }
              $output .= '</table>';
              header('Content-Type: application/xls');
              header('Content-Disposition: attachment; filename=User_Log'.$Log_date.'.xls');
              
              echo $output;
              exit();
        }
        else{
            header( "location: UsersLog.php" );
            exit();
        }
}
?>
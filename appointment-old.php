<!DOCTYPE html>
<!-- Group C - Swann Thantsin, Yinon Shirazi, Nursultan Zhumabaev -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LOGIN</title>
    <style type="text/css">
    h1{ color: white;}
    body{ font: 20px sans-serif;
          border: solid;
          border-width: 1em;
          border-color: #20B2AA;
          margin: 5%;
          background-color: #25274D;
          }
    .box{ width: 100%;
          height: 700px;
          text-align: center;
          background-color: #464866;
          opacity: 0.8;
          }

		  /* CSS for the Submit button (form) */
          input[type=submit]
		      {
		      position: absolute;
		      top: 70%;
		      left: 45%;
          background-color: #20B2AA;
          border: none;
          color: black;
          width:200px;
          padding: 12px 18px;
          text-align: center;
          margin: 8px 2px;
          cursor: pointer;
          }

          .closeButton
          {
            margin-top: 0;
            text-align: center;
            font-size: 2em;
            margin-left: 88%;
            width: 2em;
            height: 2em;
            position: sticky;
          }

          .submit
          {
            position:sticky;
            margin-bottom: 5%;

          }

          /* Add media queries for smaller screens */
          @media screen and (max-width:720px) {
            body {width: 13.1%;}
          }

          @media screen and (max-width: 420px) {
            body {width: 12.5%;}
          }

          @media screen and (max-width: 290px) {
            body {width: 12.2%;}
          }
    </style>
<?php
	date_default_timezone_set('America/New_York'); //sets timezone to EST
	/* Attempt MySQL server connection.*/
	$conn = mysqli_connect("localhost", "root", "", "CSC350GroupCTerm");
	// Check connection
	if($conn === false)
	{
		die("ERROR: Could not connect. " . mysqli_connect_error());
	}
	// Attempt select query execution
	else
		$sql = 'select sessionid from csc350groupcterm.schedule order by sessionid desc limit 1;';
	$result = mysqli_query($conn, $sql);
	//Sets up the sessionid of the laundry timeslots to be 0 or the latest + 1
	if (mysqli_num_rows($result) == 0)
	{
		$scheduleid = 0;
	}
	else
	{
		$row = mysqli_fetch_row($result);
		$scheduleid = (int)$row[0] + 1;
	}
	//Receives dateData array, booldate array, and userApt value from the session
	session_start();
	$dateData = $_SESSION['datedata'];
	$boolDate = $_SESSION['booldate'];
	$userApt = $_SESSION['userApt'];

	//If conditions for delete has been set by the timeslots.php, the sessionid for laundry entered is replaced with the id deleted, else the sessionid would be as entered previously
	if (isset($_REQUEST['postdelete']))
	{
		$deletecondition = $_REQUEST['postdelete'];

		if ($deletecondition == -1)
			$insertingID = $scheduleid;
		else
			$insertingID = $deletecondition;
	}

	//turns datetime data into a string and enters sessionid, apartment number of the user, and the datetime string of the laundry timeslot into the schedule table
	if (isset($_REQUEST['timeslot']))
	{
		$timeslotdata = explode(",", $_POST['timeslot']);

		$time = ($timeslotdata[0] *3);
		if($time < 10)
			$timeStr = '0'.strval($time);
		else
			$timeStr = strval($time);

		$dayIndex = $timeslotdata[1];
		$day = $dateData[$dayIndex];
		$dayStr = date_format($day,"Ymd");

		$date = $dayStr.$timeStr."0000";

		$sql = 'insert into csc350groupcterm.schedule values ('.$insertingID .','.$userApt.','.$date.');';
		$result = mysqli_query($conn, $sql);

	}
?>
</head>
<body>
  <div class="box">
  <div class="closeButton">
    <a  style="color:white;" href="login.php">Logout</a>
  </div>
	<br><br><br>
	<h1 style="margin:auto;">Laundry Timeslot</h1>
	<br>
	<div>
<?php
		//Sets the start of the current week to be 11:59:59PM of last week's sunday
		$startDate = clone $dateData[0];
		date_modify($startDate, '-1 day');
		date_time_set($startDate, 23,59,59);
		$startDateSTR = date_format($startDate,"YmdHis");

		//Sets the end of the current week to be 11:59:59PM of this week's sunday
		$endDate = clone $dateData[6];
		date_time_set($endDate, 23, 59, 59);
		$endDateSTR = date_format($endDate,"YmdHis");

		//receives user information from UserInfo table
		$sql = 'SELECT * FROM CSC350GroupCTerm.UserInfo where apt = "'.$userApt.'";';
		$result = mysqli_query($conn, $sql);
		$row = mysqli_fetch_assoc($result);

	    echo '<br><br><br>'.'<h2  style="color: white">'.$row['FIRSTNAME'].' '.$row['LASTNAME'].', your laundry timeslot is at: <br>'.'</h2>';
		//receives timeslot information from the schedule table with conditions on both userinfo and itself being met
		$sql = 'select date_format(schedule.usedate,"%W %m/%d %h:%i%p") from userinfo join schedule on userinfo.apt = schedule.apt where schedule.apt ='.$userApt.' and usedate > '.$startDateSTR.' and usedate < '.$endDateSTR.';';
		$result = mysqli_query($conn, $sql);
		$row = mysqli_fetch_row($result);

		echo '<h1 style = "margin-top:2.5em;">'.$row[0].'</h1>';
	//receives sessionid from the schedule table to be sent into timeslots.php to be deleted
	$sql = 'select sessionid from userinfo join schedule on userinfo.apt = schedule.apt where schedule.apt ='.$userApt.' and usedate > '.$startDateSTR.' and usedate < '.$endDateSTR.';';
	$result = mysqli_query($conn, $sql);
	$todelete = mysqli_fetch_row($result);

  ?>
	<form action="timeslots.php" method="post">
		<input type="hidden" name="todelete" value="<?php echo $todelete[0]; ?>"/>
		<div class="submit">
         <input type="submit" value="Change Your Time Schedule">
		</div>
        </div>
    </div>
  </body>
  </html>

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
          margin: 15%;
          margin-top: 350px;
          background-color: #25274D;
          }
    .box{ width: 100%;
          height: 400px;
          text-align: center;
          background-color: #464866;
          opacity: 0.8;
          }
          a:link, a:visited
          {
          background-color: #20B2AA;
          border: none;
          color: black;
          padding: 12px 18px;
          text-decoration: none;
          margin: 4px 2px;
          cursor: pointer;
          }
          input[type=submit] {  /* CSS for the Submit button (form) */
          background-color: #20B2AA;
          border: none;
          color: black;
          width:120px;
          padding: 12px 18px;
          text-decoration: none;
          margin: 4px 2px;
          cursor: pointer;
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
</head>
<?php
	date_default_timezone_set('America/New_York'); //sets timezone to EST
    if (isset($_REQUEST['username']))
        $userId = $_REQUEST['username'];
    else
        $userId = NULL;

    if(isset($_REQUEST['pwd']))
        $password = $_REQUEST['pwd'];
    else
        $password = NULL;

    $dateArray = getDate();
    $date=date_create();

    switch ($dateArray['wday']){ // setting up different arrays for each different case and a boolean array bool date that sets 0 for the days passed
        case 0:
          $index = array('Mo' => "-6 days", 'Tu' => "-5 days", 'We'=> "-4 days" ,'Th' => "-3 days", 'Fr' => "-2 days", 'Sa' => "-1 days", 'Su' => "0 days");
		  $boolDate = array(0,0,0,0,0,0,1);
        break;
        case 1:
          $index = array('Mo' => "0 days", 'Tu' => "1 days", 'We'=> "2 days" ,'Th' => "3 days", 'Fr' => "4 days", 'Sa' => "5 days", 'Su' => "6 days");
		  $boolDate = array(1,1,1,1,1,1,1);
        break;
        case 2:
          $index = array('Mo' => "-1 days", 'Tu' => "0 days", 'We'=> "1 days" ,'Th' => "2 days", 'Fr' => "3 days", 'Sa' => "4 days", 'Su' => "5 days");
		  $boolDate = array(0,1,1,1,1,1,1);
        break;
        case 3:
          $index = array('Mo' => "-2 days", 'Tu' => "-1 days", 'We'=> "0 days" ,'Th' => "1 days", 'Fr' => "2 days", 'Sa' => "3 days", 'Su' => "4 days");
		  $boolDate = array(0,0,1,1,1,1,1);
        break;
        case 4:
          $index = array('Mo' => "-3 days", 'Tu' => "-2 days", 'We'=> "-1 days" ,'Th' => "0 days", 'Fr' => "1 days", 'Sa' => "2 days", 'Su' => "3 days");
		  $boolDate = array(0,0,0,1,1,1,1);
        break;
        case 5:
          $index = array('Mo' => "-4 days", 'Tu' => "-3 days", 'We'=> "-2 days" ,'Th' => "-1 days", 'Fr' => "0 days", 'Sa' => "1 days", 'Su' => "2 days");
		  $boolDate = array(0,0,0,0,1,1,1);
        break;
        case 6:
          $index = array('Mo' => "-5 days", 'Tu' => "-4 days", 'We'=> "-3 days" ,'Th' => "-2 days", 'Fr' => "-1 days", 'Sa' => "0 days", 'Su' => "1 days");
		  $boolDate = array(0,0,0,0,0,1,1);
        break;
    }

	//Stores days from current week's monday to sunday into an array dateData
	date_add($date, date_interval_create_from_date_string($index['Mo'])); $dateData[] = $date; $date=date_create();
	date_add($date, date_interval_create_from_date_string($index['Tu'])); $dateData[] = $date; $date=date_create();
	date_add($date, date_interval_create_from_date_string($index['We'])); $dateData[] = $date; $date=date_create();
	date_add($date, date_interval_create_from_date_string($index['Th'])); $dateData[] = $date; $date=date_create();
	date_add($date, date_interval_create_from_date_string($index['Fr'])); $dateData[] = $date; $date=date_create();
	date_add($date, date_interval_create_from_date_string($index['Sa'])); $dateData[] = $date; $date=date_create();
	date_add($date, date_interval_create_from_date_string($index['Su'])); $dateData[] = $date; $date=date_create();

	//sends the two arrays dateData and boolDate into the session
	session_start();
	$_SESSION['datedata'] = $dateData;
	$_SESSION['booldate'] = $boolDate;
?>
    <div class="box">
        <br><br><br>
        <h1 style="margin:auto;">LOGIN</h1>
        </br>
        <form action="login.php" method="post">
            <div class="userid">
                <label for="userid"><span style="padding-left:25px; color:#20B2AA;"> User Id: </label>
                <input type="text" id="userid" name="username">
            </div>
            <br>
            <div class="password">
                <label for="password" style="color:#20B2AA;">Password: </label>
                <input type="password" id="password" name="pwd">
            </div>
            <br>
            <div class="submit">
                <input type="submit" value="LOGIN" style="font-size:20px;">
                <a href="register.php">REGISTER</a>
            </div>
        </form>
        <?php
        if(isset($_REQUEST['username']) && isset($_REQUEST['pwd']))
        {
            if($userId != NULL && $password != NULL)  // input validation, checking to make sure a blank field was not submitted.
            {
                if (authenticateUser($userId, $password))  // Calling the function to authenticate the user.
				{
					//Sets the start of the current week to be 11:59:59PM of last week's sunday
					$startDate = clone $dateData[0];
					date_modify($startDate, '-1 day');
					date_time_set($startDate, 23,59,59);
					$startDateSTR = date_format($startDate,"YmdHis");

					//Sets the end of the current week to be 11:59:59PM of this week's sunday
					$endDate = clone $dateData[6];
					date_time_set($endDate, 23, 59, 59);
					$endDateSTR = date_format($endDate,"YmdHis");

					$conn = mysqli_connect("localhost","root", "", "CSC350GroupCTerm");
					if(!$conn)
					{
						die("Connection failed: " .mysqli_connect_error());
					}
					else//Query to receive apartment number of the user who has a laundry timeslot this week and the apartment number of the user in general
					{
						$sql = 'select schedule.APT from userinfo join schedule on userinfo.apt = schedule.apt where userid ="'.$userId.'" and usedate > '.$startDateSTR.' and usedate < '.$endDateSTR.';';
						$sql1 = 'select apt from userinfo where userid = "'.$userId.'";';
					}
					$result = mysqli_query($conn, $sql);
					$result1 = mysqli_query($conn,$sql1);
					//Sends the apartment number of the user into the session
					$apt = mysqli_fetch_row($result1);
					$_SESSION['userApt'] = $apt[0];
					//Takes the user to appointment.php if he has a laundry timeslot this week, timeslots.php else
					if (mysqli_num_rows($result) > 0)
					{
						header('Location: appointment.php');
                        exit();
					}
					else
					{
						header('Location: timeslots.php');
                        exit();
					}
				}
                else
                    echo "<br>INVALID USERID OR PASSWORD – TRY AGAIN OR REGISTER.";
            }
            else
                echo "Please enter a valid User ID and Password.";
        }
        ?>
    </div>
<?php
    // authenticateUser checks to see if the database has a row with the userID and password that are sent as parameters.
    // if found, returns true, returns false otherwise.
    function authenticateUser($userId, $password)
    {
		$conn = mysqli_connect("localhost","root", "", "CSC350GroupCTerm");  // connection through mysqli_connect (location, user name, password (if applicable), name of schema)
		if(!$conn)
		{
			die("Connection failed: " .mysqli_connect_error());
		}
		else
			$sql = "select * from userinfo where userId = '". $userId ."' and password = '". $password ."';"; // writing a query into a variable.
			$result = mysqli_query($conn, $sql);

		$rows = mysqli_num_rows($result);

		if( $rows == 1) $match = true;
		else $match = false;

		return $match;
    }


?>
</body>
</html>

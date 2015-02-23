<?php
@session_start();

if(isset($_SESSION['user']) && isset($_SESSION['password'])) {
    header("Location: index.php");
}

require_once 'parts/head.php';


$err='';

//check if postback
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $err = User::testSignUp();
    $db = NULL; //close connection
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($err)) {    
    echo "<div class='container'>Registration was completed! click <a href='login.php'>here</a> to be directed back to login page....</div></body></html>";  
    
} else {
?>
<div class="container">
<h1>Create an account</h1>    

<div id="Sign-Up">
<div class="inline" style="margin-left: 10px">
    <img src="img/sign_up_boy.PNG" height="450" width="450" />
</div>
<div class="inline">
	<fieldset style="width:30%">
		<legend>
			Registration Form
		</legend>
		<form id="signup" method="POST" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>>
			<table>
				
				<tr>
					<td>UserName</td><td>
					<input type="text" name="user" value="<?php
                        if (isset($_POST['user']))
                            echo $_POST['user'];
                    ?>">
					</td>
				</tr>
				<tr>
					<td>Password</td><td>
					<input type="password" name="pass">
					</td>
				</tr>
				<tr>
					<td>Confirm Password </td><td>
					<input type="password" name="cpass">
					</td>
				</tr>
				<tr>

                    <td>First Name</td><td>
                    <input type="text" name="first_name" value="<?php
                        if (isset($_POST['first_name']))
                            echo $_POST['first_name'];
                    ?>">
                    </td>
                </tr>
                <tr>

                    <td>Last Name</td><td>
                    <input type="text" name="last_name" value="<?php
                        if (isset($_POST['last_name']))
                            echo $_POST['last_name'];
                    ?>">
                    </td>
                </tr>
                <tr>
                    <td>Email</td><td>
                    <input type="text" name="email" value="<?php
                        if (isset($_POST['email']))
                            echo $_POST['email'];
                    ?>">
                    </td>
                </tr>
				<tr>
				    <td>
				    <p><img src="lib/imagebuilder.php" border="1">  </p>
                </td>
                    <td>
                <p>Please enter the code shown in the image.<br>
                <input MAXLENGTH=8 SIZE=8 name="userstring" type="text" value="">
                <br>
       
                </p>
                </td>
				</tr>
				<tr>
                    <span class="error"><?php echo $err?></span>
                    </td>
                </tr>
				<tr>
					<td>
					<input id="button" type="submit" name="submit" value="Sign-Up">
					</td>
					<td>
					<input id="button" type="button" onclick="location.href='login.php'" value="Sign In">
					</td>
				</tr>
		</form>
		</table>
	</fieldset>
</div>
</div>

</div>
<?php

require_once 'htmlparts/footer.php';
}
//make sure page is closed
exit;
?>


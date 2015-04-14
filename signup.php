<?php
@session_start();
if (isset($_SESSION['user']) && isset($_SESSION['password'])) {
	header("Location: index.php");
}

include_once 'parts/header.php';

require_once 'lib/password.php';
require_once 'database/User.php';

$err='';

debug($_SESSION['string']);

//check if postback
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $err = User::testSignUp();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($err)) {    
	$result = User::newUser();
	if($result) {
		$message = "Registration was completed! Login Please";
		$url = "index.php";
	} else {
		$message = "Error in signup!";
		$url = "signup.php";
	}
	echo "<script> alert('$message'); window.location.href='$url';</script>";
} else {
?>
<body>

    <div id="wrapper">

        <!-- Page Content -->
        <div id="page-wrapper">
        
            <div class="container-fluid">
            
            
            
            
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Signup</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
       
                <div class="panel panel-default">
                        <div class="panel-heading">
                            Registration
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" method="POST" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>>
                                    	
                                    	<?php if(!empty($err)) { ?>
                                    	<div class="alert alert-danger" role="alert">
                                    	<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                    		<?php echo $err?>
                                    	</div> 
                                    	<?php }?>
                                    	
                                    	 <div class="form-group">
                                            <label>UserName</label>
                                            <input class="form-control" name="user" value="<?php
                        					if (isset($_POST['user']))
                            				echo $_POST['user'];
                    						?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" class="form-control" name="pass">
                                        </div>
                                        <div class="form-group">
                                            <label>Confirm Password</label>
                                            <input type="password" class="form-control" name="cpass">
                                        </div>
                                        <div class="form-group">
                                            <label>First Name</label>
                                            <input class="form-control" name="first_name" value="<?php
                        					if (isset($_POST['first_name']))
                            				echo $_POST['first_name'];
                    						?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input class="form-control" name="last_name" value="<?php
                        					if (isset($_POST['last_name']))
                            				echo $_POST['last_name'];
                    						?>">
                                        </div>
                                        <div class="form-group">
                                        	<label>Please enter the code shown in the image</label>
                                        	<p><img src="lib/imagebuilder.php" border="1"> </p>
                                        	<input MAXLENGTH=8 SIZE=8 name="userstring" type="text" value="">
                                        </div>
                                        <input type="submit" value="Sign-Up" class="btn-lg btn-block btn btn-warning"/>
                               		<input type="button" value="Login" class="btn-lg btn-block btn btn-success" onclick="location.href = 'login.php';"/>
                                    </form>
                                </div>
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                         
                
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    
<?php 
include_once 'parts/bottom.php';
include_once 'parts/footer.php';
}
?>
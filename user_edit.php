<?php 
include_once 'connection/checkUser.php';

include_once 'database/User.php';
include_once 'database/Database.php';

include_once 'parts/header.php';

$err = '';

//Check if post back
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$err = User::testEdit();
}

if (($_SERVER["REQUEST_METHOD"] == "POST") && empty($err)) {
	User::updateUser($_SESSION["user"], $_POST["first_name"], $_POST["last_name"]);
	header('Location: user_info.php');
} else {
?>

<body>

    <div id="wrapper">
        <?php include_once 'parts/nav.php';?>

        <!-- Page Content -->
        <div id="page-wrapper">
        
            <div class="container-fluid">
            
            
            
            
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">User Info</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
       
                <div class="panel panel-default">
                        <div class="panel-heading">
                            Information
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" method="POST" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>>
                                    	<?php 
                                    	$results = User::getUser($_SESSION['user']);
                                    	?>
                                    	<?php if(!empty($err)) { ?>
                                    	<div class="alert alert-danger" role="alert">
                                    	<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                    		<?php echo $err?>
                                    	</div> 
                                    	<?php }?>
                                    	
                                        <div class="form-group">
                                            <label>Username:</label>
                                            <input class="form-control" type="text" placeholder="<?php echo $results[0]["USERNAME"];?>" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label>First Name</label>
                                            <input class="form-control" name="first_name" type="text" value="<?php echo $results[0]["FIRST_NAME"];?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input class="form-control" name="last_name" type="text" value="<?php echo $results[0]["LAST_NAME"];?>">
                                        </div>

                                        <button type="submit" class="btn btn-default">Save</button>
                                        
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

}?>

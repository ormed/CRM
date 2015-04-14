<!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">CRM System</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">

                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-cogs"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="user_info.php"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        <li><a href="balance.php"><i class="fa fa-line-chart"></i> All Balance</a>
                        <li><a href="date_balance.php"><i class="fa fa-calendar"></i> Balance by Date</a>
                        <li><a href="user_balance.php"><i class="fa fa-usd"></i></i> My Sellings</a>
                        <li class="divider"></li>
                        <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                    	 <li>
                             <a href="index.php">Lobby</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-wrench fa-fw"></i> Menu<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="order.php">Add Order</a>
                                </li>
                                <li>
                                    <a href="edit_order_menu.php">Edit Order</a>
                                </li>
                                <li>
                                    <a href="customer.php">Add/Edit Customer</a>
                                </li>
                                <li>
                                    <a href="product.php">Add/Edit Product</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-table"></i></i> Display<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="all_products.php">Products</a>
                                </li>
                                <li>
                                    <a href="all_orders.php">Orders</a>
                                </li>
                                <li>
                                    <a href="all_invoices.php">Invoices</a>
                                </li>
                                <li>
                                    <a href="all_customers.php">Customers</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-search"></i> Search<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="search_product.php">Search Product</a>
                                </li>
                                <li>
                                    <a href="search_customer.php">Search Customer</a>
                                </li>
                                <li>
                                    <a href="search_order.php">Search Order</a>
                                </li>
                                <li>
                                    <a href="search_invoice.php">Search Invoice</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>
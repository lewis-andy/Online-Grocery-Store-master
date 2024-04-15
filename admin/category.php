<?php
session_start();
if (!isset($_SESSION['ADMIN_ID']) || empty($_SESSION['ADMIN_ID'])) {
    header('Location: login.php');
    exit;
}
require '../dbcon.php';

$result = $conn->query('SELECT * FROM `category` ORDER BY `cid` DESC');
$total_category = $result->num_rows;
?>
<!DOCTYPE html>
<html lang="en">

<head>
<?php
    $title = 'All Category | Admin';
    require 'include/head.php';
?>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
<?php
    require 'include/sidebar.php';
?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
<?php
    require 'include/topbar.php';
?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">All Category (<?=$total_category?>)</h1>
            <a href="category-add.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            	<i class="fa fa-plus-circle"></i> Create New
            </a>
          </div>

          <!-- Content Row -->
            <div class="row">
                <!-- column -->
                <div class="col-12">
                    <div class="form-group m-t-40">
                        <?php
                        if (!isset($_SESSION['msg']) || $_SESSION['msg'] == '') {
                        } else {
                            ?>
				        <div class="alert alert-<?=$_SESSION['msg']['type']?> alert-dismissable">
					        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					        <?=$_SESSION['msg']['msg']?>
				        </div>
                        <?php
                            $_SESSION['msg'] = '';
                            unset($_SESSION['msg']);
                        }
                        ?>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title"> </h4>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>CID</th>
                                            <th>Name</th>
                                            <th>Parent Category</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if ($total_category) {
                                        while ($row = $result->fetch_assoc()) {
                                            // Ensure the query is properly quoted and escaped to prevent SQL injection
                                            // and syntax errors, especially if parent_id is dynamically generated.
                                            $parent_id = $conn->real_escape_string($row['parent_id']);
                                            $query = "SELECT `name` FROM `category` WHERE `cid` = '{$parent_id}'";
                                            $result1 = $conn->query($query);

                                            // Initialize $categoryName to a default value (e.g., "No Category")
                                            // in case the query returns null or fails.
                                            $categoryName = "No Category";

                                            if ($result1) {
                                                $category = $result1->fetch_assoc();
                                                if ($category) {
                                                    $categoryName = $category['name'];
                                                } else {
                                                    // Optionally handle the case where no matching category is found
                                                    // This is where you might log an error or take corrective action
                                                }
                                            } else {
                                                // Error handling: log or output the error
                                                error_log("SQL Error: " . $conn->error . " in query " . $query);
                                                // Optionally output an error message or take other corrective action
                                            }
                                            ?>
                                            <tr>
                                                <td><?=htmlspecialchars($row['cid'], ENT_QUOTES, 'UTF-8')?></td>
                                                <td><?=htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8')?></td>
                                                <td><?=htmlspecialchars($categoryName, ENT_QUOTES, 'UTF-8')?></td>
                                            <td class="text-nowrap">
                                                <a href="category-edit.php?id=<?=$row['cid']?>" class="btn btn-outline-info">
                                                	<i class="fa fa-close text-info"></i> Edit
                                                </a>
                                                <a href="include/category-delete.php?id=<?=$row['cid']?>" class="btn btn-outline-danger">
                                                	<i class="fa fa-close text-danger"></i> Remove
                                                </a>
                                            </td>
                                        </tr>
										<?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- column -->
            </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Your Website 2020</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

<?php
    require 'include/javascript.php';
?>

  <!-- Page level custom scripts -->
  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>

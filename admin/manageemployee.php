<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    // code for Inactive  employee    
    if (isset($_GET['inid'])) {
        $id = $_GET['inid'];
        $status = 0;
        $sql = "update tblemployees set Status=:status  WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->execute();
        header('location:manageemployee.php');
    }



    //code for active employee
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $status = 1;
        $sql = "update tblemployees set Status=:status  WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->execute();
        header('location:manageemployee.php');
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>

        <!-- Title -->
        <title>Admin | Manage Employees</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta charset="UTF-8">
        <meta name="description" content="Responsive Admin Dashboard Template" />
        <meta name="keywords" content="admin,dashboard" />
        <meta name="author" content="Steelcoders" />

        <!-- Styles -->
        <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css" />
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="../assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
        <link href="../assets/plugins/datatables/css/jquery.dataTables.min.css" rel="stylesheet">


        <!-- Theme Styles -->
        <link href="../assets/css/alpha.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/custom.css" rel="stylesheet" type="text/css" />
    </head>

    <body>
        <?php include('includes/header.php'); ?>
        <?php include('includes/sidebar.php'); ?>
        <main class="mn-inner">
            <div class="row">
                <div class="col s12">
                    <div class="page-title">Manage EmployesWWW</div>
                </div>
                <div class="col s12 m12 l12">
                    <div class="card">
                        <div class="card-content">
                            <span class="card-title">Employees Info</span>
                            <?php if ($msg) { ?><div class="succWrap"><strong>SUCCESS</strong> : <?php echo htmlentities($msg); ?> </div><?php } ?>
                            <table id="example" class="display responsive-table ">
                                <thead>
                                    <tr>
                                        <th class="center">No</th>
                                        <th class="center">รหัสพนักงาน</th>
                                        <th class="center">ชื่อ นามสกุล</th>
                                        <th class="center">แผนก</th>
                                        <th class="center">สถานะ</th>
                                        <th class="center">วันเริ่มงาน</th>
                                        <th class="center">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sql = "SELECT EmpId,FirstName,LastName,Department,Status,HireDate,id from  tblemployees";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $result) {               ?>
                                            <tr>
                                                <td> <?php echo htmlentities($cnt); ?></td>
                                                <td class="center"><?php echo htmlentities($result->EmpId); ?></td>
                                                <td><?php echo htmlentities($result->FirstName); ?>&nbsp;<?php echo htmlentities($result->LastName); ?></td>
                                                <td class="center">
                                                    <?php
                                                    $id = $result->Department;
                                                    $sql = "SELECT DepartmentName FROM tbldepartments WHERE id = :id";
                                                    $query = $dbh->prepare($sql);
                                                    $query->bindParam(':id', $id, PDO::PARAM_INT);
                                                    $query->execute();
                                                    $department = $query->fetch(PDO::FETCH_OBJ);
                                                    echo htmlentities($department->DepartmentName ?? 'N/A');
                                                    ?>
                                                </td>
                                                <td class="center"><?php $stats = $result->Status;
                                                                    if ($stats) {
                                                                    ?>
                                                        <a class="waves-effect waves-green btn-flat m-b-xs">Active</a>
                                                    <?php } else { ?>
                                                        <a class="waves-effect waves-red btn-flat m-b-xs">Inactive</a>
                                                    <?php } ?>
                                                </td>
                                                <td class="center"><?php echo htmlentities($result->HireDate); ?></td>
                                                <td class="center">
                                                    <a href="editemployee.php?empid=<?php echo htmlentities($result->id); ?>"><i class="material-icons">mode_edit</i></a>
                                                    <?php if ($result->Status == 1) { ?>
                                                        <a href="manageemployee.php?inid=<?php echo htmlentities($result->id); ?>" onclick="return confirm('Are you sure you want to inactive this Employe?');"" > <i class=" material-icons" title="Inactive">clear</i>
                                                        <?php } else { ?>

                                                            <a href="manageemployee.php?id=<?php echo htmlentities($result->id); ?>" onclick="return confirm('Are you sure you want to active this employee?');""><i class=" material-icons" title="Active">done</i>
                                                            <?php } ?>
                                                </td>
                                            </tr>
                                    <?php $cnt++;
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        </div>
        <div class="left-sidebar-hover"></div>

        <!-- Javascripts -->
        <script src="../assets/plugins/jquery/jquery-2.2.0.min.js"></script>
        <script src="../assets/plugins/materialize/js/materialize.min.js"></script>
        <script src="../assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
        <script src="../assets/plugins/jquery-blockui/jquery.blockui.js"></script>
        <script src="../assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
        <script src="../assets/js/alpha.min.js"></script>
        <script src="../assets/js/pages/table-data.js"></script>

    </body>

    </html>
<?php } ?>
<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['emplogin']) == 0) {
    header('location:index.php');
} else {

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>

        <!-- Title -->
        <title>Employee | Leave History</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta charset="UTF-8">

        <!-- Styles -->
        <link type="text/css" rel="stylesheet" href="assets/plugins/materialize/css/materialize.min.css" />
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
        <link href="assets/plugins/datatables/css/jquery.dataTables.min.css" rel="stylesheet">


        <!-- Theme Styles -->
        <link href="assets/css/alpha.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/custom.css" rel="stylesheet" type="text/css" />
    </head>

    <body>
        <?php include('includes/header.php'); ?>

        <?php include('includes/sidebar.php'); ?>
        <main class="mn-inner">
            <div class="row">
                <div class="col s12">
                    <div class="page-title">ประวัติการลา</div>
                </div>

                <div class="col s12 m12 l12">
                    <div class="card">
                        <div class="card-content">
                            <span class="card-title">ประวัติการลา</span>
                            <?php if ($msg) { ?><div class="succWrap"><strong>SUCCESS</strong> : <?php echo htmlentities($msg); ?> </div><?php } ?>
                            <table id="example" class="display responsive-table ">
                                <thead>
                                    <tr>
                                        <th class="center">#</th>
                                        <th class="center" width="120">ประเภทการลา</th>
                                        <th class="center">วันที่</th>
                                        <th class="center">ถึง</th>
                                        <th class="center" width="130">วันที่สร้างข้อมูล</th>
                                        <th class="center">สถานะ</th>
                                        <th class="center">รายละเอียด</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $eid = $_SESSION['eid'];
                                    $sql = "SELECT tblleaves.id as lid ,LeaveType,ToDate,FromDate,Description,PostingDate,AdminRemarkDate,AdminRemark,Status from tblleaves where empid=:eid";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $result) {               ?>
                                            <tr>
                                                <td class="center"> <?php echo htmlentities($cnt); ?></td>
                                                <td class="center"><?php echo htmlentities($result->LeaveType); ?></td>
                                                <td class="center"><?php echo htmlentities($result->FromDate); ?></td>
                                                <td class="center"><?php echo htmlentities($result->ToDate); ?></td>
                                                <td class="center"><?php echo htmlentities($result->PostingDate); ?></td>

                                                <td class="center"><?php $stats = $result->Status;
                                                    if ($stats == 1) {
                                                    ?>
                                                        <span style="color: green">อนุมัติ</span>
                                                    <?php }
                                                    if ($stats == 2) { ?>
                                                        <span style="color: red">ไม่อนุมัติ</span>
                                                    <?php }
                                                    if ($stats == 0) { ?>
                                                        <span style="color: blue">อยู่ระหว่างพิจารณา</span>
                                                    <?php } ?>

                                                </td>
                                                <td class="center">
                                                    <a href="leave-details.php?leaveid=<?php echo htmlentities($result->lid); ?>" class="waves-effect waves-light btn blue m-b-xs"> คลิ๊กดูรายละเอียด</a>
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
        <script src="assets/plugins/jquery/jquery-2.2.0.min.js"></script>
        <script src="assets/plugins/materialize/js/materialize.min.js"></script>
        <script src="assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
        <script src="assets/plugins/jquery-blockui/jquery.blockui.js"></script>
        <script src="assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
        <script src="assets/js/alpha.min.js"></script>
        <script src="assets/js/pages/table-data.js"></script>
        <script src="assets/js/pages/notification.js"></script>

    </body>

    </html>
<?php } ?>
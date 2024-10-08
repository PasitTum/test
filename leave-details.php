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
        <title>Employee | รายละเอียดการลา </title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta charset="UTF-8">

        <!-- Styles -->
        <link type="text/css" rel="stylesheet" href="assets/plugins/materialize/css/materialize.min.css" />
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
        <link href="assets/plugins/datatables/css/jquery.dataTables.min.css" rel="stylesheet">

        <link href="assets/plugins/google-code-prettify/prettify.css" rel="stylesheet" type="text/css" />
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
                    <div class="page-title" style="font-size:24px;">รายละเอียดการลา</div>
                </div>

                <div class="col s12 m12 l12">
                    <div class="card">
                        <div class="card-content">
                            <span class="card-title">รายละเอียดการลา</span>
                            <?php if ($msg) { ?><div class="succWrap"><strong>บันทึกสำเร็จ</strong> :
                                    <?php echo htmlentities($msg); ?> </div><?php } ?>
                            <table id="example" class="display responsive-table ">
                                <tbody>
                                    <?php
                                    $lid = intval($_GET['leaveid']);
                                    $sql = "SELECT tblleaves.id as lid,tblemployees.FirstName,tblemployees.LastName,tblemployees.EmpId,tblemployees.id,tblemployees.Gender,tblemployees.Phonenumber,tblemployees.EmailId,tblleaves.LeaveType,tblleaves.ToDate,tblleaves.FromDate,tblleaves.Description,tblleaves.PostingDate,tblleaves.Status,tblleaves.AdminRemark,tblleaves.AdminRemarkDate from tblleaves join tblemployees on tblleaves.empid=tblemployees.id where tblleaves.id=:lid";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':lid', $lid, PDO::PARAM_STR);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $result) {
                                    ?>
                                        <tr>
                                            <td style="font-size:16px;">
                                                <b>ชื่อ-นามสกุล พนักงาน :</b>
                                            </td>
                                            <td>
                                                <?php echo htmlentities($result->FirstName . " " . $result->LastName); ?>
                                            </td>
                                            <td style="font-size:16px;">
                                                <b>รหัสพนักงาน :</b>
                                            </td>
                                            <td>
                                                <?php echo htmlentities($result->EmpId); ?>
                                            </td>
                                            <td style="font-size:16px;">
                                                <b>เพศ :</b>
                                            </td>
                                            <td>
                                                <?php echo htmlentities($result->Gender); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:16px;">
                                                <b>อีเมล :</b>
                                            </td>
                                            <td>
                                                <?php echo htmlentities($result->EmailId); ?>
                                            </td>
                                            <td style="font-size:16px;">
                                                <b>เบอร์โทร :</b>
                                            </td>
                                            <td>
                                                <?php echo htmlentities($result->Phonenumber); ?>
                                            </td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:16px;">
                                                <b>ประเภทการลา :</b>
                                            </td>
                                            <td>
                                            <?php 
                                            $leaveTypeId = $result->LeaveType;
                                            $sql = "SELECT LeaveType FROM tblleavetype WHERE id = :id";
                                            $query = $dbh->prepare($sql);
                                            $query->bindParam(':id', $leaveTypeId, PDO::PARAM_INT);
                                            $query->execute();
                                            $leaveTypeResult = $query->fetch(PDO::FETCH_OBJ);
                                            echo htmlentities($leaveTypeResult->LeaveType);
                                            ?>
                                            </td>
                                            <td style="font-size:16px;"><b>วันที่ลา :</b></td>
                                            <td>
                                                From <?php echo htmlentities($result->FromDate); ?> to <?php echo htmlentities($result->ToDate); ?>
                                            </td>
                                            <td style="font-size:16px;">
                                                <b>วันที่กรอกข้อมูล</b>
                                            </td>
                                            <td>
                                                <?php echo htmlentities($result->PostingDate); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:16px;"><b>รายละเอียดการลา : </b></td>
                                            <td colspan="5"><?php echo htmlentities($result->Description); ?></td>

                                        </tr>
                                        <tr>
                                            <td style="font-size:16px;"><b>สถานะการลา :</b></td>
                                            <td colspan="5"><?php $stats = $result->Status;
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
                                        </tr>
                                        <tr>
                                            <td style="font-size:16px;"><b>ความคิดเป็นจากผู้อนุมัติ: </b></td>
                                            <td colspan="5">
                                                <?php
                                                    if ($result->AdminRemark == "") {
                                                        echo "waiting for Approval";
                                                    } else {
                                                        echo htmlentities($result->AdminRemark);
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:16px;"><b>วันที่อนุมัติ/ไม่อนุมัติ : </b></td>
                                            <td colspan="5">
                                                <?php
                                                    if ($result->AdminRemarkDate == "") {
                                                        echo "";
                                                    } else {
                                                        echo htmlentities($result->AdminRemarkDate);
                                                    }
                                                ?>
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

        <div class="left-sidebar-hover"></div>

        <!-- Javascripts -->
        <script src="assets/plugins/jquery/jquery-2.2.0.min.js"></script>
        <script src="assets/plugins/materialize/js/materialize.min.js"></script>
        <script src="assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
        <script src="assets/plugins/jquery-blockui/jquery.blockui.js"></script>
        <script src="assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
        <script src="assets/js/alpha.min.js"></script>
        <script src="assets/js/pages/table-data.js"></script>
        <script src="assets/js/pages/ui-modals.js"></script>
        <script src="assets/plugins/google-code-prettify/prettify.js"></script>
        <script src="assets/js/pages/notification.js"></script>

    </body>

    </html>
<?php } ?>
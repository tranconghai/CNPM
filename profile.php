<?php
include('assets/header.php');
// Nếu không đăng nhập sẽ hủy phiên làm việc và trở về trang chủ
if (!isset($_SESSION['login'])) {
    session_unset();
    session_destroy();
    header('location:index.php');
}
?>

<main class="background-2">
    <div class="myform container">
        <div>
            <div class="card p-3 py-4">
                <div class="text-center">
                    <img src="assets/images/avatar.png" width="100">
                </div>
                <div class="mt-3">
                    <?php
                    // Hiển thị với khách hàng
                    if($_SESSION['customer']) {        
                        $user_id = $_SESSION['user_id'];
                        $sql = "SELECT * FROM customer WHERE customer_id = '$user_id'";
                        $query = mysqli_query($con, $sql);
                        $row = mysqli_fetch_assoc($query); ?>
                            <h5 class="mt-2 mb-0 text-center mb-4"><?php echo $row['fullname'] ?></h5>
                            <div class="row">
                                <div class="col fw-medium">ID:</div>
                                <div class="col"><?php echo $row['customer_id'] ?></div>
                            </div>
                            <div class="row">
                                <div class="col fw-medium">Account:</div>
                                <div class="col"><?php echo $row['account'] ?></div>
                            </div>
                            <div class="row">
                                <div class="col fw-medium">Phone number:</div>
                                <div class="col"><?php echo $row['phone']?></div>
                            </div>
                            <div class="row">
                                <div class="col fw-medium">Address:</div>
                                <div class="col"><?php echo $row['address']?></div>
                            </div>
                    <?php }; 
                    // Hiển thị thông tin với admin
                    if($_SESSION['admin']) {
                        $user_id = $_SESSION['user_id'];
                        $sql = "SELECT * FROM admin WHERE admin_id = '$user_id'";
                        $query = mysqli_query($con, $sql);
                        $row = mysqli_fetch_assoc($query); ?>
                            <h5 class="mt-2 mb-0 text-center mb-4">ADMIN #<?php echo $row['admin_id'] ?></h5>
                            <div class="row">
                                <div class="col fw-medium">ID:</div>
                                <div class="col"><?php echo $row['admin_id'] ?></div>
                            </div>
                            <div class="row">
                                <div class="col fw-medium">Account:</div>
                                <div class="col"><?php echo $row['account'] ?></div>
                            </div>
                    <?php };  ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
include('assets/footer.php');
?>

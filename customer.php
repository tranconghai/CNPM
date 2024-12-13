<?php
include('assets/header.php');

if (!$_SESSION['admin']) {
    session_unset();
    session_destroy();
    header('location:index.php');
}
?>

    <div class="container min-vh-100">
        <!-- Tiêu đề -->
        <div class="mb-3 mt-3">
            <h3 class="fw-normal mb-0 text-black">Khách hàng</h3>
        </div>

        <!-- Thông tin sản phẩm khách hàng đã đặt -->
        
        <table class="table table-bordered table-striped text-center">
            <thead>
                <tr class="table-dark">
                    <th scope="col">ID</th>
                    <th scope="col">Tên tài khoản</th>
                    <th scope="col">Tên khách hàng</th>
                    <th scope="col">Địa chỉ</th>
                    <th scope="col">SĐT</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $customer_id = $_SESSION['user_id'];
            $sql = "SELECT *
                    FROM customer ";
            $query = mysqli_query($con, $sql);
            if ($query) {
                while ($row = mysqli_fetch_assoc($query)) { ?>
                <tr class="align-middle">
                    <th scope="row"><?php echo $row['customer_id'] ?></th>
                    <td><?php echo $row['account'] ?></td>
                    <td><?php echo $row['fullname'] ?></td>
                    <td><?php echo $row['address']; ?></td>
                    <td><?php echo $row['phone'] ?></td>
                </tr>
                <?php }
            } ?>
            </tbody>
        </table>
            
    </div>
<?php
include('assets/footer.php');
?>
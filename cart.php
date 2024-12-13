<?php
include('assets/header.php');

// Kiểm tra xem khách hàng đã đăng nhập chưa
if (isset($_SESSION['user_id'])) {
    // Nếu có yêu cầu xóa sản phẩm
    if (isset($_GET['delete'])) {
        $bill_id = $_GET['delete'];  // Lấy ID của đơn hàng cần xóa

        // Kiểm tra nếu bill_id không trống hoặc không phải là một số hợp lệ
        if (is_numeric($bill_id) && $bill_id > 0) {
            // Viết câu lệnh SQL để xóa sản phẩm khỏi giỏ hàng
            $delete_sql = "DELETE FROM bill WHERE id = ?"; // Sử dụng cột `id` thay vì `bill_id`

            // Sử dụng prepared statement để tránh SQL Injection
            $stmt = mysqli_prepare($con, $delete_sql);
            mysqli_stmt_bind_param($stmt, 'i', $bill_id);  // Liên kết tham số với biến

            // Thực thi câu lệnh SQL
            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('Sản phẩm đã được xóa khỏi giỏ hàng!'); window.location='cart.php';</script>";
            } else {
                echo "<script>alert('Lỗi khi xóa sản phẩm!'); window.location='cart.php';</script>";
            }
            exit();
        } else {
            echo "<script>alert('ID sản phẩm không hợp lệ!'); window.location='cart.php';</script>";
            exit();
        }
    }
} else {
    // Chuyển hướng người dùng không phải là khách hàng đến trang đăng nhập
    header('Location: login.php');
    exit();
}

// Khởi tạo biến tổng số tiền
$total_amount = 0;

?>

<!-- Nội dung của trang giỏ hàng -->
<div class="container cart-container">
    <div class="row d-flex justify-content-center">
        <div class="col-10">
            <!-- Tiêu đề -->
            <div class="mb-3 mt-3">
                <h3 class="cart-header">Giỏ Hàng</h3>
            </div>

            <!-- Thông tin sản phẩm đã đặt -->
            <?php
            $customer_id = $_SESSION['user_id'];
            $sql = "SELECT bill.id, product.image as image, product.name as name, bill.amount as amount, product.price as price, bill.delivery_time as delivery_time 
                    FROM bill 
                    INNER JOIN product ON bill.product_id = product.product_id 
                    WHERE customer_id = $customer_id 
                    ORDER BY bill.delivery_time desc";

            $query = mysqli_query($con, $sql);
            if ($query && mysqli_num_rows($query) > 0) {
                // Có sản phẩm trong giỏ hàng
                while ($row = mysqli_fetch_assoc($query)) {
                    $item_total = $row['amount'] * $row['price']; // Tính tổng cho từng sản phẩm
                    $total_amount += $item_total; // Cộng dồn vào tổng giỏ hàng
            ?>
            <div class="cart-item card">
                <div class="card-body p-4">
                    <div class="row d-flex justify-content-between align-items-center">
                        <div class="col-md-2 col-lg-2 col-xl-2">
                            <img src="./assets/images/products/<?php echo $row['image']; ?>" class="product-image rounded-3" alt="Ảnh sản phẩm">
                        </div>
                        <div class="col-md-3 col-lg-3 col-xl-3">
                            <p class="product-name"><?php echo $row['name'] ?></p>
                        </div>
                        
                        <div class="col-md-3 col-lg-3 col-xl-4">
                            <p class="product-details">Số lượng: <span class="text-muted"><?php echo $row['amount'] ?></span></p>
                            <p class="product-details">Thời gian nhận hàng: <?php echo $row['delivery_time'] ?></p>
                        </div>
                        <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                            <p class="cart-total">Tổng cộng:</p>
                            <p class="cart-total"><?php echo number_format($item_total, 0, ',', '.') ?> đ</p>
                            <!-- Nút xóa sản phẩm -->
                            <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm mt-2">Xóa</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                }
            } else {
                // Nếu giỏ hàng trống, hiển thị thông báo
                echo "<p>Giỏ hàng của bạn hiện tại không có sản phẩm.</p>";
            }
            ?>

            <!-- Tổng tiền chỉ hiển thị nếu có sản phẩm trong giỏ hàng -->
            <?php if ($total_amount > 0): ?>
            <div class="cart-total-container">
                <p>Tổng giỏ hàng: <?php echo number_format($total_amount, 0, ',', '.') ?> đ</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
include('assets/footer.php');
?>

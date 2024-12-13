<?php
include('assets/header.php');

// Kiểm tra nếu admin đã đăng nhập
if (isset($_SESSION['admin'])) {
    // Nếu có yêu cầu xóa sản phẩm
    if (isset($_GET['delete_product_id']) && is_numeric($_GET['delete_product_id'])) {
        $product_id_to_delete = $_GET['delete_product_id'];

        // Xóa sản phẩm khỏi cơ sở dữ liệu
        $delete_sql = "DELETE FROM product WHERE product_id = ?";
        $stmt = mysqli_prepare($con, $delete_sql);
        mysqli_stmt_bind_param($stmt, 'i', $product_id_to_delete);
        
        if (mysqli_stmt_execute($stmt)) {
            // Thông báo xóa thành công và chuyển hướng ngay lập tức
            echo "<script>alert('Sản phẩm đã được xóa!'); window.location.href = 'products.php';</script>";
        } else {
            // Thông báo lỗi nếu có và chuyển hướng
            echo "<script>alert('Có lỗi xảy ra khi xóa sản phẩm.'); window.location.href = 'products.php';</script>";
        }
        exit(); // Dừng script để tránh lỗi sau khi chuyển hướng
    }
} else {
    // Nếu không phải admin, chuyển hướng về trang đăng nhập hoặc trang chủ
    header('Location: login.php');
    exit();
}

// Hàm định dạng giá
function format_price($price) {
    return number_format($price, 0, ',', '.') . ' đ';
}


// Kiểm tra khi form được gửi
if (isset($_POST['submit'])) {
    $product_id = $_POST['product_id'];
    $customer_id = $_SESSION['user_id'];
    $amount = $_POST['amount'];
    $delivery_time = $_POST['delivery_time'];
    // $admin_id = $_SESSION['user_id'];  // Giả sử 'user_id' là ID của admin trong session
    // thêm đơn hàng vào cơ sở dữ liệu
    $sql_order = "INSERT INTO bill(customer_id, product_id, amount, delivery_time) 
    VALUES ('$customer_id', '$product_id', '$amount', '$delivery_time')";
    if (mysqli_query($con, $sql_order)) {
    echo "<script>alert('Đơn hàng đã được xác nhận!');</script>";
    } else {
    echo "<script>alert('Có lỗi xảy ra khi tạo đơn hàng.');</script>";
    }
}

?>

<div class="container min-vh-100 d-flex justify-content-center">
    <div class="product-container row">
        <!-- Hiển thị các sản phẩm được chủ cửa hàng đăng tải -->
        <?php
        $sql = "SELECT * FROM product";
        $query = mysqli_query($con, $sql);
        
        if ($query) {
            while ($row = mysqli_fetch_assoc($query)) {
                // Hiển thị sản phẩm với tư cách khách hàng
                if (isset($_SESSION['customer']) && $_SESSION['customer']) {
                    echo '
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mb-4">
                        <div class="card product-card">
                            <img src="./assets/images/products/' . $row['image'] . '" class="card-img-top" alt="Ảnh sản phẩm">
                            <div class="card-body">
                                <h5 class="card-title text-center">' . $row['name'] . '</h5>
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <span class="fw-bold">Giá:</span>
                                        <span class="ms-1">' . format_price($row['price']) . '</span>
                                    </div>
                                </div>
                                <form method="post">
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-bold">Số lượng:</span>
                                        <input type="hidden" name="product_id" value="' . $row['product_id'] . '">
                                        <input type="number" name="amount" min="1" required class="form-control-sm w-50">
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-bold">Ngày nhận:</span>
                                        <input type="date" name="delivery_time" required class="form-control-sm w-50">
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-2" name="submit">Xác nhận</button>     
                                </form>
                            </div>
                        </div>
                    </div>';
                }
                // Hiển thị sản phẩm cho người dùng không phải khách hàng hoặc admin
                else {
                    echo '
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mb-4">
                        <div class="card product-card">
                            <img src="./assets/images/products/' . $row['image'] . '" class="card-img-top" alt="Ảnh sản phẩm">
                            <div class="card-body">
                                <h5 class="card-title text-center">' . $row['name'] . '</h5>
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <span class="fw-bold">Giá:</span>
                                        <span class="ms-1">' . format_price($row['price']) . '</span>
                                    </div>
                                </div>';

   // Hiển thị nút xóa nếu là admin và người dùng đã đăng nhập
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true && isset($_SESSION['login']) && $_SESSION['login'] === true) {
    echo '
    <div class="d-flex justify-content-center">
        <a href="?delete_product_id=' . $row['product_id'] . '" class="btn btn-danger btn-sm mt-2">Xóa sản phẩm</a>
    </div>';
}



                    echo '
                            </div>
                        </div>
                    </div>';
                }
            }
        }
        ?>
    </div>
</div>

<?php
include('assets/footer.php');
?>

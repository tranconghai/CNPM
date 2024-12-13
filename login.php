<?php
// Đảm bảo rằng session luôn được khởi tạo
include('assets/header.php');

if (isset($_POST['login'])) {
    $account = $_POST['account'];
    $password = $_POST['password'];

    // Kiểm tra tài khoản người dùng (customer)
    $sql_customer = "SELECT * FROM customer WHERE account = ?";
    $stmt_customer = $con->prepare($sql_customer);
    $stmt_customer->bind_param("s", $account);
    $stmt_customer->execute();
    $query_customer = $stmt_customer->get_result();
    $result_customer = $query_customer->fetch_assoc();

    // Kiểm tra tài khoản admin
    $sql_admin = "SELECT * FROM admin WHERE account = ?";
    $stmt_admin = $con->prepare($sql_admin);
    $stmt_admin->bind_param("s", $account);
    $stmt_admin->execute();
    $query_admin = $stmt_admin->get_result();
    $result_admin = $query_admin->fetch_assoc();

    if ($result_customer) {
        // Nếu là user, kiểm tra mật khẩu đã mã hóa
        if (password_verify($password, $result_customer['password'])) {
            $_SESSION['user_id'] = $result_customer['customer_id'];
            $_SESSION['login'] = true;
            $_SESSION['customer'] = true;  // Lưu session customer
            header('location:index.php');
        } else {
            $_SESSION['error'] = '<p class="fw-semibold text-bg-danger p-2 text-center">Tài khoản hoặc mật khẩu không đúng</p>';
        }
    } elseif ($result_admin) {
        // Nếu là admin, kiểm tra mật khẩu đã mã hóa
        if (password_verify($password, $result_admin['password'])) {
            $_SESSION['user_id'] = $result_admin['admin_id'];
            $_SESSION['login'] = true;
            $_SESSION['admin'] = true;  // Lưu session admin
            header('location:index.php');
        } else {
            $_SESSION['error'] = '<p class="fw-semibold text-bg-danger p-2 text-center">Tài khoản hoặc mật khẩu không đúng</p>';
        }
    } else {
        $_SESSION['error'] = '<p class="fw-semibold text-bg-danger p-2 text-center">Tài khoản hoặc mật khẩu không đúng</p>';
    }
}
?>

<main class="background-1">
    <div class="container-md myform">
        <form method="post">
            <?php
            if (isset($_SESSION['error'])) {
                echo $_SESSION['error'];
                unset($_SESSION['error']);
            }
            ?>
            <div class="mb-3">
                <label for="account" class="form-label">Tên tài khoản</label>
                <input name="account" type="text" pattern="[a-zA-Z0-9]+" required class="form-control" id="account">
                <small class="fst-italic">Chỉ nhập các chữ số và chữ cái</small>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input name="password" type="password" id="password" pattern="[a-zA-Z0-9]+" class="form-control">
                <span class="show-hide" onclick="showOrHidePassword();"><i class="fa-solid fa-eye f-me"></i></span>
                <small class="fst-italic">Chỉ nhập các chữ số và chữ cái</small>
            </div>
            <div class="row">
                <button type="submit" class="btn col m-1 text-white" style="background-color: #ffbd03;" name="login">Đăng nhập</button>
            </div>
        </form>
        <div class="pt-2 text-center">
            <p class="d-inline-block pe-2">Chưa có tài khoản?</p>
            <a href="register.php" class="text-decoration-none text-white btn btn-success">Đăng ký</a>
        </div>
    </div>
</main>

<script>
    // Ẩn/hiện mật khẩu
    function showOrHidePassword() {
        let x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>

<?php
include('assets/footer.php');
?>

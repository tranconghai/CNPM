<?php
include('assets/header.php');

if (isset($_POST['register'])) {
    $account = $_POST['account'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $fullname = $_POST['fullname'];
    $address = $_POST['address'];

    // Kiểm tra mật khẩu có đủ mạnh không (ít nhất 8 ký tự, bao gồm chữ hoa và số)
    if (strlen($password) < 8 || !preg_match("/[A-Z]/", $password) || !preg_match("/[0-9]/", $password)) {
        $_SESSION['error'] = '<p class="fw-semibold text-bg-danger p-2 text-center" style="color: red; margin-left: auto;">Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa và số!</p>';
    } else {
        // Mã hóa mật khẩu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Kiểm tra xem tài khoản đã tồn tại trong cơ sở dữ liệu chưa
        $stmt = $con->prepare("SELECT * FROM customer WHERE account = ?");
        $stmt->bind_param("s", $account);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION['error'] = '<p class="fw-semibold text-bg-danger p-2 text-center" style="color: red; margin-left: auto;">Tên người dùng đã được sử dụng!</p>';
        } else {
            // Tài khoản chưa tồn tại, thực hiện đăng ký
            $stmt = $con->prepare("INSERT INTO customer (account, password, fullname, address, phone) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $account, $hashed_password, $fullname, $address, $phone);
            
            try {
                $stmt->execute();
                $_SESSION['success'] = '<p class="fw-semibold text-bg-success p-2 text-center" style="color: #17E617; margin-left: auto;">Đăng ký thành công!</p>';
                // Redirect to login page if needed (optional)
                // header('Location: login.php');
            } catch (mysqli_sql_exception) {
                $_SESSION['error'] = '<p class="fw-semibold text-bg-danger p-2 text-center" style="color: red; margin-left: auto;">Đã có lỗi xảy ra, vui lòng thử lại!</p>';
            }
        }
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
            if (isset($_SESSION['success'])) {
                echo $_SESSION['success'];
                unset($_SESSION['success']);
            }
            ?>
            <div class="mb-3">
                <label for="account" class="form-label">Tên tài khoản</label>
                <input type="text" name="account" pattern="[a-zA-Z0-9]+" maxlength="32" required class="form-control" id="account">
                <small class="fst-italic">Chỉ nhập các chữ số và chữ cái (Tối đa 32 ký tự)</small>
            </div>
            <div class="mb-3 ">
                <label for="password" class="form-label">Mật khẩu</label>
                <input type="password" name="password" id="password" pattern="[A-Za-z0-9]+" class="form-control" required>
                <span class="show-hide" onclick="showOrHidePassword();"><i class="fa-solid fa-eye f-me"></i></span>
                <small class="fst-italic">Chỉ nhập các chữ số và chữ cái</small>
            </div>
            <div class="mb-3">
                <label for="fullname" class="form-label">Tên đầy đủ</label>
                <input type="text" name="fullname" required class="form-control" id="fullname">
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Số điện thoại</label>
                <input type="tel" name="phone" pattern="[0-9]{10}" required class="form-control" id="phone" placeholder="0123456789">
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Địa chỉ</label>
                <textarea name="address" required class="form-control" id="address"></textarea>
            </div>
            <button type="submit" class="btn btn-primary w-100" name="register">Đăng ký</button>
            
        </form>
        <div class="pt-2 text-center">
            <p class="d-inline-block pe-2">Đã có tài khoản?</p>
            <a href="login.php" class="text-decoration-none text-white btn btn-success">Đăng nhập</a>
        </div>
    </div>
</main>

<script>
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

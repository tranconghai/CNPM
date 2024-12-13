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
    <div class="mb-3 mt-3 d-flex justify-content-between">
        <h3 class="fw-normal mb-0 text-black d-inline">Đơn hàng</h3>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#favoriteProductModal">
            Mặt hàng yêu thích
        </button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="favoriteProductModal" tabindex="-1" aria-labelledby="favoriteProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="favoriteProductModalLabel">Mặt hàng yêu thích</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <?php
                $sql = "SELECT MAX(temp.amount) as amount
                FROM 	(SELECT name,image, SUM(amount) AS amount,delivery_time
                        FROM bill INNER JOIN product ON bill.product_id = product.product_id
                        WHERE delivery_time >= CURRENT_DATE()
                        GROUP BY bill.product_id) temp";
                $query = mysqli_query($con, $sql);
                $row = mysqli_fetch_assoc($query);
                if($row['amount'] ){
                    $max = $row['amount'];
                    $sql = "SELECT temp.amount as amount, name,image
                            FROM 	(SELECT name,image, SUM(amount) AS amount,delivery_time
                                FROM bill INNER JOIN product ON bill.product_id = product.product_id
                                WHERE delivery_time >= CURRENT_DATE()
                                GROUP BY bill.product_id) temp
                            WHERE amount = $max";
                    $query_2 = mysqli_query($con, $sql);
                    if ($query_2) {
                    while ($row = mysqli_fetch_assoc($query_2)) { ?>
                    <div class="card rounded-3 mb-4">
                    <div class="card-body p-4">
                        <div class="row d-flex justify-content-between align-items-center">
                        <div class="col">
                            <img src="./assets/images/products/<?php echo $row['image']; ?>" class="card-img-top rounded-3" style="height: 5rem; width: 7rem">
                        </div>
                        <div class="col">
                            <p class="lead fw-normal"><?php echo $row['name'] ?></p>
                        </div>
                        
                        <div class="col">
                            <p class="lead fw-normal">Số lượng:<span class=" ms-2 text-muted"><?php echo $row['amount'] ?></span></p>
                        </div>
                        </div>
                    </div>
                    </div>
                    <?php }
                } 
                }?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Thông tin sản phẩm khách hàng đã đặt -->

    <table class="table table-bordered table-striped text-center">
        <thead>
            <tr class="table-dark">
                <th scope="col">#</th>
                <th scope="col">Tên KH</th>
                <th scope="col">Sản phẩm</th>
                <th scope="col">Tổng cộng</th>
                <th scope="col">Liên hệ</th>
                <th scope="col">Ngày nhận</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT bill.id as id,
                            product.name as product_name,
                            bill.amount as amount,
                            product.price as price, 
                            bill.delivery_time as delivery_time,
                            customer.address as address,
                            customer.fullname as fullname,
                            customer.phone as phone

                    FROM ((bill INNER JOIN product ON product.product_id = bill.product_id)
                                INNER JOIN customer ON bill.customer_id = customer.customer_id)
                    WHERE bill.delivery_time >= CURRENT_DATE()
                    ORDER BY bill.delivery_time";
            $query = mysqli_query($con, $sql);
            if ($query) {
                while ($row = mysqli_fetch_assoc($query)) { ?>
                    <tr class="align-middle">
                        <th scope="row"><?php echo $row['id'] ?></th>
                        <td><?php echo $row['fullname'] ?></td>
                        <td><?php echo $row['product_name'] ?></td>
                        <td><span class="fw-semibold">Số lượng:</span> <?php echo $row['amount']; ?> <br>
                            <span class="fw-semibold">Thành tiền:</span> <?php echo $row['amount'] * $row['price'] ?> đồng
                        </td>
                        <td><span class="fw-semibold">Địa chỉ:</span> <?php echo $row['address'] ?><br>
                            <span class="fw-semibold">SĐT:</span> <?php echo $row['phone'] ?>
                        </td>
                        <td><?php echo $row['delivery_time'] ?></td>
                    </tr>
            <?php }
            } ?>
        </tbody>
    </table>

</div>
<?php
include('assets/footer.php');
?>
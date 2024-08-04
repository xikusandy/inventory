<?php
$page_title = 'Add Sale';
require_once('includes/load.php');
// Checking user permission level to view this page
page_require_level(3);

if (isset($_POST['add_sale'])) {
    $req_fields = array('s_id', 'quantity', 'price', 'date', 'phone_number', 'location', 'building_type');
    validate_fields($req_fields);

    if (empty($errors)) {
        $p_id = $db->escape((int)$_POST['s_id']);
        $s_qty = $db->escape((int)$_POST['quantity']);
        $s_price = $db->escape($_POST['price']);
        $s_total = $s_qty * $s_price; // Calculate total based on quantity and price
        $date = $db->escape($_POST['date']);
        $s_date = make_date();
        $phone_number = $db->escape($_POST['phone_number']);
        $location = $db->escape($_POST['location']);
        $building_type = $db->escape($_POST['building_type']);

        $sql = "INSERT INTO sales (";
        $sql .= " product_id, phone_number, quantity, price, location, date, building_type, total";
        $sql .= ") VALUES (";
        $sql .= "'{$p_id}', '{$phone_number}', '{$s_qty}', '{$s_price}', '{$location}', '{$s_date}', '{$building_type}', '{$s_total}'";
        $sql .= ")";

        if ($db->query($sql)) {
            update_product_qty($s_qty, $p_id);
            $session->msg('s', "Sale added.");
            redirect('add_sale.php', false);
        } else {
            $session->msg('d', 'Failed to add sale.');
            redirect('add_sale.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_sale.php', false);
    }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-6">
        <?php echo display_msg($msg); ?>
        <form method="post" action="add_sale.php" autocomplete="off" id="sug-form">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-primary">Find It</button>
                    </span>
                    <input type="text" id="sug_input" class="form-control" name="title" placeholder="Search for product name">
                </div>
                <div id="result" class="list-group"></div>
            </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Sale Edit</span>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="add_sale.php">
                    <table class="table table-bordered">
                        <thead>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th>Date</th>
                            <th>Phone Number</th>
                            <th>Location</th>
                            <th>Building Type</th>
                            <th>Action</th>
                        </thead>
                        <tbody id="product_info">
                            <tr>
                                <td><!-- Replace with input for item/product --></td>
                                <td><input type="text" class="form-control" name="price"></td>
                                <td><input type="text" class="form-control" name="quantity"></td>
                                <td><!-- Display calculated total (read-only) --></td>
                                <td><input type="date" class="form-control" name="date"></td>
                                <td><input type="text" class="form-control" name="phone_number"></td>
                                <td><input type="text" class="form-control" name="location"></td>
                                <td><input type="text" class="form-control" name="building_type"></td>
                                <td><button type="submit" name="add_sale" class="btn btn-primary">Add Sale</button></td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>

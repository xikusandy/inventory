<?php
$page_title = 'Add Customer';
require_once('includes/load.php');
// Check user's permission level to view this page
page_require_level(3); // Adjust permission level as needed

if (isset($_POST['add_customer'])) {
    $req_fields = array('phone_number', 'customer_name', 'email');
    validate_fields($req_fields);

    if (empty($errors)) {
        $phone_number = $db->escape($_POST['phone_number']);
        $customer_name = $db->escape($_POST['customer_name']);
        $email = $db->escape($_POST['email']);

        // Check if the customer already exists
        $existing_customer = find_by_column('customers', 'phone_number', $phone_number);
        if ($existing_customer) {
            $session->msg('d', 'Customer with this phone number already exists.');
            redirect('add_customer.php');
        }

        $query = "INSERT INTO customers (phone_number, customer_name, email)";
        $query .= " VALUES ('{$phone_number}', '{$customer_name}', '{$email}')";

        if ($db->query($query)) {
            $session->msg('s', 'Customer added successfully.');
            redirect('add_customer.php', false); // Redirect to customers list page
        } else {
            $session->msg('d', 'Failed to add customer.');
            redirect('add_customer.php', false); // Redirect back to add customer page
        }
    } else {
        $session->msg('d', $errors);
        redirect('add_customer.php', false); // Redirect back to add customer page
    }
}
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-user"></span>
                    Add New Customer
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="add_customer.php">
                    <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <input type="tel" class="form-control" name="phone_number" required>
                    </div>
                    <div class="form-group">
                        <label for="customer_name">Customer Name</label>
                        <input type="text" class="form-control" name="customer_name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <button type="submit" name="add_customer" class="btn btn-primary">Add Customer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>

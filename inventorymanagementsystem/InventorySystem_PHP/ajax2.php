<?php
require_once('includes/load.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'find') {
    $phone_number = $db->escape($_POST['phone_number']);
    $sql = "SELECT phone_number, customer_name, email FROM customers WHERE phone_number LIKE '%{$phone_number}%'";
    $result = find_by_sql($sql);
    
    if ($result) {
        foreach ($result as $customer) {
            echo "<tr>";
            echo "<td class='text-center'>" . remove_junk($customer['phone_number']) . "</td>";
            echo "<td class='text-center'>" . remove_junk($customer['customer_name']) . "</td>";
            echo "<td class='text-center'>" . remove_junk($customer['email']) . "</td>";
            echo "<td class='text-center'>";
            echo "<div class='btn-group'>";
            echo "<a href='edit_customer.php?phone_number=" . urlencode(remove_junk($customer['phone_number'])) . "' class='btn btn-warning btn-xs' title='Edit' data-toggle='tooltip'>";
            echo "<span class='glyphicon glyphicon-edit'></span>";
            echo "</a>";
            echo "<a href='delete_customer.php?phone_number=" . urlencode(remove_junk($customer['phone_number'])) . "' class='btn btn-danger btn-xs' title='Delete' data-toggle='tooltip'>";
            echo "<span class='glyphicon glyphicon-trash'></span>";
            echo "</a>";
            echo "</div>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4' class='text-center'>No customers found</td></tr>";
    }
}
?>

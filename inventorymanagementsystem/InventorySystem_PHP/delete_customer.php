<?php
require_once('includes/load.php');
// Check user's permission level to view this page
page_require_level(3); // Adjust as necessary

// Ensure phone_number parameter is set and numeric
if (!isset($_GET['phone_number']) || !is_numeric($_GET['phone_number'])) {
    $session->msg("d", "Invalid phone number.");
    redirect('customer_management.php');
}

// Escape and retrieve phone_number from URL
$phone_number = $db->escape($_GET['phone_number']);

// Find customer by phone number
$customer = find_by_column('customers', 'phone_number', $phone_number);

// If customer not found, redirect to customer management page
if (!$customer) {
    $session->msg("d", "Customer not found.");
    redirect('customer_management.php');
}

// Construct SQL query to delete the customer
$sql = "DELETE FROM customers WHERE phone_number = '{$phone_number}'";
$result = $db->query($sql);

// Check if deletion was successful
if ($result && $db->affected_rows() === 1) {
    $session->msg("s", "Customer deleted successfully.");
    redirect('customer_management.php');
} else {
    $session->msg("d", "Failed to delete customer.");
    redirect('customer_management.php');
}
?>

<?php
$page_title = 'Daily Sales';
require_once('includes/load.php');
// Check what level user has permission to view this page
page_require_level(3);

$year  = date('Y');
$month = date('m');
$sales = dailySales($year, $month);

?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-6">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Daily Sales</span>
                </strong>
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th> Product name </th>
                            <th class="text-center" style="width: 15%;"> Quantity sold</th>
                            <th class="text-center" style="width: 15%;"> Total </th>
                            <th class="text-center" style="width: 15%;"> Date </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sales as $index => $sale): ?>
                            <tr>
                                <td class="text-center"><?php echo $index + 1; ?></td>
                                <td><?php echo isset($sale['name']) ? remove_junk($sale['name']) : ''; ?></td>
                                <td class="text-center"><?php echo isset($sale['qty']) ? (int)$sale['qty'] : 0; ?></td>
                                <td class="text-center"><?php echo isset($sale['total_saleing_price']) ? remove_junk($sale['total_saleing_price']) : ''; ?></td>
                                <td class="text-center"><?php echo isset($sale['date']) ? $sale['date'] : ''; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>

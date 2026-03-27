<?php
include('data.inc.php');
include('functions.inc.php');

$subtotal = ($quantity1 * $price1) + ($quantity2 * $price2) + ($quantity3 * $price3) + ($quantity4 * $price4);
$shipping = ($subtotal > 10000) ? 100 : 200;
$grandtotal = $subtotal + $shipping;

$orderStart = 500;
$orderStep = 10;
$orderCount = 5;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CISC3003 Practice 09</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="css/material.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

<div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">

<?php include('header.inc.php'); ?>

<?php include('left.inc.php'); ?>


  <main class="mdl-layout__content">

    <header class="page-content">
      <h2>Order Summaries</h2>
      <p>Examine your customer orders</p>
    </header>

    <section class="mdl-grid">

        <div class="mdl-cell mdl-cell--12-col mdl-grid">
          <div class="mdl-cell mdl-cell--3-col card-lesson mdl-card mdl-shadow--2dp">
            <div class="mdl-card__title" style="background-color: #673AB7; color: #fff;">
              <h2 class="mdl-card__title-text">My Orders</h2>
            </div>
            <div class="mdl-card__supporting-text">
                <ul class="mdl-list">
                    <?php
                    for ($i = 0; $i < $orderCount; $i++) {
                        $orderNum = $orderStart + ($i * $orderStep);
                        echo '<li class="mdl-list__item"><a href="#" style="color: #E65100;">Order #' . $orderNum . '</a></li>';
                    }
                    ?>
                </ul>
            </div>
          </div>

          <div class="mdl-cell mdl-cell--9-col card-lesson mdl-card mdl-shadow--2dp">
            <div class="mdl-card__title" style="background-color: #FF9800; color: #fff;">
              <h2 class="mdl-card__title-text">Selected Order: #520</h2>
            </div>
            <div class="mdl-card__supporting-text">
                <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp" style="width: 100%;">
                 <caption>Customer: <strong>Mount Royal University</strong></caption>
                  <thead>
                    <tr>
                      <th class="mdl-data-table__cell--non-numeric">Cover</th>
                      <th class="mdl-data-table__cell--non-numeric">Title</th>
                      <th>Quantity</th>
                      <th>Price</th>
                      <th>Amount</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    outputOrderRow($file1, $title1, $quantity1, $price1);
                    outputOrderRow($file2, $title2, $quantity2, $price2);
                    outputOrderRow($file3, $title3, $quantity3, $price3);
                    outputOrderRow($file4, $title4, $quantity4, $price4);
                    ?>
                  </tbody>
                  <tfoot>
                      <tr class="totals">
                          <td class="mdl-data-table__cell--non-numeric" colspan="4">Subtotal</td>
                          <td>$<?php echo number_format($subtotal, 2); ?></td>
                      </tr>
                      <tr class="totals">
                          <td class="mdl-data-table__cell--non-numeric" colspan="4">Shipping</td>
                          <td>$<?php echo number_format($shipping, 2); ?></td>
                      </tr>
                      <tr class="grandtotals">
                          <td class="mdl-data-table__cell--non-numeric" colspan="4">Grand Total</td>
                          <td>$<?php echo number_format($grandtotal, 2); ?></td>
                      </tr>
                  </tfoot>

                </table>
            </div>

          </div>

        </div>

    </section>
  </main>

</div>

<script src="js/material.min.js"></script>

</body>
</html>

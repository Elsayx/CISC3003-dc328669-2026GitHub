<?php

include 'includes/book-utilities.inc.php';

$customers = readCustomers('data/customers.txt');

$selectedCustomer = null;
$orders = array();

if (isset($_GET['customer_id'])) {
    $custId = $_GET['customer_id'];
    if (isset($customers[$custId])) {
        $selectedCustomer = $customers[$custId];
        $orders = readOrders($custId, 'data/orders.txt');
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>dc328669 Yang Xu</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.3/material.blue_grey-orange.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/demo-styles.css">
    
    <script src="https://code.jquery.com/jquery-1.7.2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.3/material.min.js"></script>
    <script src="js/jquery.sparkline.2.1.2.js"></script>
    
  
</head>

<body>
    
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer
            mdl-layout--fixed-header">
            
    <?php include 'includes/header.inc.php'; ?>
    <?php include 'includes/left-nav.inc.php'; ?>
    
    <main class="mdl-layout__content mdl-color--grey-50">
        <section class="page-content">

            <div class="mdl-grid">

              <!-- mdl-cell + mdl-card -->
              <div class="mdl-cell mdl-cell--7-col card-lesson mdl-card  mdl-shadow--2dp">
                <div class="mdl-card__title mdl-color--orange">
                  <h2 class="mdl-card__title-text">Customers</h2>
                </div>
                <div class="mdl-card__supporting-text">
                    <table class="mdl-data-table  mdl-shadow--2dp">
                      <thead>
                        <tr>
                          <th class="mdl-data-table__cell--non-numeric">Name</th>
                          <th class="mdl-data-table__cell--non-numeric">University</th>
                          <th class="mdl-data-table__cell--non-numeric">City</th>
                          <th>Sales</th>
                        </tr>
                      </thead>
                      <tbody>

                        <?php foreach ($customers as $cust): ?>
                        <tr>
                          <td class="mdl-data-table__cell--non-numeric">
                            <a href="cisc3003-sugex10-after.php?customer_id=<?php echo $cust['id']; ?>">
                              <?php echo $cust['firstName'] . ' ' . $cust['lastName']; ?>
                            </a>
                          </td>
                          <td class="mdl-data-table__cell--non-numeric"><?php echo $cust['university']; ?></td>
                          <td class="mdl-data-table__cell--non-numeric"><?php echo $cust['city']; ?></td>
                          <td>
                            <span class="inlinesparkline"><?php echo $cust['sales']; ?></span>
                          </td>
                        </tr>
                        <?php endforeach; ?>
                                              
                      </tbody>
                    </table>
                </div>
              </div>  <!-- / mdl-cell + mdl-card -->
              
              
            <div class="mdl-grid mdl-cell--5-col">
    

       
                  <!-- mdl-cell + mdl-card -->
                  <div class="mdl-cell mdl-cell--12-col card-lesson mdl-card  mdl-shadow--2dp">
                    <div class="mdl-card__title mdl-color--deep-purple mdl-color-text--white">
                      <h2 class="mdl-card__title-text">Customer Details</h2>
                    </div>
                    <div class="mdl-card__supporting-text">
                        <?php if ($selectedCustomer): ?>
                        <h4><?php echo $selectedCustomer['firstName'] . ' ' . $selectedCustomer['lastName']; ?></h4>
                        <p><strong>Email:</strong> <?php echo $selectedCustomer['email']; ?></p>
                        <p><strong>University:</strong> <?php echo $selectedCustomer['university']; ?></p>
                        <p><strong>Address:</strong> <?php 
                            $addr = $selectedCustomer['address'] . ', ' . $selectedCustomer['city'] . ', ' . $selectedCustomer['state'] . ', ' . $selectedCustomer['country'];
                            if (!empty($selectedCustomer['zip'])) {
                                $addr .= ', ' . $selectedCustomer['zip'];
                            }
                            echo $addr;
                        ?></p>
                        <p><strong>Phone:</strong> <?php echo $selectedCustomer['phone']; ?></p>
                        <?php else: ?>
                        <p>Select a customer to view details.</p>
                        <?php endif; ?>
                                                                                                                                                                           
                    </div>    
                  </div>  <!-- / mdl-cell + mdl-card -->   

                  <!-- mdl-cell + mdl-card -->
                  <div class="mdl-cell mdl-cell--12-col card-lesson mdl-card  mdl-shadow--2dp">
                    <div class="mdl-card__title mdl-color--deep-purple mdl-color-text--white">
                      <h2 class="mdl-card__title-text">Order Details</h2>
                    </div>
                    <div class="mdl-card__supporting-text">       
                               
                               <table class="mdl-data-table  mdl-shadow--2dp">
                              <thead>
                                <tr>
                                  <th class="mdl-data-table__cell--non-numeric">Cover</th>
                                  <th class="mdl-data-table__cell--non-numeric">ISBN</th>
                                  <th class="mdl-data-table__cell--non-numeric">Title</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php if ($selectedCustomer && count($orders) > 0): ?>
                                <?php foreach ($orders as $order): ?>
                                <tr>
                                  <td class="mdl-data-table__cell--non-numeric">
                                    <img src="images/tinysquare/<?php echo $order['isbn']; ?>.jpg" 
                                         onerror="this.src='images/tinysquare/missing.jpg'" 
                                         alt="<?php echo $order['title']; ?>">
                                  </td>
                                  <td class="mdl-data-table__cell--non-numeric"><?php echo $order['isbn']; ?></td>
                                  <td class="mdl-data-table__cell--non-numeric"><?php echo $order['title']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php elseif ($selectedCustomer && count($orders) == 0): ?>
                                <tr>
                                  <td class="mdl-data-table__cell--non-numeric" colspan="3">No orders for this customer.</td>
                                </tr>
                                <?php endif; ?>
                              </tbody>
                            </table>

                        </div>    
                   </div>  <!-- / mdl-cell + mdl-card -->             


               </div>   
           
           
            </div>  <!-- / mdl-grid -->    

        </section>

        <footer style="text-align:center; padding:20px; color:#666; font-size:14px;">
            CISC3003 Web Programming: dc328669 Yang Xu 2026
        </footer>

    </main>    
</div>    <!-- / mdl-layout --> 

<script>
$(function() {
    $('.inlinesparkline').sparkline('html', {type: 'bar', barColor: '#ff6e40'});
});
</script>
          
</body>
</html>

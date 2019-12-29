<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>PayPal Invoice</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>
  <body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-6" style="border:1px solid blue">
                <h1>Invoice Send Via PayPal API</h1>
                <form method="POST">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="Client Email" required>
                    </div>
                    <div class="form-group">
                        <label for="prodname">Product Name</label>
                        <input type="text" name="name" class="form-control" id="prodname" placeholder="Product Name" required>
                    </div>
                    <div class="form-group">
                        <label for="proddescription">Product Description</label>
                        <input type="text" name="description" class="form-control" id="proddescription" placeholder="Product Description" required>
                    </div>
                    <div class="form-group">
                        <label for="value">Email</label>
                        <input type="text" name="value" class="form-control" id="value" placeholder="Value (USD)" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" />
                    </div>
                </form>
                <?php
                if(isset($_POST['email'])) {
                    include('invoice.php');
                    echo draftInvoice($_POST['name'], $_POST['description'], $_POST['email'], $_POST['value']);
                }
                ?>
            </div>
            <div class="col-xs-6" style="border:1px solid red">
                <h1>PayPal Full Transaction Refund</h1>
                    <form method="POST">
                        <div class="form-group">
                            <label for="transid">Transaction ID</label>
                            <input type="text" name="transid" class="form-control" id="transid" placeholder="Transaction ID" required>
                        </div>
                        <div class="form-group">
                            <input type="submit" />
                        </div>
                    </form>
                    <?php
                    if(isset($_POST['transid'])){
                        include('refund.php');
                        print_r(fullRefund($_POST['transid']));
                    }
                    ?>
                    <h1>Partial Refund</h1>
                    <form method="POST">
                        <div class="form-group">
                            <label for="transid">Transaction ID</label>
                            <input type="text" name="partialtransid" class="form-control" id="transid" placeholder="Transaction ID" required>
                        </div>
                        <div class="form-group">
                            <label for="transid">Refund Value</label>
                            <input type="text" name="partialtransvalue" class="form-control" id="transid" placeholder="Partial Refund Value" required>
                        </div>
                        <div class="form-group">
                            <input type="submit" />
                        </div>
                    </form>
                    <?php
                    if(isset($_POST['partialtransid'])){
                        include('refund.php');
                        print_r(partialRefund($_POST['partialtransid'], $_POST['partialtransvalue']));
                    }
                    ?>
                    
                    
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  </body>
</html>
<?php
    // resume the session if one is already existing
    session_start();
    // extract data from the session
    $loggedin = false;
    $delivery_details = false;


    if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'])
    {
        $user_name = $_SESSION['user_name'];
        $user_email = $_SESSION['user_email'];
        $loggedin = true;

        require "partials/_dbconnect.php";
        $sql = "SELECT i.item_name, o.ord_quantity, o.ord_date, o.ord_isComp, o.ord_price
                FROM FOOD_ORDER o
                INNER JOIN ITEM i
                ON i.item_id = o.ord_food_id
                WHERE o.ord_date = CURDATE() AND o.ord_user_id = '$user_email'";
        $get_result = mysqli_query($conn, $sql);
        $num_orders = mysqli_num_rows($get_result);
    }
    if(isset($_SESSION['mode']) && isset($_SESSION['destination'])) {
      $delivery_details = true;
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") {
      if(isset($_POST)) {
        $_SESSION['mode'] = (int)$_POST['mode'];
        $_SESSION['destination'] = $_POST['destination'];
        header("Location: Menu.php");
      }
    }
    // EXTRACT FEEDBACKS:
    require "partials/_dbconnect.php";
    $feedback_query = "SELECT * FROM FEEDBACK ORDER BY rating DESC LIMIT 2";
    $feedbacks = mysqli_query($conn, $feedback_query);
    if(!$feedbacks) {
      die("Error " . mysqli_connect_error($conn));
    }
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    
    <!-- CSS -->
    <style>
      body {background-image: url('./assets/BG2.jpg');background-repeat:no-repeat; background-size:cover; background-position:center; opacity: 1}
      .dot {
        height: 25px;
        width: 25px;
        background-color: black;
        border-radius: 50%;
        display: inline-block;
        margin: 5px;
      }
      .filled {
          background-color: gold;
      }
      .half-filled {
          background: linear-gradient(to right, gold 50%, black 50%);
      }
    </style>

    <!-- AOS CSS -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

    <title>Delizioso</title>
  </head>
  <body>
  <section id="title">
    <?php require "partials/_nav.php" ?>
    <div class="container my-5" style="text-align: center;">
      <h1 style=" color: white;">Welcome to Delizioso</h1>
      <div class="container">
        <div class="row" style="justify-content: center;">
          <div class="col-sm-2">
            <?php if($delivery_details) { ?>
              <a href="Menu.php"><button class="btn btn-lg btn-dark">Place Order</button></a>
            <?php } else{ ?>
              <!-- Button trigger modal -->
              <button type="button" id="butn" class="btn btn-lg btn-dark" data-toggle="modal" data-target="#exampleModal">
                Place Order
              </button>

              <!-- Modal -->
              <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Enter delivery details: </h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <form action="Home.php" method="POST">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="Inplace" value="1" name="mode">
                          <label class="form-check-label" for="Inplace">Inplace</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="HD" value="2" name="mode">
                          <label class="form-check-label" for="HD">Home delivery</label>
                        </div>
                        <br>
                        <input type="text" name="destination" placeholder="Enter table number or address" style="width: 400px;" />
                        <br>
                        <button type="submit" class="btn btn-primary my-3">Save changes</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>


          <div class="col-sm-2">
            <a href="TableListView.php"><button id="butn" class="btn btn-lg btn-dark">Book Table</button></a>
          </div>
        </div>
        </div>

  </section>
      <!-- Order Tracking -->
      <div class="container my-5" style="color: white;">
        <?php
          if($loggedin && $num_orders > 0) { ?>
          <h4> Track Your Orders </h4>
          <table class="table table-striped" style="color: white;">
              <thead>
                  <tr>
                  <th scope="col">Name</th>
                  <th scope="col">Quantity</th>
                  <th scope="col">Status</th>
                  </tr>
              </thead>
              <tbody>
                  <?php
                      $record = mysqli_fetch_assoc($get_result);
                      while($record) {
                          $ord_status = $record['ord_isComp'] ? 'Done' : 'Working on it';
                          echo "
                              <tr>
                                  <td>{$record['item_name']}</td>
                                  <td>{$record['ord_quantity']}</td>
                                  <td>{$ord_status}</td>
                              </tr>";

                          $record = mysqli_fetch_assoc($get_result);
                      }
                  ?>
              </tbody>
          </table>
        <?php } ?>
      </div>

      <!-- feedbacks -->
      <div class="container" style="text-align: center; margin-top:100px;">
        <h3 data-aos="fade-up" style="color: white;"> Reviews </h3>
        <div class="row">
        <?php
          $animation = "fade-right";

          $feedback_record = mysqli_fetch_assoc($feedbacks);
          while($feedback_record) {
            $rating = $feedback_record['rating'];
            $rating_code = "";
            for($i=1; $i<=$rating; $i++)
              $rating_code = $rating_code . "<span class='dot filled'></span>";
            for($i=1; $i<=5-$rating; $i++)
              $rating_code = $rating_code . "<span class='dot'></span>";
            
            if($animation == "fade-left")
              $animation = "fade-right";
            else
              $animation = "fade-left";
            
            echo "
            <div class='card' data-aos='{$animation}' style='width: 18rem; height: 18rem; margin: auto; margin-top: 1rem'>
              <div class='card-body'>
                <h5 class='card-title'>"
                 . $rating_code . 
                "</h5>
                <h5 class='card-title'>@{$feedback_record['cust_email']}</h5>
                <p class='card-text' style='overflow: auto'>{$feedback_record['review']}</p>
              </div>
            </div>";

            //var_dump($feedback_record);
            // echo "<div class='card'>
            //       <div class='card-header'>
            //       {$feedback_record['cust_email']}
            //       </div>
            //       <div class='card-body'>
            //         <h5 class='card-title'>Rating: {$feedback_record['rating']}</h5>
            //         <p class='card-text'>{$feedback_record['review']}.</p>
            //       </div>
            //     </div>";
            
            $feedback_record = mysqli_fetch_assoc($feedbacks);
          }
        ?>
        </div>
      </div>
    </div>
    <!-- AOS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1200
        });
    </script>

    <!-- Custom script -->
    <script>
      $("input:radio[name=mode]").change(function() {
        console.log($(this).val());
        if($(this).val() == 1) {
          $("input[type=text][name=destination]").attr('placeholder', 'Enter Table Number');
        } else {
          $("input[type=text][name=destination]").attr('placeholder', 'Enter your address');
        }
      });
    </script>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <!-- Option 2: jQuery, Popper.js, and Bootstrap JS
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    -->
  </body>
</html>
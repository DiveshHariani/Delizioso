<?php
    session_start();
    if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"]) {
        $user_email = $_SESSION['user_email'];
    } else {
        header("Location: Login.php");
        exit;
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

    <!-- Jquery -->
    <script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    crossorigin="anonymous"></script>

    <style>
        .dot {
            height: 25px;
            width: 25px;
            background-color: black;
            border-radius: 50%;
            display: inline-block;
        }
        .filled {
            background-color: gold;
        }
        .half-filled {
            background: linear-gradient(to right, gold 50%, black 50%);
}
    </style>

    <title>Feedback</title>
  </head>
  <body>
    <?php include "partials/_nav.php" ?>
    <div class="container my-4" style="text-align: center; width: 450px;">
        <h1>Feedback</h1>
        <form>
            <div class="form-group row">
                <label for="rating" class="col-sm-2 col-form-label">Rating</label>
                <div class="col-sm-10">
                    <span class="dot" id="1"></span>
                    <span class="dot" id="2"></span>
                    <span class="dot" id="3"></span>
                    <span class="dot" id="4"></span>
                    <span class="dot" id="5"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="review" class="col-sm-2 col-form-label">Review</label>
                <div class="col-sm-10">
                    <textarea type="textbox" class="form-control" id="review"></textarea>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-10">
                    <button id="submit_feedback" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        $("#submit_feedback").click(function(event) {
            event.preventDefault();

            let rating = $(".filled").length;
            let review = $("#review").val();
            console.log("rating", rating);
            jQuery.noConflict();
            $.ajax({
                type: "POST",
                url: "Feedback.php",
                data: {
                        'rating': rating,
                        'review': review
                       },
                success: function(data) {
                    console.log(data);
                    data = JSON.parse(data)
                    console.log(typeof data);
                    console.log(data.result);
                    if(data['result']) {
                        
                        alert("Thankyou for Feedback!");
                        location.reload(true);
                    }
                }
            })
        });
    </script>

    <!-- Rating -->
    <script>
        $("#1").click(function() {
            $(".dot").removeClass("filled");
            $("#1").addClass("filled");
        });
        $("#2").click(function() {
            $(".dot").removeClass("filled");
            $("#1, #2").addClass("filled");
        });
        $("#3").click(function() {
            $(".dot").removeClass("filled");
            $("#1, #2, #3").addClass("filled");
        });
        $("#4").click(function() {
            $(".dot").removeClass("filled");
            $("#1, #2, #3, #4").addClass("filled");
        });
        $("#5").click(function() {
            $(".dot").removeClass("filled");
            $("#1, #2, #3, #4, #5").addClass("filled");
        });
    </script>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script> -->

    <!-- Option 2: jQuery, Popper.js, and Bootstrap JS
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    -->
  </body>
</html>
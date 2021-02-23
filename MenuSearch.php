<?php
    require "partials/_dbconnect.php";

    // resume the session if one is already existing
    session_start();
    // extract data from the session
    $loggedin = false;
    if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'])
    {
        $user_name = $_SESSION['user_name'];
        $loggedin = true;
    }
    else
    {
        header("Location: Login.php");
    }
    
    if($_SERVER["REQUEST_METHOD"] == "GET") {
        $itemName = $_GET["search"];
    }

    $sql = "SELECT * FROM ITEM WHERE item_name LIKE '%{$itemName}%'";
    $query_res = mysqli_query($conn, $sql);
    if(!$query_res) {
        die("Query error ".mysqli_connect_error());
    }
    if($query_res)
    {
        // $record = mysqli_fetch_assoc($query_res);
        // while($record) {
        //     echo "{$record['item_id']} {$record['item_name']} {$record['item_price']} <br>";
        //     $record = mysqli_fetch_assoc($query_res);
        // }
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
    h1 {text-align: center;}
    body {padding:3% 15% 7%; background-image: url('./assets/vege.jpg');background-repeat:no-repeat; background-size:cover; background-position:center; opacity: 1}
    </style>

    <!-- Jquery -->
    <script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    crossorigin="anonymous"></script>

    <title>Menu</title>
  </head>
  <body>
    <?php include "partials/_nav.php" ?>
    <h1> Menu </h1>
    <!-- Button trigger modal -->
    <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
    Launch demo modal
    </button> -->
    <form class="row" style="text-align: center;" method="GET" action="MenuSearch.php">
        <div class="col-8">
            <input type="text" class="form-control" id="search" name="search" placeholder="Enter Food name" />
        </div>
        <div class="col-4">
            <button class="btn btn-dark"> Search </button>
        </div>
    </form>
    <!-- Modal -->
    <div class="modal fade" id="Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirm your order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-lg btn-primary" id="confirm">Confirm</button>
            </div>
            </div>
        </div>
    </div>

    <form style="overflow: auto; height: 300px;">
        <table class="table table-striped">
            <thead>
                <tr>
                <th scope="col"></th>
                <th scope="col"><h5>Name</h5></th>
                <th scope="col"><h5>Quantity</h5></th>
                <th scope="col"><h5>Price</h5></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $record = mysqli_fetch_assoc($query_res);
                    while($record) {
                        echo "
                            <tr>
                                <th scope=\"row\">
                                    <div class=\"form-check\">
                                        <input class=\"form-check-input checks\" type=\"checkbox\" value='{$record['item_id']}' name=\"checkbox[]\" >
                                    </div>
                                </th>
                                <td id='name{$record['item_id']}'><b>{$record['item_name']}</b></td>
                                <td>
                                    <div class=\"form-check\">
                                    <input class=\"form-check-input\" type=\"number\" value='1' min='1' name=\"quantity[]\" id='{$record['item_id']}' disabled>
                                    </div>
                                </td>
                                <td><b>{$record['item_price']}</b></td>
                            </tr>";
                        echo "<input type='hidden' name='price[]' value='{$record['item_price']}' id='price{$record['item_id']}' disabled />";

                        $record = mysqli_fetch_assoc($query_res);
                    }
                ?>
            </tbody>
        </table>
        <button id="place" class="btn btn-lg btn-dark" style="position: fixed;">Place</button>
    </form>

    <?php
        if(isset($isWarning) && $isWarning) {
            echo "<script> alert('Please select item to be placed') </script>";
        }
    ?>
<!------------------------------------------------------------------------------------------------>
    <script>
        var item_ids = [];
        var item_qtys = [];
        var item_prices = [];
        var item_names = [];

        // handle able/disable of qty field
        $(".checks").change(function(){
            //alert("clicked");
            if(this.checked) {
                let quant_ip_id = $(this).val();
                $("#"+quant_ip_id).removeAttr("disabled");
                $("#price"+quant_ip_id).removeAttr("disabled");
                //console.log($(this).val());
            }
            else
            {
                let quant_ip_id = $(this).val();
                $("#"+quant_ip_id).attr("disabled", true);
                $("#price"+quant_ip_id).attr("disabled", true);
            }
        });

        // Handle click on place button
        $('#place').click(function(event) {
            //alert("Clicked");
            event.preventDefault();
            let total_price = 0;
            var html = [];
            html.push('<table class="table table-striped">'+
            '<thead>' +
                '<tr>'+
                '<th scope="col">Name</th>'+
                '<th scope="col">Quantity</th>'+
                '<th scope="col">Price</th>'+
                '</tr>'+
            '</thead>'+
            '<tbody>');

            if($("input[name='checkbox[]']:checked").length != 0) 
            {
                $("input[name='checkbox[]']:checked").each(function() {
                    //console.log($(this).val());
                    // get all item details
                    id = $(this).val()
                    item_ids.push(id);
                    item_qtys.push($("#"+id).val());
                    item_prices.push($("#price"+id).val());
                    item_names.push($("#name"+id).text());
                    html.push(
                    '<tr>' +
                    '<td>' + $("#name"+id).text() + '</td>' +
                    '<td>' + $("#"+id).val() + '</td>' +
                    '<td>' + Number($("#price"+id).val()) * Number($("#"+id).val()) + '</td>' +
                    '</tr>'
                    );
                });
                for(let i=0; i<item_prices.length; i++)
                    total_price += Number(item_prices[i])*Number(item_qtys[i]);
                html.push(
                    '<tr>' +
                    '<td> Total Price </td>' +
                    '<td></td>' + 
                    '<td>' + total_price + '</td>' +
                    '</tr>' 
                );

                html.push('</tbody></table>');
                // console.log(html);
                // console.log(html.join(''));
                modal_content = html.join('');
                $(".modal-body").html(modal_content);
                $("#Modal").modal('show');
                // console.log(item_ids);
                // console.log(item_qtys);
                // console.log(item_prices);
                // console.log(item_names);
            }
            else
            {
                alert("Please select an item to be orderd");
            }
        });

        // handle confirm order button
        $("#confirm").click(function(event) {
            event.preventDefault();

            console.log(item_ids);
            console.log(item_qtys);
            console.log(item_prices);
            console.log(item_names);

            jQuery.noConflict();
            $.ajax({
                type: "POST",
                url: "PlaceOrder.php",
                data: {
                        'checkbox': item_ids,
                        'quantity': item_qtys,
                        'price': item_prices
                       },
                success: function(data) {
                    console.log(data);
                    data = JSON.parse(data)
                    console.log(typeof data);
                    console.log(data.result);
                    if(data['result']) {
                        alert("Order successful");
                        location.reload(true);
                    }
                }
            });
        });

        // get list of all selected check boxes:
    </script>
  </body>
</html>
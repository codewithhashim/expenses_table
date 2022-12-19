<?php
class Connection {

    function __construct() {
        global $con;
    $con = mysqli_connect('localhost', 'root', '', 'income');

    if (isset($_POST['einnahme'])) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $datum = $_POST['datum'];
            $titel = $_POST['titel'];
            $betrag_green = $_POST['betrag'];
    
        }
    
        $sql = "INSERT INTO `expenses` (`datum`, `titel`, `betrag_green`) VALUES ('$datum', '$titel', '$betrag_green' ) ";
        
        $rs = mysqli_query($con, $sql);
    
    } elseif (isset($_POST['ausgabe'])) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $datum = $_POST['datum'];
            $titel = $_POST['titel'];
            $betrag_red = $_POST['betrag'];
    
        }
    
        $sql = "INSERT INTO `expenses` (`datum`, `titel`, `betrag_red`) VALUES ('$datum', '$titel', '$betrag_red' ) ";
        $rs = mysqli_query($con, $sql);
    
    }
    }
}
$obj = new Connection();


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Formular</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
        integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <style>
        #wrapper {
            max-width: 700px;
            margin: 30px auto;
        }

        .right {
            text-align: right;
        }

        .page-link {
            color: #212529;
        }

        .page-link:hover {
            color: #212529;
        }

        .page-item.active .page-link {
            border-color: #212529;
            background-color: #212529;
        }

        .pagination li a {
            position: relative;
            display: block;
            padding: .5rem .75rem;
            margin-left: -1px;
            line-height: 1.25;
            color: #007bff;
            background-color: #fff;
            border: 1px solid #dee2e6;
        }

        .pagination li.active a{
            color: white!important;
            border-color: #212529;
            background-color: #212529!important;
        }
    </style>
</head>

<body>
    <div id="wrapper" class="container-fluid">
        <h1>Einnahmen / Ausgaben</h1>
        <form action="form.php" method="post">
            <div class="row g-3 mt-3">
                <div class="col-sm mt-1">
                    <input type="date" name="datum" class="form-control" value="" required aria-label="Datum" require>
                </div>
                <div class="col-sm-5  mt-1">
                    <input type="text" name="titel" class="form-control" value="" required placeholder="Titel"
                        aria-label="Titel">
                </div>
                <div class="col-sm  mt-1">
                    <div class="input-group">
                        <input type="text" name="betrag" class="form-control" value="" required placeholder="Betrag"
                            aria-label="Betrag">
                        <div class="input-group-text">€</div>
                    </div>
                </div>
            </div>
            <div class="mt-3 right">
                <div class="col-12 pl-0 pr-0">
                    <button type="submit" name="einnahme" class="btn btn-success mr-3" formnovalidate="formnovalidate"
                        value="Einnahme">Einnahme</button>
                    <button type="submit" name="ausgabe" class="btn btn-danger" formnovalidate="formnovalidate"
                        name="ausgabe" value="ausgabe">Ausgabe</button>
                    <input type="hidden" name="_token" value="">
                </div>
            </div>
        </form>
        <hr class="mt-5">
        <?php

        // current page number
      
        if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
            $page_no = $_GET['page_no'];
        } else {
            $page_no = 1;
        }
        $total_records_per_page = 10;
        $offset = ($page_no - 1) * $total_records_per_page;
        $previous_page = $page_no - 1;
        $next_page = $page_no + 1;
        $adjacents = "2";

        $result_count = mysqli_query($con, "SELECT COUNT(*) As total_records FROM `expenses`");
        $total_records = mysqli_fetch_array($result_count);
        $total_records = $total_records['total_records'];
        $total_no_of_pages = ceil($total_records / $total_records_per_page);
        $second_last = $total_no_of_pages - 1; // total pages minus 1
        

        // 10 is the limit
        $sql = "SELECT * FROM `expenses` LIMIT $offset, $total_records_per_page";
        $result = mysqli_query($con, $sql);

        ?>
        <div>
            <div class="row pl-2">
                <div class="col-sm text-success">Einnahmen:

                    <?php
                    $total = mysqli_query($con, 'SELECT SUM(betrag_green) AS value_sum FROM expenses');
                    $row = mysqli_fetch_assoc($total);
                    $sum_green = $row['value_sum'];
                    echo number_format((float)$sum_green, 2, '.', '');
                    ?>

                </div>
                <div class="col-sm text-danger">Ausgaben:

                    <?php
                    $total = mysqli_query($con, 'SELECT SUM(betrag_red) AS value_sum FROM expenses');
                    $row = mysqli_fetch_assoc($total);
                    $sum_red = $row['value_sum'];
                    echo ($sum_red . ',00 €');
                    ?>
                </div>
                <div class="col-sm text-success">Stand:
                    <?php
                    echo ($sum_green - $sum_red . ',00 €');
                    ?>
                </div>
            </div>
        </div>
        <hr>
        <div class="mt-5">
            <p class="pl-2"><strong>
                    <?php
                    echo date('F Y');
                    ?>
                </strong></p>
            <table class="table table-striped" width="100%">
                <thead>
                    <tr>
                        <th>Datum</th>
                        <th>Titel</th>
                        <th class="right">Einnahme</th>
                        <th class="right">Ausgabe</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0):
                        while ($row = mysqli_fetch_array($result)):
                    ?>
     <tr>
                        <td>
                            <?php
                            echo $row["datum"];
                            ?>
                        </td>
                        <td>
                            <?php
                            echo $row["titel"];
                            ?>
                        </td>
                        <td class="text-success right">
                            <?php

                            echo $row["betrag_green"] . ',00';

                            ?>
                        </td>
                        <td class="text-danger right">
                            <?php

                            echo $row["betrag_red"] . ',00';

                            ?>
                        </td>
                    </tr>



                    <?php
                        endwhile;
                    ?>
                    <?php
                    endif;
                    ?>
                </tbody>
            </table>
            <div style='padding: 10px 20px 0px; border-top: dotted 1px #CCC;'>
                <strong>Page
                    <?php echo $page_no . " of " . $total_no_of_pages; ?>
                </strong>
            </div>

            <ul class="pagination">
                <?php // if($page_no > 1){ echo "<li><a href='?page_no=1'>First Page</a></li>"; } ?>
    
	<li <?php if ($page_no <= 1) {
        echo "class='disabled'";
    } ?>>
                <a <?php if ($page_no > 1) {                 echo "href='?page_no=$previous_page'";                } ?>>‹</a>
                </li>

                <?php
                if ($total_no_of_pages <= 10) {
                    for ($counter = 1; $counter <= $total_no_of_pages; $counter++) {
                        if ($counter == $page_no) {
                            echo "<li class='active'><a>$counter</a></li>";
                        } else {
                            echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                        }
                    }
                } elseif ($total_no_of_pages > 10) {

                    if ($page_no <= 4) {
                        for ($counter = 1; $counter < 8; $counter++) {
                            if ($counter == $page_no) {
                                echo "<li class='active'><a>$counter</a></li>";
                            } else {
                                echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                            }
                        }
                        echo "<li><a>...</a></li>";
                        echo "<li><a href='?page_no=$second_last'>$second_last</a></li>";
                        echo "<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
                    } elseif ($page_no > 4 && $page_no < $total_no_of_pages - 4) {
                        echo "<li><a href='?page_no=1'>1</a></li>";
                        echo "<li><a href='?page_no=2'>2</a></li>";
                        echo "<li><a>...</a></li>";
                        for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {
                            if ($counter == $page_no) {
                                echo "<li class='active'><a>$counter</a></li>";
                            } else {
                                echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                            }
                        }
                        echo "<li><a>...</a></li>";
                        echo "<li><a href='?page_no=$second_last'>$second_last</a></li>";
                        echo "<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
                    } else {
                        echo "<li><a href='?page_no=1'>1</a></li>";
                        echo "<li><a href='?page_no=2'>2</a></li>";
                        echo "<li><a>...</a></li>";

                        for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
                            if ($counter == $page_no) {
                                echo "<li class='active'><a>$counter</a></li>";
                            } else {
                                echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                            }
                        }
                    }
                }
                ?>

                <li <?php if ($page_no >= $total_no_of_pages) {                   echo "class='disabled'";             } ?>>
                    <a <?php if ($page_no < $total_no_of_pages) {                 echo "href='?page_no=$next_page'";                 } ?>> › </a>
                </li>
                <?php if ($page_no < $total_no_of_pages) {
                    echo "<li><a href='?page_no=$total_no_of_pages'>»</a></li>";
                } ?>
            </ul>



</body>

</html>
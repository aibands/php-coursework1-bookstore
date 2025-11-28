<?php
    // Part1  Book Inventory (Arrays)
    $books = [
        [
            'title' => 'Dune',
            'author' => 'Frank Herbert',
            'genre' => 'Science Fiction',
            'price' => 29.99
        ],
        [
            'title' => 'The Catcher in the Rye',
            'author' => 'J.D. Salinger',
            'genre' => 'Coming-of-Age',
            'price' => 18.99
        ],
        [
            'title' => 'The Hobbit',
            'author' => 'J.R.R. Tolkien',
            'genre' => 'Fantasy',
            'price' => 24.99
        ],
        [
            'title' => 'To Kill a Mockingbird',
            'author' => 'Harper Lee',
            'genre' => 'Classic Fiction',
            'price' => 21.99
        ]
    ];

    //Part2 Discount Logic Pass-by-Reference

    function applyDiscounts(array &$books) {
        // create loop to check genre every book
        foreach ($books as &$book) {
            if($book['genre'] === "Science Fiction"){
                $book['price'] *= 0.9; // discount 10%
            } else if ($book['genre'] === "Classic Fiction"){
                $book['price'] *= 0.75; // discount 25%
            }
        }
    }
    
    // applyDiscounts($books); // call function
    // print_r($books); // print to check

    // Part3 User Input Handling
    switch($_SERVER["REQUEST_METHOD"]){
        case "GET":
            break;
        case "POST":
            switch(basename($_SERVER["PATH_INFO"])){
                case "input":
                if(isset($_REQUEST['title']) && isset($_REQUEST["author"]) && isset($_REQUEST["genre"]) && isset($_REQUEST["price"])){
                    // echo $_REQUEST["title"];
                    $title = $_REQUEST['title'];
                    $author = $_REQUEST['author'];
                    $genre = $_REQUEST['genre'];
                    $price = $_REQUEST['price'];
                    // add new book
                    $newBooks = [
                        'title' => $title,
                        'author' => $author,
                        'genre' => $genre,
                        'price' => $price
                    ];

                    $books[] = $newBooks;
                    // apply discount for newbook as well
                    applyDiscounts($books);
                    // print_r($books);

                    // part 7 File Logging (Write-Only Log)
                    $logLine = "[" . date("Y-m-d H:i:s") . "] " .
                                "IP: " . $_SERVER['REMOTE_ADDR'] . " | " .
                                "UA: " . $_SERVER['HTTP_USER_AGENT'] . " | " .
                                "New book: \"" . $newBooks['title'] . " by " . $newBooks['author'] . "\" (" . 
                                        $newBooks['genre'] . ", " . number_format($newBooks['price'], 2) . ")\n";
                    // Append to log file
                    file_put_contents('bookstore_log.txt', $logLine, FILE_APPEND);

                }else{
                    echo 'input data is wrong';
                }
                break;
            }
            break;
    }
    // Part4 Total Price Calculation
    // call function discount to make all book discount
    applyDiscounts($books);
    $totalPrice = 0;
    foreach ($books as $book) {
        $totalPrice += $book['price'];
    }
    // echo "Total price after discounts: $$totalPrice"; html already have
?>

<!-- Part5 Display the book list -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Online Bookstore</title>
        <style>
            body {
                background-color: #f7f7f7;
                color: #333;
                display: flex;
                flex-direction: column;
                align-items: center;
                margin: 0;
                padding: 20px;
            }

            table {
                text-align: center;
                background-color: #fff;
            }

            th, td {
                padding: 20px;
                border: 1px solid black;
            }

            th {
                background-color: #0f6198ff;
                color: white;
            }

            p {
                font-size: 16px;
                margin: 10px 0;
            }
            .timestamp{
                text-align: left;
            }
        </style>
    </head>
    <body>
        <h1>Online Bookstore</h1>
        <h3>Book List</h3>
        <table>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Genre</th>
                <th>Price (After discount if applicable)</th>
            </tr>
            <?php foreach($books as $book): ?>
                <tr>
                    <td><?= $book['title'] ?></td>
                    <td><?= $book['author'] ?></td>
                    <td><?= $book['genre'] ?></td>
                    <!-- make price show just 2 number -->
                    <td>$<?= number_format($book['price'], 2) ?></td> 
                </tr>
                <!-- ending tag for a special PHP loop syntax -->
            <?php endforeach; ?>
        </table>
        <h3>Total price after discounts: $<?= number_format($totalPrice, 2) ?></h3>
        
        <!-- Part6 Server Info & Timestamp -->
         <div class="timestamp">
            <h3>Server Info & Timestamp</h3>
            <p>Request time: <?= date("Y-m-d H:i:s") ?></p>
            <p>IP: <?= $_SERVER['REMOTE_ADDR'] ?></p>
            <p>User agent: <?= $_SERVER['HTTP_USER_AGENT'] ?></p>
            <!-- for the log file-->
            <p><a href="view_log.php">View Bookstore Log</a></p> 
        </div>
    </body>
</html>

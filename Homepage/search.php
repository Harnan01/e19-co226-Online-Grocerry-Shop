<?php
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "project";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search results</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="design.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-md">
                <a href="Project.php" class="navbar-brand">
                    <img src="images/logo.png" alt="Store" height="60">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <div class="d-flex justify-content-center align-items-center" style="flex-grow: 1;">
                        <ul class="navbar-nav justify-content-center align-items-center">
                            <li class="nav-item">
                            <form action="search.php" method="GET">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="keyword" placeholder="Search on Skymart" style="width: 500px; min-width: 200px;">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="submit">Search</button>
                                    </div>
                                </div>
                            </form>

                            </li>
                        </ul>
                    </div>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown d-flex align-items-center"> 
                            <a class="nav-link dropdown-toggle " href="#" role="button" data-toggle="dropdown" style="color: black;" > 
                                Categories
                            </a>
                            <div class="dropdown-menu dropdown-menu-right"> 
                            <a class="dropdown-item" href="#">food</a>
                            <a class="dropdown-item" href="#">fruit</a>
                            <a class="dropdown-item" href="#">phone</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <button id = "Cart" style= "border-color : transparent;background-color:transparent" > 
                                <img src="images/cart.png" alt="cart" height="30">
                            </button>
                        </li>
                        <li class="nav-item dropdown"> 
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"> 
                            <img src="images/user.png" alt="user" height="30">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right"> 
                            <a class="dropdown-item" href="Profile.php">Profile</a>
                            <a class="dropdown-item" href="Profile.php#changePassword">Change password</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="http://localhost/e19-co226-Online-Grocery-Shop/LogIn/login.php">Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        
    </header>


    <section class="products py-5">
        <div class="container">

            <div class="row">
                <?php
                if (isset($_GET['keyword'])) {
                    $keyword = $_GET['keyword'];
            
                    if (!empty($keyword)) {
            
                        $query = "SELECT * FROM product WHERE ProductName LIKE '%$keyword%' OR Description LIKE '%$keyword%' OR Category LIKE '%$keyword%'";
                        $result = $conn->query($query);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $imageData = base64_encode($row['Image']); 
                            ?>
                                <div class="col-lg-3 col-md-4 mb-3" >
                                    <div class="card">
                                    <img src='data:image/jpg;base64,<?php echo $imageData; ?>' class="card-img-top" style="height : 150px;">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo $row['ProductName']; ?></h5>
                                            <p class="card-text"><?php echo $row['Description']; ?></p>
                                            <h5 class = "card-title">LKR <?php echo number_format($row['SellingPrice'], 2); ?></h5>
                                            <button  class="addToCartButton btn btn-primary" data-product-id = <?php echo $row['Id']; ?> >Add to Cart</button>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                        } 
                    }
                }
                        ?>
<?php
        if (isset($_GET['keyword'])) {
                $keyword = $_GET['keyword'];
                    
                if (!empty($keyword)) {
                    $query = "SELECT * FROM product WHERE ProductName LIKE '%$keyword%' OR Description LIKE '%$keyword%' OR Category LIKE '%$keyword%'";
                    $result = $conn->query($query);
                    
                    if ($result->num_rows <= 0) {
                        echo "<h4 class='container' > No results found. </h4>";
                    }
                } else {
                    echo "<h4 class='container'>'Please enter a search keyword.'</h4>";
                }
            }
            ?>


            </div>
        </div>
    </section>


<?php      

    $conn->close();
} else {
    header("HTTP/1.1 405 Method Not Allowed");
    header("Allow: GET");
    echo "Method Not Allowed";
}
?>

    <script>
        var addToCartButtons = document.querySelectorAll(".addToCartButton");

        sendToCart = [];

        function addToUniqueArray(value) {
            if (!sendToCart.includes(value)) {
                sendToCart.push(value);
            }
        }

        addToCartButtons.forEach(function(button) {
            button.addEventListener("click", function(event) {
                var productId = event.target.getAttribute("data-product-id");
                addToUniqueArray(productId)
                    
            });
        });
    productIds =sendToCart;

    document.getElementById('Cart').addEventListener('click', function() {
            // Redirect to Cart.php with productIds as a URL parameter
            var productIdsString = JSON.stringify(productIds);
            var encodedProductIds = encodeURIComponent(productIdsString);
            window.location.href = 'Cart.php?productIds=' + encodedProductIds;
    });
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

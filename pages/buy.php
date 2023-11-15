<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ParkCoins - Buy Coins</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        /* Customize body background and font */
body {
    background-color: #141414;
    color: #fff;
    font-family: Arial, sans-serif;
}

/* Header styles */
h1 {
    color: #f9ff2b; /* Yellow color similar to Fortnite theme */
}

/* Card styles */
.card {
    background-color: #181818; /* Dark background color */
    color: #fff;
    margin: 10px;
}

.card-title {
    color: #f9ff2b; /* Yellow title color */
}

.card-text {
    font-size: 1.5rem;
}

/* Button styles */
.btn-primary {
    background-color: #f9ff2b; /* Yellow button color */
    border-color: #f9ff2b; /* Yellow border color */
    color: #000; /* Text color */
}

.btn-primary:hover {
    background-color: #000; /* Hover color */
    color: #f9ff2b; /* Text color on hover */
    border-color: #f9ff2b; /* Border color on hover */
} 

    </style>
</head>
<body>
  <div class="text-center">

  </div>
   <div class="container text-center mt-5">
    <h1 class="display-4">Buy ParkCoins</h1>
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <img src="/images/park1.jpeg" class="card-img-top" alt="Image 1">
                <div class="card-body">
                    <h5 class="card-title">50 ParkCoins</h5>
                    <p class="card-text">5€</p>
                    <a id="buy-button" data-amount="50" data-currency="EUR" class="btn btn-primary" href="https://buy.stripe.com/14kaFf8gUcApeNq3cc">
                        Buy Now
</a>
                                      </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <img src="/images/parkcoins3.jpeg" class="card-img-top" alt="Image 2">
                <div class="card-body">
                    <h5 class="card-title">500 ParkCoins</h5>
                    <p class="card-text">30€</p>
                    COMING SOON
                                      </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <img src="/images/parkcoins2.jpeg" class="card-img-top" alt="Image 3">
                <div class="card-body">
                    <h5 class="card-title">1000 ParkCoins</h5>
                    <p class="card-text">70€</p>
                    COMING SOON                     
                      
                </div>
            </div>
        </div>
    </div>
</div>

  
</body>
</html>
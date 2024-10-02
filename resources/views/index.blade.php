<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mega Deals - Home Wi-Fi</title>
    <link rel="shortcut icon" href="https://img.freepik.com/free-photo/global-business-internet-network-connection-iot-internet-things-business-intelligence-concept-bus_1258-176990.jpg" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url(https://img.freepik.com/premium-photo/global-business-internet-network-connection-iot-internet-things-business-intelligence-concept-bus_1258-178669.jpg);
            background-size: cover;
        }
        .carousel {
    display: flex;
    overflow: hidden; /* Hide overflow for a clean look */
    width: 100%; /* Full width of the parent */
    max-width: 600px; /* Set maximum width for the carousel */
    height: 400px; /* Set a fixed height for the carousel */
    margin: 20px auto; /* Center the carousel horizontally */
    border: 2px solid #ccc; /* Optional: Add a border for better visibility */
    border-radius: 10px; /* Optional: Rounded corners */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Optional: Shadow for depth */
}

.carousel-item {
    min-width: 100%; /* Ensure each item takes full width of the carousel */
    transition: transform 0.5s ease; /* Smooth transition for sliding effect */
    text-align: center; /* Center text inside the carousel items */
}

.carousel img {
    width: 100%; /* Full width of the item */
    height: 100%; /* Set height to 100% of the carousel item */
    object-fit: cover; /* Ensure images cover the area without distortion */
    display: block; /* Ensure images are treated as block elements for centering */
}


        .container {
            display: flex;
            justify-content: center;
            padding: 20px;
        }
        .deals {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            width: 100%;
            padding: 20px;
            border-radius: 10px;
        }
        .deal-item {
            text-align: center;
            background-color: #f7f7f7;
            padding: 15px;
            margin: 10px;
            border-radius: 10px;
            width: 26%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .deal-item:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .deal-item h2 {
            color: #0074d9;
            font-size: 20px;
        }
        .deal-item .price {
            font-size: 24px;
            color: #ff4136;
        }
        .deal-item .details {
            font-size: 12px;
            margin: 8px 0;
        }
        .deal-item .free-data {
            background-color: #ffdc00;
            color: black;
            padding: 5px;
            border-radius: 5px;
        }
        .deal-item input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .deal-item button {
            background-color: #0074d9;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            width: 100%;
            margin-top: 10px;
            cursor: pointer;
            font-size: 16px;
        }
        .deal-item button:hover {
            background-color: #005bb5;
        }
        .wifi-section {
            background-color: white;
            padding: 20px;
            margin: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .wifi-section h2 {
            color: #0074d9;
        }
        .wifi-section .price {
            font-size: 28px;
            color: #ff4136;
        }

        @media screen and (max-width: 768px) {
            .deal-item {
                width: 25%;
            }
        }

        @media screen and (max-width: 480px) {
            .deals {
                flex-direction: column;
                align-items: center;
            }
            .deal-item {
                width: 100%;
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="wifi-section">
        <h2>Home Wi-Fi Mega Deals</h2>
        <p>Connect for less with our special offers. Enjoy more data for less!</p>
    </div>
    <!-- Carousel -->
    <div id="adCarousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://my-catalogue.co.za/public/gimg/1/6/0/7/7/4/4/pep-cell-catalogue-28-07-2023-24-08-2023--1607744-350-580.jpg" class="d-block w-100" alt="Ad 1">
            </div>
            <div class="carousel-item">
                <img src="https://mybroadband.co.za/news/wp-content/uploads/2019/07/MTN-Mega-Deals.png" class="d-block w-100" alt="Ad 2">
            </div>
            <div class="carousel-item">
                <img src="https://img.offers-cdn.net/assets/uploads/offers/za/22615873/hisense-u50-lite-normal.jpeg" class="d-block w-100" alt="Ad 3">
            </div>
        </div>
        <a class="carousel-control-prev" href="#adCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#adCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
    
    <div class="container">
        <div class="deals">
            <div class="deal-item">
                <h2>20 GB</h2>
                <div class="price">Ksh 149</div>
                <div class="details">SIM-Only <br> MyMtn Social Pass 30GB</div>
                <div class="free-data">Free 14GB</div>
                <form action="{{ route('initiatePayment') }}" method="post">
                    @csrf
                <input type="text" name="phone" placeholder="Enter phone number" />
                <input type="number" name="amount" value="1" hidden>
                <button>Buy</button>
            </form>
            </div>
            <div class="deal-item">
                <h2>40 GB</h2>
                <div class="price">Ksh 199</div>
                <div class="details">SIM-Only <br> MyMtn Social Pass 30GB</div>
                <div class="free-data">Free 14GB</div>
                <input type="text" name="phone" placeholder="Enter phone number" />
                <input type="number" name="amount" value="199" hidden>
                <button>Buy</button>
            </div>
            <div class="deal-item">
                <h2>80 GB</h2>
                <div class="price">Ksh 299</div>
                <div class="details">SIM-Only <br> MyMtn Social Pass 30GB</div>
                <div class="free-data">Free 14GB</div>
                <input type="text" placeholder="Enter phone number" />
                <button>Buy</button>
            </div>
            <div class="deal-item">
                <h2>120 GB</h2>
                <div class="price">Ksh 399</div>
                <div class="details">Wi-Fi Router <br> MyMtn Social Pass 30GB</div>
                <div class="free-data">Free 14GB</div>
                <input type="text" placeholder="Enter phone number" />
                <button>Buy</button>
            </div>
            <div class="deal-item">
                <h2>210 GB</h2>
                <div class="price">Ksh 599</div>
                <div class="details">Wi-Fi Router <br> MyMtn Social Pass 30GB</div>
                <div class="free-data">Free 34GB</div>
                <input type="text" placeholder="Enter phone number" />
                <button>Buy</button>
            </div>
            <div class="deal-item">
                <h2>300 GB</h2>
                <div class="price">Ksh 599</div>
                <div class="details">Wi-Fi Router <br> MyMtn Social Pass 30GB</div>
                <div class="free-data">Free 14GB</div>
                <input type="text" placeholder="Enter phone number" />
                <button>Buy</button>
            </div>
            <div class="deal-item">
                <h2>300 GB</h2>
                <div class="price">Ksh 599</div>
                <div class="details">Wi-Fi Router <br> MyMtn Social Pass 30GB</div>
                <div class="free-data">Free 14GB</div>
                <input type="text" placeholder="Enter phone number" />
                <button>Buy</button>
            </div>
            <div class="deal-item">
                <h2>300 GB</h2>
                <div class="price">Ksh 599</div>
                <div class="details">Wi-Fi Router <br> MyMtn Social Pass 30GB</div>
                <div class="free-data">Free 14GB</div>
                <input type="text" placeholder="Enter phone number" />
                <button>Buy</button>
            </div>
            <div class="deal-item">
                <h2>300 GB</h2>
                <div class="price">Ksh 599</div>
                <div class="details">Wi-Fi Router <br> MyMtn Social Pass 30GB</div>
                <div class="free-data">Free 14GB</div>
                <input type="text" placeholder="Enter phone number" />
                <button>Buy</button>
            </div>
        </div>
    </div>
    <div class="wifi-section">
        <h2>Home Wi-Fi Mega Deals</h2>
        <p>Connect for less with our special offers. Enjoy more data for less!</p>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</html>

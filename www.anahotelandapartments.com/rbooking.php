<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="theme-color" content="white">
    <link rel="icon" type="image/ico" href="img/favicon2.png">
    <?php include 'css.php'; ?>
    <link type="text/css" rel="stylesheet" href="../www.anahotelandapartments.com/css/form.css" />
    <link type="text/css" rel="stylesheet" href="../www.anahotelandapartments.com/css/bootstrap.min.css" />
    <meta name="description" content="At Ana Hotel & Apartments, we strive to provide an unmatched experience characterized by exquisite style and first-rate service.">
    <meta name="author" content="vimtech Africa, Nigeria-based Company">
    <meta property="og:title" content="ANA Hotel and Apartments - Luxurious suites and apartments | Abuja">
    <meta property="og:site_name" content="vimtech Africa">
    <meta property="og:description" content="At Ana Hotel & Apartments, we strive to provide an unmatched experience characterized by exquisite style and first-rate service.">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@vimtechafrica">
    <meta name="twitter:creator" content="@vimtechmedia">
    <title>ANA Hotel and Apartments - Luxurious Suites and Apartments | Abuja</title>
    <script>
        async function checkAvailability() {
            const roomType = document.querySelector('input[name="room-type"]:checked').value;
            const response = await fetch(`check_availability.php?type=${roomType}`);
            const rooms = await response.json();

            const roomSelect = document.getElementById("roomSelect");
            roomSelect.innerHTML = "";

            rooms.forEach(room => {
                const option = document.createElement("option");
                option.value = room.id;
                option.text = `Room ${room.id}`;
                roomSelect.appendChild(option);
            });
        }

        function showModal() {
            const params = new URLSearchParams(window.location.search);
            if (params.has('successful')) {
                const modal = document.getElementById('successModal');
                modal.style.display = 'block';
            }
            if (params.has('no_rooms')) {
                const modal = document.getElementById('noroomModal');
                modal.style.display = 'block';
            }
        }

        window.onload = showModal;
    </script>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
<div id="reception-booking" class="section">
    <div class="section-center">
        <h3>WELCOME: <?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'Guest'; ?></h3>
        <div class="container">
            <div class="row">
                <div class="booking-form">
                    <form action="reception_book.php" method="POST">
                        <div class="form-group">
                            <div class="form-checkbox">
                                <label for="standard">
                                    <input type="radio" id="standard" name="room-type" value="standard" required>
                                    <span></span>Standard
                                </label>
                                <label for="deluxe">
                                    <input type="radio" id="deluxe" name="room-type" value="deluxe" required>
                                    <span></span>Deluxe
                                </label>
                                <label for="royal">
                                    <input type="radio" id="royal" name="room-type" value="royal" required>
                                    <span></span>Royal
                                </label>
                                <label for="palatial">
                                    <input type="radio" id="palatial" name="room-type" value="palatial" required>
                                    <span></span>Palatial Apartment
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <span class="form-label">Full Name</span>
                                    <input class="form-control" type="text" placeholder="Name" name="name" required />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <span class="form-label">Email</span>
                                    <input class="form-control" type="email" name="email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <span class="form-label">Phone</span>
                                    <input class="form-control" type="text" name="phone" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <span class="form-label">Arrival</span>
                                    <input class="form-control" type="date" name="check_in_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <span class="form-label">Departure</span>
                                    <input class="form-control" type="date" name="check_out_date" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <span class="form-label">Adults (18+)</span>
                                    <select class="form-control" name="total_adults" required>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <span class="form-label">Children (0-17)</span>
                                    <select class="form-control" name="total_children" required>
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-checkbox">
                                <label for="pos">
                                    <input type="radio" id="pos" name="payment-type" value="POS" required>
                                    <span></span>POS
                                </label>
                                <label for="transfer">
                                    <input type="radio" id="transfer" name="payment-type" value="Transfer" required>
                                    <span></span>Transfer
                                </label>
                                <label for="cash">
                                    <input type="radio" id="cash" name="payment-type" value="Cash" required>
                                    <span></span>Cash
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-btn">
                                    <button class="submit-btn" type="submit">BOOK</button>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (isset($_GET['successful']) && isset($_GET['booking_id'])): ?>
<!-- The Success Modal -->
<div id="successModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('successModal').style.display='none'">&times;</span>
        <div class="row">
            <div class="col-md-6">
                <h6>Reservations No: 09062035350, 09062035351 <br>
                Email: reservations@anahotelandapartments.com</h6>
            </div>
            <div class="col-md-6">
                <h5>Your booking ID is: <?php echo htmlspecialchars($_GET['booking_id']); ?></h5>
                <h5>A refundable charge of N5,000 is to be added to your total payment. Please call the Reservation No</h5>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>


<!-- No Rooms Modal -->
<div id="noroomModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('noroomModal').style.display='none'">&times;</span>
        <h2>No rooms available</h2>
        <p>Unfortunately, we don't have rooms available for the selected type at this time.</p>
    </div>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>
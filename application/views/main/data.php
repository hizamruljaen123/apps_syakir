<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- Highcharts -->
    <script src="https://code.highcharts.com/highcharts.js"></script>

    <title>Oil Price Prediction</title>

    <!-- Custom CSS -->
    <style>
        /* Navbar */
        .navbar {
            background-color: #1abc9c; /* Aksen warna pertama */
            color: white;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            background-color: #16a085; /* Aksen warna kedua */
            padding-top: 56px;
            z-index: 1;
        }

        .sidebar .nav-link {
            color: white;
        }

        .sidebar .nav-link:hover {
            color: #f1c40f; /* Warna ketika dihover */
        }

        /* Main content */
        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .content h1 {
            color: #1abc9c; /* Warna judul */
        }

        /* Chart container */
        #chartContainer {
            background-color: #f2f2f2; /* Warna latar belakang chart */
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body>

<?php include 'sideNav.php' ?>
<!-- Main content -->
<div class="content" style="margin-top: 3%;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h1>Data Historis</h1>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Close</th>
                            <th>Open</th>
                            <th>High</th>
                            <th>Low</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($records as $record): ?>
                        <tr>
                            <td><?php echo $record['Date']; ?></td>
                            <td><?php echo $record['Terakhir']; ?></td>
                            <td><?php echo $record['Pembukaan']; ?></td>
                            <td><?php echo $record['Tertinggi']; ?></td>
                            <td><?php echo $record['Terendah']; ?></td>
                            <td><a href="<?php echo base_url('main/delete_data/'.$record['id']); ?>">Delete</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- jQuery first, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/main.js"></script>

</body>
</html>

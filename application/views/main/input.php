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
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Input Single Data
                </div>
                <div class="card-body">
                  
                    <form method="post" action="<?php echo base_url('main/save_data'); ?>">
                        <div class="form-group">
                            <label for="inputDate">Date:</label>
                            <input type="date" class="form-control" name="inputDate" value="<?php echo date('Y-m-d') ?>">
                        </div>
                        <div class="form-group">
                            <label for="inputHigh">Open:</label>
                            <input type="text" class="form-control" name="inputOpen">
                        </div>
                        <div class="form-group">
                            <label for="inputHigh">Close:</label>
                            <input type="text" class="form-control" name="inputClose">
                        </div>
                        <div class="form-group">
                            <label for="inputHigh">High:</label>
                            <input type="text" class="form-control" name="inputHigh">
                        </div>
                        <div class="form-group">
                            <label for="inputLow">Low:</label>
                            <input type="text" class="form-control" name="inputLow">
                        </div>
                        <!-- Add more input fields as needed -->
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
         <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Input Single Data
                </div>
                <div class="card-body">
                  <form enctype="multipart/form-data" method="post" action="<?php echo base_url('main/upload_csv'); ?>">
                    <div class="form-group">
                        <label for="inputDate">Upload CSV Dataset</label>
                        <input type="file" class="form-control" id="inputDate" name="csvFile">
                    </div>
                    
                    <!-- Add more input fields as needed -->
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>

              </div>

            </div>
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

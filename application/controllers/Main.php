<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

    public function index()
	{
		$this->load->model('Oil_Price_Prediction_model');
	    // Load database library
	    $this->load->database();

	    // Panggil data historis dari tabel database
	    $historical_data = $this->db->order_by('Date', 'DESC');
	    $historical_data = $this->db->get('tbl_data')->result_array();

	    // Panggil nilai alpha dari tabel parameter_alpha
	    $result = $this->db->get('parameter_alpha')->row_array();
	    $alpha = isset($result['alpha']) ? $result['alpha'] : 0.765;
	    $beta = 1 - $alpha;

	    // Panggil fungsi prediksi Holt Exponential Smoothing
	    $predictions = $this->holtExponentialSmoothing($historical_data, $alpha, $beta);

	    // Kirim data historis dan prediksi per hari ke view
	    $data['historical_data'] = $historical_data;
	    $data['predictions'] = $predictions;

	    // Load view
	    $this->load->view('main/index', $data); 
	}


    public function input_data()
    {
    	$this->load->view('main/input'); 
    }
    public function holt()
    {
    	$this->load->view('main/holt'); 
    }
    public function save_data()
	{
	    // Load database library
	    $this->load->database();

	    // Retrieve data from form
	    $tanggal = $this->input->post('inputDate');
	    $terakhir = $this->input->post('inputClose');
	    $pembukaan = $this->input->post('inputOpen');
	    $tertinggi = $this->input->post('inputHigh');
	    $terendah = $this->input->post('inputLow');


	    // print_r($this->input->post());
	    // Check if all required fields are filled
	    if (!empty($tanggal) && !empty($terakhir) && !empty($pembukaan) && !empty($tertinggi) && !empty($terendah)) {
	        // Format tanggal
	        $tanggal = date('Y-m-d', strtotime($tanggal));
	        
	        // Construct the SQL query
	        $sql = "INSERT INTO tbl_data (Tanggal, Terakhir, Pembukaan, Tertinggi, Terendah) 
	                VALUES ('$tanggal', '$terakhir', '$pembukaan', '$tertinggi', '$terendah')";

	        // Execute SQL query
	        $query_result = $this->db->query($sql);

	        if ($query_result) {
	            echo "Data saved successfully.";
	        } else {
	            // Print database error
	            $error = $this->db->error();
	            echo "Failed to save data. Error: {$error['code']} - {$error['message']}";
	        }
	    } else {
	        echo "All fields are required.";
	    }
	}

	public function show_data()
	{
	    // Load database library
	    $this->load->database();

	    // Select data from database
	    $query = $this->db->get('tbl_data');
	    
	    // Check if there is any data
	    if ($query->num_rows() > 0) {
	        $data['records'] = $query->result_array();
	        // Load view to display data
	        $this->load->view('main/data', $data);
	    } else {
	        echo "No data found.";
	    }
	}

	public function delete_data($id)
	{
	    // Load database library
	    $this->load->database();

	    // Delete data from database
	    $this->db->where('id', $id);
	    $result = $this->db->delete('tbl_data');

	    // Check if data deleted successfully
	    if ($result) {
	        // Redirect to show_data
	        redirect('main/show_data');
	    } else {
	        // Print database error
	        $error = $this->db->error();
	        echo "Failed to delete data. Error: {$error['code']} - {$error['message']}";
	    }
	}




    // Function to generate random data for historical oil prices
    private function generateRandomData($num_days, $start_year) {
        $oil_data = array();
        $current_date = strtotime($start_year . '-01-01');
        $start_price = 50; // Starting price
        $price_fluctuation = 5; // Maximum fluctuation in price

        for ($i = 0; $i < $num_days; $i++) {
            $date = date('Y-m-d', $current_date);
            $open = $start_price + rand(-$price_fluctuation, $price_fluctuation);
            $close = $open + rand(-$price_fluctuation, $price_fluctuation);
            $high = max($open, $close) + rand(0, $price_fluctuation);
            $low = min($open, $close) - rand(0, $price_fluctuation);

            $oil_data[] = array(
                'Date' => $date,
                'Open' => $open,
                'Close' => $close,
                'High' => $high,
                'Low' => $low
            );

            $current_date = strtotime('+1 day', $current_date);
        }

        return $oil_data;
    }

public function upload_csv()
{
    // Load necessary libraries
    $this->load->library('csv');
    $this->load->database();

    // Load CSV file
    $file = $_FILES['csvFile']['tmp_name'];

    // Convert CSV to array
    $data = $this->csv->parse_file($file);

    // Process each row of data
    foreach ($data as &$row) {
        // Trim all keys to remove hidden characters
        $row = array_map('trim', $row);

        // Remove unwanted columns
        unset($row['Vol.']);
        unset($row['Perubahan%']);

        // Rename key "Tanggal" to "Date" using regex to detect hidden characters
        foreach ($row as $key => $value) {
            if (preg_match('/\s*"Tanggal"\s*/', $key)) {
                $row['Date'] = $value;
                unset($row[$key]);
            }
        }

        // Convert date format
        if (isset($row['Date'])) {
            $row['Date'] = date('Y-m-d', strtotime(str_replace('/', '-', $row['Date'])));
        }

        // Convert numbers with commas to decimals
        foreach (['Terakhir', 'Pembukaan', 'Tertinggi', 'Terendah'] as $key) {
            if (isset($row[$key])) {
                $row[$key] = str_replace(',', '.', $row[$key]);
            }
        }

        // Convert 'Perubahan%' to decimal
        if (isset($row['Perubahan%'])) {
            $row['Perubahan'] = str_replace('%', '', $row['Perubahan%']);
            unset($row['Perubahan%']);
        }
    }

    // Insert or update data into database
    foreach ($data as $row) {
        if (isset($row['Date'])) {
            // Check if data with the same date already exists
            $this->db->where('Date', $row['Date']);
            $query = $this->db->get('tbl_data');

            if ($query->num_rows() > 0) {
                // Update existing data
                $this->db->where('Date', $row['Date']);
                $this->db->update('tbl_data', $row);
            } else {
                // Insert new data
                $this->db->insert('tbl_data', $row);
            }
        }
    }

    // Check for errors and display appropriate message
    $affected_rows = $this->db->affected_rows();
    if ($affected_rows > 0) {
        echo "Data imported successfully.";
    } else {
        $error = $this->db->error();
        echo "Failed to import data. Error: {$error['code']} - {$error['message']}";
    }
}

public function holtExponentialSmoothing($data, $alpha, $beta) {
    $predictions = array();
    $level = $data[0]['Terakhir']; // Inisialisasi level awal
    $trend = $data[1]['Terakhir'] - $data[0]['Terakhir']; // Inisialisasi tren awal

    foreach ($data as $key => $value) {
        $last_level = $level;
        $level = $alpha * $value['Terakhir'] + (1 - $alpha) * ($last_level + $trend);
        $trend = $beta * ($level - $last_level) + (1 - $beta) * $trend;
        $predictions[$key] = array(
            'Date' => $value['Date'],
            'Open' => $value['Pembukaan'],
            'Close' => $value['Terakhir'],
            'High' => $value['Tertinggi'],
            'Low' => $value['Terendah'],
            'Prediction' => $level + $trend
        );
    }

    return $predictions;
}



}
?>

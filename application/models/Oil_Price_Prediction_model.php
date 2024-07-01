<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Oil_Price_Prediction_model extends CI_Model {

    // Fungsi untuk menghitung Holt's Exponential Smoothing
public function holtExponentialSmoothing($data, $alpha, $beta) {
    $predictions = array();
    $level = $data[0]['Terakhir']; // Inisialisasi level awal
    $trend = $data[1]['Terakhir'] - $data[0]['Terakhir']; // Inisialisasi tren awal

    foreach ($data as $key => $value) {
        $last_level = $level;
        $level = $alpha * $value['Terakhir'] + (1 - $alpha) * ($last_level + $trend);
        $trend = $beta * ($level - $last_level) + (1 - $beta) * $trend;
        $predictions[$key] = array(
            'Date' => $value['Tanggal'],
            'Open' => $value['Pembukaan'],
            'Close' => $value['Terakhir'],
            'High' => $value['Tertinggi'],
            'Low' => $value['Terendah'],
            'Prediction' => $level + $trend
        );
    }

    return $predictions;
}


    // Fungsi untuk prediksi harga minyak
    public function predictOilPrice($historical_oil_data, $alpha, $beta) {
        return $this->holtExponentialSmoothing($historical_oil_data, $alpha, $beta);
    }
}
?>

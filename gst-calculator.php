<?php
/*
Plugin Name: GST Calculator India
Plugin URI: https://example.com/gst-calculator
Description: A simple GST calculator for Indian users with AJAX support
Version: 1.0
Author: Your Name
Author URI: https://example.com
*/

if (!defined('ABSPATH')) exit;

// Enqueue scripts and styles
function gst_calculator_enqueue_scripts() {
    // CSS
    wp_enqueue_style('gst-calculator-css', plugins_url('css/style.css', __FILE__));
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
    
    // JS
    wp_enqueue_script('gst-calculator-js', plugins_url('js/script.js', __FILE__), array('jquery'), null, true);
    wp_localize_script('gst-calculator-js', 'gstCalculator', array(
        'ajaxurl' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'gst_calculator_enqueue_scripts');

// Shortcode handler
function gst_calculator_shortcode() {
    ob_start(); ?>
    <div class="gst-calculator-container">
        <h3><i class="fas fa-calculator"></i> GST Calculator</h3>
        <div class="gst-input-group">
            <label><i class="fas fa-rupee-sign"></i> Original Price:</label>
            <input type="number" id="gst-original-price" step="0.01" required>
        </div>
        
        <div class="gst-input-group">
            <label><i class="fas fa-percentage"></i> GST Rate:</label>
            <select id="gst-rate">
                <option value="5">5%</option>
                <option value="12">12%</option>
                <option value="18">18%</option>
                <option value="28">28%</option>
            </select>
        </div>
        
        <button id="gst-calculate-btn" class="gst-button">
            <i class="fas fa-calculate"></i> Calculate GST
        </button>
        
        <div id="gst-results" class="gst-results">
            <div class="gst-result-item">
                <span>GST Amount:</span> 
                <span id="gst-amount">₹0.00</span>
            </div>
            <div class="gst-result-item">
                <span>Total Price:</span> 
                <span id="gst-total-price">₹0.00</span>
            </div>
            <div class="gst-result-item">
                <span>CGST (<span class="cgst-percent">0</span>%):</span> 
                <span id="cgst-amount">₹0.00</span>
            </div>
            <div class="gst-result-item">
                <span>SGST (<span class="sgst-percent">0</span>%):</span> 
                <span id="sgst-amount">₹0.00</span>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('gst_calculator', 'gst_calculator_shortcode');

// AJAX handler
add_action('wp_ajax_calculate_gst', 'calculate_gst');
add_action('wp_ajax_nopriv_calculate_gst', 'calculate_gst');

function calculate_gst() {
    $original_price = floatval($_POST['original_price']);
    $gst_rate = floatval($_POST['gst_rate']);
    
    $gst_amount = ($original_price * $gst_rate) / 100;
    $total_price = $original_price + $gst_amount;
    $cgst = $sgst = $gst_amount / 2;
    
    wp_send_json(array(
        'success' => true,
        'gst_amount' => number_format($gst_amount, 2),
        'total_price' => number_format($total_price, 2),
        'cgst' => number_format($cgst, 2),
        'sgst' => number_format($sgst, 2),
        'cgst_percent' => $gst_rate / 2,
        'sgst_percent' => $gst_rate / 2
    ));
}

<?php
/**
 * Plugin Name: Estimated Delivery Addon - Product Types & Categories
 * Description: Ajoute des options de délais de livraison par type de produit et par catégorie pour le plugin Estimated Delivery
 * Author: Extended by AI
 * Version: 1.0.2
 * Text Domain: estimated-delivery-addon
 * Domain Path: /languages
 * WC requires at least: 3.0
 * WC tested up to: 8.8.3
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

if(!defined('ABSPATH')) { exit; }

// Vérifier que le plugin principal est actif
add_action('plugins_loaded', 'eda_check_parent_plugin');
function eda_check_parent_plugin() {
    if(!class_exists('EDWCore')) {
        add_action('admin_notices', 'eda_parent_plugin_notice');
        deactivate_plugins(plugin_basename(__FILE__));
        return;
    }
}

function eda_parent_plugin_notice() {
    ?>
    <div class="notice notice-error">
        <p><?php _e('Le plugin "Estimated Delivery Addon" nécessite le plugin "Estimated Delivery for WooCommerce" pour fonctionner.', 'estimated-delivery-addon'); ?></p>
    </div>
    <?php
}

// Définir les constantes
define('EDA_PATH', dirname(__FILE__) . '/');
define('EDA_VERSION', '1.0.2');
define('EDA_PLUGIN_FILE', __FILE__);

// Charger les classes
require_once EDA_PATH . 'includes/class-admin.php';
require_once EDA_PATH . 'includes/class-filters.php';

// Initialiser le plugin
if(!class_exists('EDA_Core')) {
    class EDA_Core {
        private static $instance = null;

        public static function get_instance() {
            if(self::$instance === null) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        private function __construct() {
            // Initialiser l'admin
            if(is_admin()) {
                new EDA_Admin();
            }

            // Initialiser les filtres
            new EDA_Filters();

            // Charger les traductions
            add_action('plugins_loaded', array($this, 'load_textdomain'));
        }

        public function load_textdomain() {
            load_plugin_textdomain('estimated-delivery-addon', false, basename(dirname(__FILE__)) . '/languages');
        }
    }

    // Démarrer le plugin
    add_action('plugins_loaded', array('EDA_Core', 'get_instance'), 20);
}

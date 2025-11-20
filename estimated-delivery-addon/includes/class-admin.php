<?php
if(!defined('ABSPATH')) { exit; }

class EDA_Admin {

    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'), 99);
        add_action('admin_init', array($this, 'save_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
    }

    public function enqueue_admin_styles($hook) {
        if($hook !== 'woocommerce_page_eda-settings') {
            return;
        }

        wp_add_inline_style('wp-admin', '
            .eda-settings-wrap {
                max-width: 1200px;
            }
            .eda-section {
                background: #fff;
                padding: 20px;
                margin: 20px 0;
                border: 1px solid #ccd0d4;
                box-shadow: 0 1px 1px rgba(0,0,0,.04);
            }
            .eda-section h2 {
                margin-top: 0;
                padding-bottom: 10px;
                border-bottom: 1px solid #e5e5e5;
            }
            .eda-table {
                width: 100%;
                border-collapse: collapse;
            }
            .eda-table th {
                background: #f9f9f9;
                padding: 12px;
                text-align: left;
                border-bottom: 2px solid #e5e5e5;
                font-weight: 600;
            }
            .eda-table td {
                padding: 12px;
                border-bottom: 1px solid #e5e5e5;
            }
            .eda-table tr:hover {
                background: #f9f9f9;
            }
            .eda-input {
                width: 80px;
                text-align: center;
            }
            .eda-help {
                color: #666;
                font-size: 13px;
                font-style: italic;
                margin-top: 5px;
            }
            .eda-notice {
                background: #fff8e5;
                border-left: 4px solid #ffb900;
                padding: 12px;
                margin: 15px 0;
            }
        ');
    }

    public function add_admin_menu() {
        add_submenu_page(
            'woocommerce',
            __('Délais par Type/Catégorie', 'estimated-delivery-addon'),
            __('Délais par Type/Catégorie', 'estimated-delivery-addon'),
            'manage_options',
            'eda-settings',
            array($this, 'render_settings_page')
        );
    }

    public function save_settings() {
        if(!isset($_POST['eda_save_settings']) || !wp_verify_nonce($_POST['eda_nonce'], 'eda_save_settings')) {
            return;
        }

        if(!current_user_can('manage_options')) {
            return;
        }

        // Sauvegarder les délais par type de produit
        if(isset($_POST['eda_product_types'])) {
            $product_types_settings = array();
            foreach($_POST['eda_product_types'] as $type => $settings) {
                if(!empty($settings['days']) || !empty($settings['max_days'])) {
                    $product_types_settings[$type] = array(
                        'days' => absint($settings['days']),
                        'max_days' => absint($settings['max_days']),
                        'days_outstock' => absint($settings['days_outstock']),
                        'max_days_outstock' => absint($settings['max_days_outstock']),
                        'days_backorders' => absint($settings['days_backorders']),
                        'max_days_backorders' => absint($settings['max_days_backorders']),
                    );
                }
            }
            update_option('eda_product_types_settings', $product_types_settings);
        }

        // Sauvegarder les délais par catégorie
        if(isset($_POST['eda_categories'])) {
            $categories_settings = array();
            foreach($_POST['eda_categories'] as $cat_id => $settings) {
                if(!empty($settings['days']) || !empty($settings['max_days'])) {
                    $categories_settings[$cat_id] = array(
                        'days' => absint($settings['days']),
                        'max_days' => absint($settings['max_days']),
                        'days_outstock' => absint($settings['days_outstock']),
                        'max_days_outstock' => absint($settings['max_days_outstock']),
                        'days_backorders' => absint($settings['days_backorders']),
                        'max_days_backorders' => absint($settings['max_days_backorders']),
                    );
                }
            }
            update_option('eda_categories_settings', $categories_settings);
        }

        // Ajouter un message de succès
        add_settings_error(
            'eda_messages',
            'eda_message',
            __('Paramètres sauvegardés avec succès.', 'estimated-delivery-addon'),
            'updated'
        );

        set_transient('eda_settings_saved', true, 5);
    }

    public function render_settings_page() {
        // Afficher le message de succès
        if(get_transient('eda_settings_saved')) {
            delete_transient('eda_settings_saved');
            echo '<div class="notice notice-success is-dismissible"><p>' .
                 __('Paramètres sauvegardés avec succès.', 'estimated-delivery-addon') .
                 '</p></div>';
        }

        // Récupérer les paramètres sauvegardés
        $product_types_settings = get_option('eda_product_types_settings', array());
        $categories_settings = get_option('eda_categories_settings', array());

        // Charger la vue
        include EDA_PATH . 'views/admin-settings.php';
    }

    /**
     * Obtenir tous les types de produits WooCommerce
     */
    public static function get_product_types() {
        return array(
            'simple' => __('Produit Simple', 'estimated-delivery-addon'),
            'variable' => __('Produit Variable', 'estimated-delivery-addon'),
            'grouped' => __('Produit Groupé', 'estimated-delivery-addon'),
            'external' => __('Produit Externe/Affilié', 'estimated-delivery-addon'),
        );
    }

    /**
     * Obtenir toutes les catégories de produits
     */
    public static function get_product_categories() {
        $categories = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'orderby' => 'name',
            'order' => 'ASC',
        ));

        $result = array();
        if(!is_wp_error($categories)) {
            foreach($categories as $category) {
                $result[$category->term_id] = $category->name;
            }
        }

        return $result;
    }
}

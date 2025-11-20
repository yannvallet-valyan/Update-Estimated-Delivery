<?php
if(!defined('ABSPATH')) { exit; }

class EDA_Filters {

    private $current_product = null;
    private $custom_settings = null;
    private $settings_loaded = false;

    public function __construct() {
        // Hook prioritaire pour capturer le produit
        add_action('woocommerce_before_single_product', array($this, 'setup_product_context'), 1);
        add_action('woocommerce_after_shop_loop_item', array($this, 'setup_product_context'), 1);

        // Hooks pour intercepter les options AVANT leur lecture (pre_option)
        add_filter('pre_option__edw_days', array($this, 'filter_days'), 10, 1);
        add_filter('pre_option__edw_max_days', array($this, 'filter_max_days'), 10, 1);
        add_filter('pre_option__edw_days_outstock', array($this, 'filter_days_outstock'), 10, 1);
        add_filter('pre_option__edw_max_days_outstock', array($this, 'filter_max_days_outstock'), 10, 1);
        add_filter('pre_option__edw_days_backorders', array($this, 'filter_days_backorders'), 10, 1);
        add_filter('pre_option__edw_max_days_backorders', array($this, 'filter_max_days_backorders'), 10, 1);

        // Reset après affichage
        add_action('woocommerce_after_single_product', array($this, 'reset_context'), 999);
        add_action('woocommerce_after_shop_loop_item', array($this, 'reset_context'), 999);

        // Pour l'API AJAX
        add_action('wp_ajax_nopriv_edw_get_estimate_dates', array($this, 'setup_ajax_context'), 1);
        add_action('wp_ajax_edw_get_estimate_dates', array($this, 'setup_ajax_context'), 1);
    }

    /**
     * Configure le contexte du produit pour les requêtes AJAX
     */
    public function setup_ajax_context() {
        if(isset($_POST['product'])) {
            $product_id = absint($_POST['product']);
            $this->current_product = wc_get_product($product_id);
        }
    }

    /**
     * Configure le contexte du produit
     */
    public function setup_product_context() {
        global $product;

        if($product && is_object($product)) {
            $this->current_product = $product;
            $this->settings_loaded = false;
            $this->custom_settings = null;
        }
    }

    /**
     * Réinitialise le contexte
     */
    public function reset_context() {
        $this->current_product = null;
        $this->custom_settings = null;
        $this->settings_loaded = false;
    }

    /**
     * Obtient le produit actuel
     */
    private function get_current_product() {
        // Utiliser le produit stocké si disponible
        if($this->current_product) {
            return $this->current_product;
        }

        // Essayer le produit global
        global $product;
        if($product && is_object($product)) {
            $this->current_product = $product;
            return $this->current_product;
        }

        // Essayer depuis le post global
        global $post;
        if($post && $post->post_type === 'product') {
            $this->current_product = wc_get_product($post->ID);
            return $this->current_product;
        }

        // Essayer depuis l'ID de la page
        if(is_product()) {
            $this->current_product = wc_get_product(get_the_ID());
            return $this->current_product;
        }

        return null;
    }

    /**
     * Charge les paramètres personnalisés pour le produit actuel
     */
    private function load_custom_settings() {
        if($this->settings_loaded) {
            return;
        }

        $this->settings_loaded = true;
        $this->custom_settings = null;

        $product = $this->get_current_product();
        if(!$product) {
            return;
        }

        $product_id = $product->get_id();

        // Vérifier si le produit a une surcharge personnalisée activée
        $overwrite = get_post_meta($product_id, '_edw_overwrite', true);

        // AUSSI vérifier si le produit a des paramètres individuels configurés
        // Si _edw_days est configuré (même sans overwrite), on ne surcharge pas
        $product_has_own_days = get_post_meta($product_id, '_edw_days', true);

        if($overwrite == '1' || ($product_has_own_days !== '' && $product_has_own_days !== false)) {
            // Le produit a ses propres paramètres, ne pas appliquer nos filtres
            return;
        }

        // Ordre de priorité : Catégorie > Type de produit

        // 1. Vérifier les catégories du produit
        $categories_settings = get_option('eda_categories_settings', array());
        if(!empty($categories_settings)) {
            $product_categories = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'ids'));

            foreach($product_categories as $cat_id) {
                if(isset($categories_settings[$cat_id])) {
                    if($this->has_valid_settings($categories_settings[$cat_id])) {
                        $this->custom_settings = $categories_settings[$cat_id];
                        return;
                    }
                }
            }
        }

        // 2. Vérifier le type de produit
        $product_types_settings = get_option('eda_product_types_settings', array());
        $product_type = $product->get_type();

        if(isset($product_types_settings[$product_type])) {
            if($this->has_valid_settings($product_types_settings[$product_type])) {
                $this->custom_settings = $product_types_settings[$product_type];
                return;
            }
        }
    }

    /**
     * Vérifie si les paramètres sont valides
     */
    private function has_valid_settings($settings) {
        if(!is_array($settings)) {
            return false;
        }

        return isset($settings['days']) ||
               isset($settings['max_days']) ||
               isset($settings['days_outstock']) ||
               isset($settings['max_days_outstock']) ||
               isset($settings['days_backorders']) ||
               isset($settings['max_days_backorders']);
    }

    /**
     * Filtre les jours de livraison
     */
    public function filter_days($pre_option) {
        $this->load_custom_settings();

        if($this->custom_settings !== null && isset($this->custom_settings['days'])) {
            return strval($this->custom_settings['days']);
        }

        // Retourner false pour utiliser la valeur par défaut
        return false;
    }

    /**
     * Filtre les jours maximum de livraison
     */
    public function filter_max_days($pre_option) {
        $this->load_custom_settings();

        if($this->custom_settings !== null && isset($this->custom_settings['max_days'])) {
            return strval($this->custom_settings['max_days']);
        }

        return false;
    }

    /**
     * Filtre les jours de livraison hors stock
     */
    public function filter_days_outstock($pre_option) {
        $this->load_custom_settings();

        if($this->custom_settings !== null && isset($this->custom_settings['days_outstock'])) {
            return strval($this->custom_settings['days_outstock']);
        }

        return false;
    }

    /**
     * Filtre les jours maximum de livraison hors stock
     */
    public function filter_max_days_outstock($pre_option) {
        $this->load_custom_settings();

        if($this->custom_settings !== null && isset($this->custom_settings['max_days_outstock'])) {
            return strval($this->custom_settings['max_days_outstock']);
        }

        return false;
    }

    /**
     * Filtre les jours de livraison en précommande
     */
    public function filter_days_backorders($pre_option) {
        $this->load_custom_settings();

        if($this->custom_settings !== null && isset($this->custom_settings['days_backorders'])) {
            return strval($this->custom_settings['days_backorders']);
        }

        return false;
    }

    /**
     * Filtre les jours maximum de livraison en précommande
     */
    public function filter_max_days_backorders($pre_option) {
        $this->load_custom_settings();

        if($this->custom_settings !== null && isset($this->custom_settings['max_days_backorders'])) {
            return strval($this->custom_settings['max_days_backorders']);
        }

        return false;
    }
}

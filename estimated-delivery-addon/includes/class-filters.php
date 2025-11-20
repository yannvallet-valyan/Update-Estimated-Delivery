<?php
if(!defined('ABSPATH')) { exit; }

class EDA_Filters {

    private $current_product_id = null;
    private $filters_applied = false;

    public function __construct() {
        // Hook pour capturer le produit en cours
        add_filter('woocommerce_product_get_id', array($this, 'capture_product_id'), 10, 1);

        // Hooks pour modifier les options de délai selon le type/catégorie
        add_filter('option__edw_days', array($this, 'filter_days'), 999, 1);
        add_filter('option__edw_max_days', array($this, 'filter_max_days'), 999, 1);
        add_filter('option__edw_days_outstock', array($this, 'filter_days_outstock'), 999, 1);
        add_filter('option__edw_max_days_outstock', array($this, 'filter_max_days_outstock'), 999, 1);
        add_filter('option__edw_days_backorders', array($this, 'filter_days_backorders'), 999, 1);
        add_filter('option__edw_max_days_backorders', array($this, 'filter_max_days_backorders'), 999, 1);

        // Hook alternatif pour les produits affichés
        add_action('woocommerce_before_single_product', array($this, 'setup_product_filters'), 5);
        add_action('woocommerce_after_shop_loop_item', array($this, 'reset_filters'), 999);
        add_action('woocommerce_after_single_product', array($this, 'reset_filters'), 999);
    }

    /**
     * Capture l'ID du produit en cours
     */
    public function capture_product_id($product_id) {
        if($product_id && !$this->current_product_id) {
            $this->current_product_id = $product_id;
        }
        return $product_id;
    }

    /**
     * Configure les filtres pour le produit actuel
     */
    public function setup_product_filters() {
        global $product;
        if($product) {
            $this->current_product_id = $product->get_id();
        }
    }

    /**
     * Réinitialise les filtres après l'affichage du produit
     */
    public function reset_filters() {
        $this->current_product_id = null;
        $this->filters_applied = false;
    }

    /**
     * Obtient l'ID du produit actuel de différentes manières
     */
    private function get_current_product_id() {
        // Essayer d'obtenir depuis notre variable
        if($this->current_product_id) {
            return $this->current_product_id;
        }

        // Essayer d'obtenir depuis le produit global
        global $product;
        if($product && is_object($product)) {
            return $product->get_id();
        }

        // Essayer d'obtenir depuis le post global
        global $post;
        if($post && $post->post_type === 'product') {
            return $post->ID;
        }

        // Essayer d'obtenir depuis l'ID de post en cours
        if(is_product()) {
            return get_the_ID();
        }

        return null;
    }

    /**
     * Obtient les paramètres personnalisés pour un produit
     */
    private function get_custom_settings($product_id) {
        if(!$product_id) {
            return null;
        }

        // Vérifier si le produit a une surcharge personnalisée activée
        $overwrite = get_post_meta($product_id, '_edw_overwrite', true);
        if($overwrite == '1') {
            // Le produit a ses propres paramètres, ne pas appliquer nos filtres
            return null;
        }

        $product = wc_get_product($product_id);
        if(!$product) {
            return null;
        }

        // Ordre de priorité : Catégorie > Type de produit

        // 1. Vérifier les catégories du produit
        $categories_settings = get_option('eda_categories_settings', array());
        if(!empty($categories_settings)) {
            $product_categories = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'ids'));

            foreach($product_categories as $cat_id) {
                if(isset($categories_settings[$cat_id])) {
                    // On a trouvé une configuration pour cette catégorie
                    if($this->has_valid_settings($categories_settings[$cat_id])) {
                        return $categories_settings[$cat_id];
                    }
                }
            }
        }

        // 2. Vérifier le type de produit
        $product_types_settings = get_option('eda_product_types_settings', array());
        $product_type = $product->get_type();

        if(isset($product_types_settings[$product_type])) {
            if($this->has_valid_settings($product_types_settings[$product_type])) {
                return $product_types_settings[$product_type];
            }
        }

        return null;
    }

    /**
     * Vérifie si les paramètres sont valides (au moins un champ non vide)
     */
    private function has_valid_settings($settings) {
        if(!is_array($settings)) {
            return false;
        }

        return !empty($settings['days']) ||
               !empty($settings['max_days']) ||
               !empty($settings['days_outstock']) ||
               !empty($settings['max_days_outstock']) ||
               !empty($settings['days_backorders']) ||
               !empty($settings['max_days_backorders']);
    }

    /**
     * Filtre les jours de livraison
     */
    public function filter_days($value) {
        $product_id = $this->get_current_product_id();
        if(!$product_id) {
            return $value;
        }

        $custom_settings = $this->get_custom_settings($product_id);
        if($custom_settings && isset($custom_settings['days']) && $custom_settings['days'] !== '') {
            $this->filters_applied = true;
            return $custom_settings['days'];
        }

        return $value;
    }

    /**
     * Filtre les jours maximum de livraison
     */
    public function filter_max_days($value) {
        $product_id = $this->get_current_product_id();
        if(!$product_id) {
            return $value;
        }

        $custom_settings = $this->get_custom_settings($product_id);
        if($custom_settings && isset($custom_settings['max_days']) && $custom_settings['max_days'] !== '') {
            $this->filters_applied = true;
            return $custom_settings['max_days'];
        }

        return $value;
    }

    /**
     * Filtre les jours de livraison hors stock
     */
    public function filter_days_outstock($value) {
        $product_id = $this->get_current_product_id();
        if(!$product_id) {
            return $value;
        }

        $custom_settings = $this->get_custom_settings($product_id);
        if($custom_settings && isset($custom_settings['days_outstock']) && $custom_settings['days_outstock'] !== '') {
            $this->filters_applied = true;
            return $custom_settings['days_outstock'];
        }

        return $value;
    }

    /**
     * Filtre les jours maximum de livraison hors stock
     */
    public function filter_max_days_outstock($value) {
        $product_id = $this->get_current_product_id();
        if(!$product_id) {
            return $value;
        }

        $custom_settings = $this->get_custom_settings($product_id);
        if($custom_settings && isset($custom_settings['max_days_outstock']) && $custom_settings['max_days_outstock'] !== '') {
            $this->filters_applied = true;
            return $custom_settings['max_days_outstock'];
        }

        return $value;
    }

    /**
     * Filtre les jours de livraison en précommande
     */
    public function filter_days_backorders($value) {
        $product_id = $this->get_current_product_id();
        if(!$product_id) {
            return $value;
        }

        $custom_settings = $this->get_custom_settings($product_id);
        if($custom_settings && isset($custom_settings['days_backorders']) && $custom_settings['days_backorders'] !== '') {
            $this->filters_applied = true;
            return $custom_settings['days_backorders'];
        }

        return $value;
    }

    /**
     * Filtre les jours maximum de livraison en précommande
     */
    public function filter_max_days_backorders($value) {
        $product_id = $this->get_current_product_id();
        if(!$product_id) {
            return $value;
        }

        $custom_settings = $this->get_custom_settings($product_id);
        if($custom_settings && isset($custom_settings['max_days_backorders']) && $custom_settings['max_days_backorders'] !== '') {
            $this->filters_applied = true;
            return $custom_settings['max_days_backorders'];
        }

        return $value;
    }
}

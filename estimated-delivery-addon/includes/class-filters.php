<?php
if(!defined('ABSPATH')) { exit; }

class EDA_Filters {

    private $current_product = null;
    private $custom_settings = null;
    private $settings_loaded = false;
    private $debug_mode = false; // Mettre à true pour activer le debug

    public function __construct() {
        // Activer le debug avec le paramètre URL ?eda_debug=1
        if(isset($_GET['eda_debug']) && $_GET['eda_debug'] == '1' && current_user_can('manage_options')) {
            $this->debug_mode = true;
            add_action('wp_footer', array($this, 'display_debug_info'), 999);
        }
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

        // Si overwrite est explicitement activé, ne pas appliquer nos filtres
        if($overwrite == '1') {
            return;
        }

        // Vérifier si le produit a des paramètres individuels configurés
        // On considère qu'un produit a ses propres paramètres si au moins un champ a une valeur > 0
        $product_days = get_post_meta($product_id, '_edw_days', true);
        $product_max_days = get_post_meta($product_id, '_edw_max_days', true);
        $product_days_outstock = get_post_meta($product_id, '_edw_days_outstock', true);
        $product_max_days_outstock = get_post_meta($product_id, '_edw_max_days_outstock', true);
        $product_days_backorders = get_post_meta($product_id, '_edw_days_backorders', true);
        $product_max_days_backorders = get_post_meta($product_id, '_edw_max_days_backorders', true);

        // Si au moins un paramètre est configuré avec une valeur > 0, on considère que le produit a ses propres réglages
        $has_custom_settings = (
            (intval($product_days) > 0) ||
            (intval($product_max_days) > 0) ||
            (intval($product_days_outstock) > 0) ||
            (intval($product_max_days_outstock) > 0) ||
            (intval($product_days_backorders) > 0) ||
            (intval($product_max_days_backorders) > 0)
        );

        if($has_custom_settings) {
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

    /**
     * Affiche les informations de débogage
     */
    public function display_debug_info() {
        if(!$this->debug_mode || !is_product()) {
            return;
        }

        global $product;
        if(!$product) {
            return;
        }

        $product_id = $product->get_id();
        $product_type = $product->get_type();
        $categories = wp_get_post_terms($product_id, 'product_cat');

        // Récupérer les paramètres du produit
        $overwrite = get_post_meta($product_id, '_edw_overwrite', true);
        $days = get_post_meta($product_id, '_edw_days', true);
        $max_days = get_post_meta($product_id, '_edw_max_days', true);
        $days_outstock = get_post_meta($product_id, '_edw_days_outstock', true);
        $max_days_outstock = get_post_meta($product_id, '_edw_max_days_outstock', true);
        $days_backorders = get_post_meta($product_id, '_edw_days_backorders', true);
        $max_days_backorders = get_post_meta($product_id, '_edw_max_days_backorders', true);

        // Récupérer les paramètres de l'addon
        $product_types_settings = get_option('eda_product_types_settings', array());
        $categories_settings = get_option('eda_categories_settings', array());

        // Récupérer les options générales
        $general_days = get_option('_edw_days');
        $general_max_days = get_option('_edw_max_days');

        ?>
        <div style="background: #fff; border: 3px solid #ff0000; padding: 20px; margin: 20px; font-family: monospace; font-size: 12px;">
            <h2 style="color: #ff0000; margin-top: 0;">🐛 EDA DEBUG MODE</h2>

            <h3>📦 Produit</h3>
            <ul>
                <li><strong>ID:</strong> <?php echo $product_id; ?></li>
                <li><strong>Nom:</strong> <?php echo $product->get_name(); ?></li>
                <li><strong>Type:</strong> <?php echo $product_type; ?></li>
                <li><strong>Stock:</strong> <?php echo $product->is_in_stock() ? 'En stock' : 'Rupture'; ?></li>
            </ul>

            <h3>📂 Catégories</h3>
            <ul>
                <?php
                if($categories) {
                    foreach($categories as $cat) {
                        echo '<li>' . $cat->name . ' (ID: ' . $cat->term_id . ')';
                        if(isset($categories_settings[$cat->term_id])) {
                            echo ' <strong style="color: green;">✓ Configuré dans l\'addon</strong>';
                            echo '<pre>' . print_r($categories_settings[$cat->term_id], true) . '</pre>';
                        } else {
                            echo ' <span style="color: red;">✗ Non configuré</span>';
                        }
                        echo '</li>';
                    }
                } else {
                    echo '<li>Aucune catégorie</li>';
                }
                ?>
            </ul>

            <h3>🏷️ Type de Produit</h3>
            <ul>
                <li><strong>Type:</strong> <?php echo $product_type; ?>
                <?php
                if(isset($product_types_settings[$product_type])) {
                    echo ' <strong style="color: green;">✓ Configuré dans l\'addon</strong>';
                    echo '<pre>' . print_r($product_types_settings[$product_type], true) . '</pre>';
                } else {
                    echo ' <span style="color: red;">✗ Non configuré</span>';
                }
                ?>
                </li>
            </ul>

            <h3>⚙️ Paramètres du Produit</h3>
            <ul>
                <li><strong>Overwrite:</strong> <?php echo $overwrite == '1' ? '<span style="color: red;">OUI (priorité absolue)</span>' : 'Non'; ?></li>
                <li><strong>Days:</strong> <?php echo $days !== '' ? $days : 'vide'; ?></li>
                <li><strong>Max Days:</strong> <?php echo $max_days !== '' ? $max_days : 'vide'; ?></li>
                <li><strong>Days Outstock:</strong> <?php echo $days_outstock !== '' ? $days_outstock : 'vide'; ?></li>
                <li><strong>Max Days Outstock:</strong> <?php echo $max_days_outstock !== '' ? $max_days_outstock : 'vide'; ?></li>
                <li><strong>Days Backorders:</strong> <?php echo $days_backorders !== '' ? $days_backorders : 'vide'; ?></li>
                <li><strong>Max Days Backorders:</strong> <?php echo $max_days_backorders !== '' ? $max_days_backorders : 'vide'; ?></li>
            </ul>

            <h3>🌍 Paramètres Généraux (Plugin Principal)</h3>
            <ul>
                <li><strong>Days:</strong> <?php echo $general_days; ?></li>
                <li><strong>Max Days:</strong> <?php echo $general_max_days; ?></li>
            </ul>

            <h3>🎯 Paramètres Appliqués (Custom Settings)</h3>
            <?php
            $this->load_custom_settings();
            if($this->custom_settings !== null) {
                echo '<div style="background: #d4edda; padding: 10px; border-left: 4px solid #28a745;">';
                echo '<strong style="color: #28a745;">✓ ADDON APPLIQUÉ</strong>';
                echo '<pre>' . print_r($this->custom_settings, true) . '</pre>';
                echo '</div>';
            } else {
                echo '<div style="background: #f8d7da; padding: 10px; border-left: 4px solid #dc3545;">';
                echo '<strong style="color: #dc3545;">✗ ADDON NON APPLIQUÉ</strong>';

                // Expliquer pourquoi
                if($overwrite == '1') {
                    echo '<p>Raison: Le produit a "Overwrite" activé</p>';
                } else {
                    $has_custom = (
                        (intval($days) > 0) ||
                        (intval($max_days) > 0) ||
                        (intval($days_outstock) > 0) ||
                        (intval($max_days_outstock) > 0) ||
                        (intval($days_backorders) > 0) ||
                        (intval($max_days_backorders) > 0)
                    );

                    if($has_custom) {
                        echo '<p>Raison: Le produit a des paramètres individuels configurés (au moins un champ > 0)</p>';
                    } else {
                        echo '<p>Raison: Aucune configuration trouvée pour ce type/catégorie</p>';
                    }
                }
                echo '</div>';
            }
            ?>

            <p style="background: #fff3cd; padding: 10px; border-left: 4px solid #ffc107;">
                <strong>💡 Pour désactiver ce mode:</strong> Enlevez <code>?eda_debug=1</code> de l'URL
            </p>
        </div>
        <?php
    }
}

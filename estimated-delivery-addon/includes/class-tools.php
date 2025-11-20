<?php
if(!defined('ABSPATH')) { exit; }

/**
 * Outils de diagnostic et nettoyage
 * Accessible uniquement aux administrateurs
 */
class EDA_Tools {

    public function __construct() {
        // Ajouter une page d'outils dans le menu admin
        add_action('admin_menu', array($this, 'add_tools_page'), 100);

        // Traiter les actions de nettoyage
        add_action('admin_init', array($this, 'handle_cleanup'));
    }

    /**
     * Ajoute la page d'outils
     */
    public function add_tools_page() {
        add_submenu_page(
            'woocommerce',
            __('Outils Addon Delivery', 'estimated-delivery-addon'),
            __('Outils Addon Delivery', 'estimated-delivery-addon'),
            'manage_options',
            'eda-tools',
            array($this, 'render_tools_page')
        );
    }

    /**
     * Gère le nettoyage des métadonnées
     */
    public function handle_cleanup() {
        if(!isset($_POST['eda_cleanup_action']) || !current_user_can('manage_options')) {
            return;
        }

        if(!wp_verify_nonce($_POST['eda_cleanup_nonce'], 'eda_cleanup')) {
            return;
        }

        $action = sanitize_text_field($_POST['eda_cleanup_action']);

        switch($action) {
            case 'cleanup_single':
                $this->cleanup_single_product();
                break;
            case 'cleanup_all':
                $this->cleanup_all_products();
                break;
            case 'list_products':
                // Juste afficher la liste, pas d'action
                break;
        }
    }

    /**
     * Nettoie un seul produit
     */
    private function cleanup_single_product() {
        if(!isset($_POST['product_id'])) {
            return;
        }

        $product_id = absint($_POST['product_id']);

        if(!$product_id) {
            add_settings_error('eda_tools', 'invalid_id', __('ID de produit invalide', 'estimated-delivery-addon'), 'error');
            return;
        }

        $deleted = $this->delete_product_metadata($product_id);

        if($deleted > 0) {
            add_settings_error('eda_tools', 'success', sprintf(__('Métadonnées supprimées pour le produit #%d (%d entrées)', 'estimated-delivery-addon'), $product_id, $deleted), 'success');
        } else {
            add_settings_error('eda_tools', 'no_data', sprintf(__('Aucune métadonnée trouvée pour le produit #%d', 'estimated-delivery-addon'), $product_id), 'info');
        }
    }

    /**
     * Nettoie tous les produits
     */
    private function cleanup_all_products() {
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'fields' => 'ids',
        );

        $products = get_posts($args);
        $total_deleted = 0;
        $products_cleaned = 0;

        foreach($products as $product_id) {
            $deleted = $this->delete_product_metadata($product_id);
            if($deleted > 0) {
                $total_deleted += $deleted;
                $products_cleaned++;
            }
        }

        if($products_cleaned > 0) {
            add_settings_error('eda_tools', 'success', sprintf(__('%d produits nettoyés (%d métadonnées supprimées)', 'estimated-delivery-addon'), $products_cleaned, $total_deleted), 'success');
        } else {
            add_settings_error('eda_tools', 'no_data', __('Aucune métadonnée trouvée', 'estimated-delivery-addon'), 'info');
        }
    }

    /**
     * Supprime les métadonnées d'un produit
     */
    private function delete_product_metadata($product_id) {
        $meta_keys = array(
            '_edw_days',
            '_edw_max_days',
            '_edw_days_outstock',
            '_edw_max_days_outstock',
            '_edw_days_backorders',
            '_edw_max_days_backorders',
            '_edw_overwrite',
            '_edw_mode',
            '_edw_disabled_days',
        );

        $deleted = 0;
        foreach($meta_keys as $key) {
            $result = delete_post_meta($product_id, $key);
            if($result) {
                $deleted++;
            }
        }

        return $deleted;
    }

    /**
     * Récupère les produits avec métadonnées
     */
    private function get_products_with_metadata() {
        global $wpdb;

        $sql = "SELECT DISTINCT p.ID, p.post_title, p.post_type
                FROM {$wpdb->posts} p
                INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
                WHERE p.post_type = 'product'
                AND p.post_status = 'publish'
                AND pm.meta_key IN (
                    '_edw_days',
                    '_edw_max_days',
                    '_edw_days_outstock',
                    '_edw_max_days_outstock',
                    '_edw_days_backorders',
                    '_edw_max_days_backorders',
                    '_edw_overwrite'
                )
                ORDER BY p.post_title ASC";

        return $wpdb->get_results($sql);
    }

    /**
     * Affiche la page d'outils
     */
    public function render_tools_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

            <?php settings_errors('eda_tools'); ?>

            <div style="background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0;">
                <h3 style="margin-top: 0;">⚠️ Attention</h3>
                <p>Ces outils permettent de <strong>supprimer les paramètres individuels</strong> des produits pour forcer l'application des délais de type/catégorie.</p>
                <p><strong>Faites une sauvegarde avant d'utiliser ces outils !</strong></p>
            </div>

            <!-- Liste des produits avec métadonnées -->
            <div style="background: #fff; padding: 20px; margin: 20px 0; border: 1px solid #ccd0d4;">
                <h2>📋 Produits avec Paramètres Individuels</h2>
                <p>Ces produits ont des paramètres Estimated Delivery configurés individuellement :</p>

                <?php
                $products = $this->get_products_with_metadata();

                if(empty($products)) {
                    echo '<p style="color: green;"><strong>✓ Aucun produit n\'a de paramètres individuels</strong></p>';
                    echo '<p>Tous vos produits utilisent les paramètres de type/catégorie ou les paramètres généraux.</p>';
                } else {
                    echo '<p>Nombre de produits trouvés : <strong>' . count($products) . '</strong></p>';
                    echo '<table class="wp-list-table widefat fixed striped">';
                    echo '<thead><tr>';
                    echo '<th>ID</th>';
                    echo '<th>Nom du Produit</th>';
                    echo '<th>Actions</th>';
                    echo '</tr></thead>';
                    echo '<tbody>';

                    foreach($products as $product) {
                        echo '<tr>';
                        echo '<td>' . $product->ID . '</td>';
                        echo '<td><a href="' . get_edit_post_link($product->ID) . '" target="_blank">' . esc_html($product->post_title) . '</a></td>';
                        echo '<td>';
                        echo '<form method="post" style="display: inline;">';
                        wp_nonce_field('eda_cleanup', 'eda_cleanup_nonce');
                        echo '<input type="hidden" name="eda_cleanup_action" value="cleanup_single">';
                        echo '<input type="hidden" name="product_id" value="' . $product->ID . '">';
                        echo '<button type="submit" class="button" onclick="return confirm(\'Supprimer les paramètres de ce produit ?\')">Nettoyer</button>';
                        echo '</form>';
                        echo ' <a href="' . get_permalink($product->ID) . '?eda_debug=1" class="button" target="_blank">Debug</a>';
                        echo '</td>';
                        echo '</tr>';
                    }

                    echo '</tbody></table>';
                }
                ?>
            </div>

            <!-- Nettoyage global -->
            <div style="background: #fff; padding: 20px; margin: 20px 0; border: 1px solid #ccd0d4;">
                <h2>🧹 Nettoyage Global</h2>

                <form method="post">
                    <?php wp_nonce_field('eda_cleanup', 'eda_cleanup_nonce'); ?>
                    <input type="hidden" name="eda_cleanup_action" value="cleanup_all">

                    <p><strong>Supprimer tous les paramètres individuels de tous les produits</strong></p>
                    <p>Cette action va :</p>
                    <ul>
                        <li>✓ Supprimer tous les paramètres Estimated Delivery de tous les produits</li>
                        <li>✓ Forcer l'utilisation des délais de type/catégorie</li>
                        <li>⚠️ Cette action est <strong>irréversible</strong></li>
                    </ul>

                    <button type="submit" class="button button-primary button-large"
                            onclick="return confirm('ATTENTION : Cette action va supprimer les paramètres de TOUS les produits.\n\nÊtes-vous sûr(e) ?')">
                        🧹 Nettoyer Tous les Produits
                    </button>
                </form>
            </div>

            <!-- Aide -->
            <div style="background: #e7f3ff; padding: 20px; margin: 20px 0; border-left: 4px solid #007cba;">
                <h3 style="margin-top: 0;">💡 Comment ça fonctionne ?</h3>

                <p><strong>Ordre de priorité :</strong></p>
                <ol>
                    <li>Paramètres du produit individuel (si configurés)</li>
                    <li>Paramètres de la catégorie (addon)</li>
                    <li>Paramètres du type de produit (addon)</li>
                    <li>Paramètres généraux</li>
                </ol>

                <p><strong>Pour qu'un produit utilise les délais de type/catégorie :</strong></p>
                <ul>
                    <li>Le produit NE DOIT PAS avoir de paramètres individuels</li>
                    <li>La catégorie OU le type du produit doit être configuré dans l'addon</li>
                </ul>

                <p><strong>Mode Debug :</strong></p>
                <p>Ajoutez <code>?eda_debug=1</code> à l'URL d'un produit pour voir tous les détails.</p>
            </div>
        </div>
        <?php
    }
}

// Initialiser seulement si admin
if(is_admin()) {
    new EDA_Tools();
}

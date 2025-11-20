<?php
if(!defined('ABSPATH')) { exit; }
?>

<div class="wrap eda-settings-wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <div class="eda-notice">
        <strong><?php _e('📌 Comment ça fonctionne :', 'estimated-delivery-addon'); ?></strong>
        <p><?php _e('Ce plugin permet de définir des délais de livraison spécifiques selon le type de produit (simple, variable, etc.) ou la catégorie du produit. Ces délais s\'appliquent automatiquement si le produit correspond au type ou à la catégorie configurée.', 'estimated-delivery-addon'); ?></p>
        <p><?php _e('💡 Ordre de priorité : Produit individuel > Catégorie > Type de produit > Paramètres généraux', 'estimated-delivery-addon'); ?></p>
    </div>

    <form method="post" action="">
        <?php wp_nonce_field('eda_save_settings', 'eda_nonce'); ?>

        <!-- Section Types de Produits -->
        <div class="eda-section">
            <h2><?php _e('🏷️ Délais par Type de Produit', 'estimated-delivery-addon'); ?></h2>
            <p class="description">
                <?php _e('Configurez les délais de livraison pour chaque type de produit WooCommerce.', 'estimated-delivery-addon'); ?>
            </p>

            <table class="eda-table">
                <thead>
                    <tr>
                        <th style="width: 20%;"><?php _e('Type de Produit', 'estimated-delivery-addon'); ?></th>
                        <th><?php _e('Jours (En stock)', 'estimated-delivery-addon'); ?></th>
                        <th><?php _e('Jours Max', 'estimated-delivery-addon'); ?></th>
                        <th><?php _e('Jours (Rupture)', 'estimated-delivery-addon'); ?></th>
                        <th><?php _e('Jours Max (Rupture)', 'estimated-delivery-addon'); ?></th>
                        <th><?php _e('Jours (Précommande)', 'estimated-delivery-addon'); ?></th>
                        <th><?php _e('Jours Max (Précommande)', 'estimated-delivery-addon'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $product_types = EDA_Admin::get_product_types();
                    foreach($product_types as $type_key => $type_name):
                        $settings = isset($product_types_settings[$type_key]) ? $product_types_settings[$type_key] : array();
                    ?>
                    <tr>
                        <td><strong><?php echo esc_html($type_name); ?></strong></td>
                        <td>
                            <input type="number"
                                   name="eda_product_types[<?php echo esc_attr($type_key); ?>][days]"
                                   value="<?php echo isset($settings['days']) ? esc_attr($settings['days']) : ''; ?>"
                                   min="0"
                                   max="99999"
                                   class="eda-input"
                                   placeholder="0" />
                        </td>
                        <td>
                            <input type="number"
                                   name="eda_product_types[<?php echo esc_attr($type_key); ?>][max_days]"
                                   value="<?php echo isset($settings['max_days']) ? esc_attr($settings['max_days']) : ''; ?>"
                                   min="0"
                                   max="99999"
                                   class="eda-input"
                                   placeholder="0" />
                        </td>
                        <td>
                            <input type="number"
                                   name="eda_product_types[<?php echo esc_attr($type_key); ?>][days_outstock]"
                                   value="<?php echo isset($settings['days_outstock']) ? esc_attr($settings['days_outstock']) : ''; ?>"
                                   min="0"
                                   max="99999"
                                   class="eda-input"
                                   placeholder="0" />
                        </td>
                        <td>
                            <input type="number"
                                   name="eda_product_types[<?php echo esc_attr($type_key); ?>][max_days_outstock]"
                                   value="<?php echo isset($settings['max_days_outstock']) ? esc_attr($settings['max_days_outstock']) : ''; ?>"
                                   min="0"
                                   max="99999"
                                   class="eda-input"
                                   placeholder="0" />
                        </td>
                        <td>
                            <input type="number"
                                   name="eda_product_types[<?php echo esc_attr($type_key); ?>][days_backorders]"
                                   value="<?php echo isset($settings['days_backorders']) ? esc_attr($settings['days_backorders']) : ''; ?>"
                                   min="0"
                                   max="99999"
                                   class="eda-input"
                                   placeholder="0" />
                        </td>
                        <td>
                            <input type="number"
                                   name="eda_product_types[<?php echo esc_attr($type_key); ?>][max_days_backorders]"
                                   value="<?php echo isset($settings['max_days_backorders']) ? esc_attr($settings['max_days_backorders']) : ''; ?>"
                                   min="0"
                                   max="99999"
                                   class="eda-input"
                                   placeholder="0" />
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p class="eda-help">
                <?php _e('💡 Laissez vide ou à 0 pour utiliser les paramètres par défaut. "Jours Max" à 0 désactive la plage (affiche une date unique).', 'estimated-delivery-addon'); ?>
            </p>
        </div>

        <!-- Section Catégories de Produits -->
        <div class="eda-section">
            <h2><?php _e('📂 Délais par Catégorie de Produit', 'estimated-delivery-addon'); ?></h2>
            <p class="description">
                <?php _e('Configurez les délais de livraison pour chaque catégorie de produit.', 'estimated-delivery-addon'); ?>
            </p>

            <?php
            $categories = EDA_Admin::get_product_categories();
            if(empty($categories)):
            ?>
                <p><?php _e('Aucune catégorie de produit trouvée. Créez d\'abord des catégories dans WooCommerce.', 'estimated-delivery-addon'); ?></p>
            <?php else: ?>

            <table class="eda-table">
                <thead>
                    <tr>
                        <th style="width: 20%;"><?php _e('Catégorie', 'estimated-delivery-addon'); ?></th>
                        <th><?php _e('Jours (En stock)', 'estimated-delivery-addon'); ?></th>
                        <th><?php _e('Jours Max', 'estimated-delivery-addon'); ?></th>
                        <th><?php _e('Jours (Rupture)', 'estimated-delivery-addon'); ?></th>
                        <th><?php _e('Jours Max (Rupture)', 'estimated-delivery-addon'); ?></th>
                        <th><?php _e('Jours (Précommande)', 'estimated-delivery-addon'); ?></th>
                        <th><?php _e('Jours Max (Précommande)', 'estimated-delivery-addon'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach($categories as $cat_id => $cat_name):
                        $settings = isset($categories_settings[$cat_id]) ? $categories_settings[$cat_id] : array();
                    ?>
                    <tr>
                        <td><strong><?php echo esc_html($cat_name); ?></strong></td>
                        <td>
                            <input type="number"
                                   name="eda_categories[<?php echo esc_attr($cat_id); ?>][days]"
                                   value="<?php echo isset($settings['days']) ? esc_attr($settings['days']) : ''; ?>"
                                   min="0"
                                   max="99999"
                                   class="eda-input"
                                   placeholder="0" />
                        </td>
                        <td>
                            <input type="number"
                                   name="eda_categories[<?php echo esc_attr($cat_id); ?>][max_days]"
                                   value="<?php echo isset($settings['max_days']) ? esc_attr($settings['max_days']) : ''; ?>"
                                   min="0"
                                   max="99999"
                                   class="eda-input"
                                   placeholder="0" />
                        </td>
                        <td>
                            <input type="number"
                                   name="eda_categories[<?php echo esc_attr($cat_id); ?>][days_outstock]"
                                   value="<?php echo isset($settings['days_outstock']) ? esc_attr($settings['days_outstock']) : ''; ?>"
                                   min="0"
                                   max="99999"
                                   class="eda-input"
                                   placeholder="0" />
                        </td>
                        <td>
                            <input type="number"
                                   name="eda_categories[<?php echo esc_attr($cat_id); ?>][max_days_outstock]"
                                   value="<?php echo isset($settings['max_days_outstock']) ? esc_attr($settings['max_days_outstock']) : ''; ?>"
                                   min="0"
                                   max="99999"
                                   class="eda-input"
                                   placeholder="0" />
                        </td>
                        <td>
                            <input type="number"
                                   name="eda_categories[<?php echo esc_attr($cat_id); ?>][days_backorders]"
                                   value="<?php echo isset($settings['days_backorders']) ? esc_attr($settings['days_backorders']) : ''; ?>"
                                   min="0"
                                   max="99999"
                                   class="eda-input"
                                   placeholder="0" />
                        </td>
                        <td>
                            <input type="number"
                                   name="eda_categories[<?php echo esc_attr($cat_id); ?>][max_days_backorders]"
                                   value="<?php echo isset($settings['max_days_backorders']) ? esc_attr($settings['max_days_backorders']) : ''; ?>"
                                   min="0"
                                   max="99999"
                                   class="eda-input"
                                   placeholder="0" />
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p class="eda-help">
                <?php _e('💡 Laissez vide ou à 0 pour utiliser les paramètres par défaut. Si un produit a plusieurs catégories, la première catégorie configurée sera utilisée.', 'estimated-delivery-addon'); ?>
            </p>

            <?php endif; ?>
        </div>

        <p class="submit">
            <input type="submit"
                   name="eda_save_settings"
                   class="button button-primary"
                   value="<?php _e('Enregistrer les paramètres', 'estimated-delivery-addon'); ?>" />
        </p>
    </form>

    <!-- Section d'aide -->
    <div class="eda-section">
        <h2><?php _e('❓ Aide et Documentation', 'estimated-delivery-addon'); ?></h2>

        <h3><?php _e('Ordre de priorité des paramètres', 'estimated-delivery-addon'); ?></h3>
        <ol>
            <li><strong><?php _e('Paramètres du produit individuel', 'estimated-delivery-addon'); ?></strong> - <?php _e('Si configuré dans l\'onglet "Estimated Delivery" du produit', 'estimated-delivery-addon'); ?></li>
            <li><strong><?php _e('Paramètres de la catégorie', 'estimated-delivery-addon'); ?></strong> - <?php _e('Si configuré ici pour la catégorie du produit', 'estimated-delivery-addon'); ?></li>
            <li><strong><?php _e('Paramètres du type de produit', 'estimated-delivery-addon'); ?></strong> - <?php _e('Si configuré ici pour le type du produit', 'estimated-delivery-addon'); ?></li>
            <li><strong><?php _e('Paramètres généraux', 'estimated-delivery-addon'); ?></strong> - <?php _e('Paramètres par défaut définis dans "Estimated Delivery"', 'estimated-delivery-addon'); ?></li>
        </ol>

        <h3><?php _e('Exemples d\'utilisation', 'estimated-delivery-addon'); ?></h3>
        <ul>
            <li><strong><?php _e('Produits volumineux', 'estimated-delivery-addon'); ?></strong> : <?php _e('Créez une catégorie "Produits volumineux" et définissez un délai de 7-10 jours', 'estimated-delivery-addon'); ?></li>
            <li><strong><?php _e('Produits sur mesure', 'estimated-delivery-addon'); ?></strong> : <?php _e('Définissez un délai plus long pour les produits variables (configurables)', 'estimated-delivery-addon'); ?></li>
            <li><strong><?php _e('Produits importés', 'estimated-delivery-addon'); ?></strong> : <?php _e('Créez une catégorie "Import" avec un délai de 14-21 jours', 'estimated-delivery-addon'); ?></li>
        </ul>
    </div>
</div>

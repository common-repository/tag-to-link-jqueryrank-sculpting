<?php
// Mise à jour des données par défaut
function WP_ElementToLink_update() {
	global $wpdb, $table_WP_ElementToLink; // insérer les variables globales

	$wp_ElementToLink_source	= $_POST['wp_ElementToLink_source'];
	$wp_ElementToLink_attr		= $_POST['wp_ElementToLink_attr'];
	$wp_ElementToLink_evttag	= $_POST['wp_ElementToLink_evttag'];
	$wp_ElementToLink_newclass	= $_POST['wp_ElementToLink_newclass'];

	$wp_ElementToLink_update = $wpdb->update(
		$table_WP_ElementToLink,
		array(
			"selector" => $wp_ElementToLink_source,
			"attribute" => $wp_ElementToLink_attr,
			"evttag" => $wp_ElementToLink_evttag,
			"newclass" => $wp_ElementToLink_newclass
		), 
		array('id' => 1)
	);
}

// Fonction d'affichage de la page d'aide et de réglages de l'extension
function WP_ElementToLink_Callback() {
	global $wpdb, $table_WP_ElementToLink; // insérer les variables globales
	
	// Déclencher la fonction de mise à jour (upload)
	if(isset($_POST['wp_ElementToLink_action']) && $_POST['wp_ElementToLink_action'] == __('Enregistrer' , 'WP-ElementToLink')) {
		WP_ElementToLink_update();
	}

	/* --------------------------------------------------------------------- */
	/* ------------------------ Affichage de la page ----------------------- */
	/* --------------------------------------------------------------------- */
	echo '<div class="wrap">';
	echo '<div id="icon-options-general" class="icon32"><br /></div>';
	echo '<h2>'; _e('Réglages de Tag-to-Link - jQueryRank sculpting','WP-ElementToLink'); echo '</h2><br/>';
	_e('<strong>Tag-to-Link</strong> permet de modifier n\'importe quelle balise HTML en lien afin d\'éviter un surnombre de liens dans les pages web et donc une division peu avantageuse du PageRank', 'WP-ElementToLink'); echo '<br/>';
	_e('L\'extension règle ainsi les problèmes de <strong>PageRank Sculpting</strong> mais aussi la gestion des ancres de liens de manière propre et discrète aux yeux des moteurs de recherche.', 'WP-ElementToLink');	echo '<br/><br/>';
    _e('L\'utilisation est simple :','WP-ElementToLink');
	echo '<ol>';
	echo '<li>'; _e('<strong>ajoutez la classe CSS</strong> paramétrée aux balises HTML qui doivent se transformer en liens','WP-ElementToLink'); echo '</li>';
	echo '<li>'; _e('<strong>ajoutez un attribut</strong> au choix ("title" par défaut car polyvalent) pour noter les URL finales qui feront office de liens','WP-ElementToLink'); echo '</li>';
	echo '<li>'; _e('<strong>Choisissez l\'action de la souris</strong> qui active la transformation ainsi que la classe CSS ajoutée au lien final','WP-ElementToLink'); echo '</li>';
	echo '</ol>';
	echo '<h3>'; _e('Exemple d\'usage', 'WP-ElementToLink'); echo '</h3>';
	_e('<em>&lsaquo;span title="http://www.monsite.com" class="linktoggle"&rsaquo;Ancre du lien&lsaquo;/span&rsaquo;</em>', 'WP-ElementToLink');
	_e('<br/>devient après un survol de la souris :<br/>', 'WP-ElementToLink');
	_e('<em>&lsaquo;a href="http://www.monsite.com" class="newlink"&rsaquo;Ancre du lien&lsaquo;/a&rsaquo;</em>', 'WP-ElementToLink');
	echo '<br/><br/>';

	// Formulaire de configuration du Shortcode
	echo '<h2>'; _e('Paramètres de l\'extension','WP-ElementToLink'); echo '</h2>';

		// Sélection des données dans la base de données		
		$select = $wpdb->get_row("SELECT * FROM $table_WP_ElementToLink WHERE id=1");
?>
        <form method="post" action="">
        <p>
			<label for="wp_ElementToLink_source"><strong><?php _e('Classe du sélecteur jQuery','WP-ElementToLink'); ?></strong></label><br />
	        <input value="<?php echo $select->selector; ?>" name="wp_ElementToLink_source" id="wp_ElementToLink_source" type="text" style="width:20%;border:1px solid #ccc;" />
        </p>
        <p>
			<label for="wp_ElementToLink_attr"><strong><?php _e('Attribut contenant l\'URL finale','WP-ElementToLink'); ?></strong></label><br />
	        <input value="<?php echo $select->attribute; ?>" name="wp_ElementToLink_attr" id="wp_ElementToLink_attr" type="text" style="width:20%;border:1px solid #ccc;" />
        </p>
        <p>
			<label for="wp_ElementToLink_evttag"><strong><?php _e('&Eacute;vénement (action avec la souris)','WP-ElementToLink'); ?></strong></label><br />
            <select name="wp_ElementToLink_evttag" id="wp_ElementToLink_evttag" style="width:20%;border:1px solid #ccc;">
            	<option value="hover" <?php if($select->evttag == 'hover') { echo 'selected="selected"'; } ?>><?php _e('au survol','WP-Planification'); ?></option>
                <option value="click" <?php if($select->evttag == 'click') { echo 'selected="selected"'; } ?>><?php _e('au clic','WP-Planification'); ?></option>
                <option value="dblclick" <?php if($select->evttag == 'dblclick') { echo 'selected="selected"'; } ?>><?php _e('au double clic','WP-Planification'); ?></option>
            </select>
        </p>
        <p>
			<label for="wp_ElementToLink_newclass"><strong><?php _e('Classe CSS pour les liens transformés','WP-ElementToLink'); ?></strong></label><br />
	        <input value="<?php echo $select->newclass; ?>" name="wp_ElementToLink_newclass" id="wp_ElementToLink_newclass" type="text" style="width:20%;border:1px solid #ccc;" />
        </p>
        <p class="submit"><input type="submit" name="wp_ElementToLink_action" class="button-primary" value="<?php _e('Enregistrer' , 'WP-ElementToLink'); ?>" /></p>
        </form>
<?php
	echo '</div>'; // Fin de la page d'admin
} // Fin de la fonction Callback
?>
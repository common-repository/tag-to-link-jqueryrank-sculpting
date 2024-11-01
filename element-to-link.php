<?php
/*
Plugin Name: Tag-to-Link (jQueryRank sculpting)
Plugin URI: http://blog.internet-formation.fr/2013/05/jqueryrank-sculpting-modifier-les-balises-en-liens-elementtoa/
Description:Extension permettant de transformer toute balise HTML en lien pour favoriser le <strong>PageRank Sculpting</strong> à l'aide d'une classe et d'un attribut <em>(Nécessite jQuery (fourni) pour fonctionner)</em> / Plugin used to transform any HTML Tag in hyperlink to improve <strong>PageRank Sculpting</strong> using a class and an attribute <em>(Requires jQuery (included) to work)</em>
Author: Mathieu Chartier
Version: 3.0
Author URI: http://blog.internet-formation.fr
*/

// Instanciation des variables globales
global $wpdb, $table_WP_ElementToLink;
$table_WP_ElementToLink = $wpdb->prefix.'elmtToLink';

// Gestion des langues
function WP_ElementToLink_Lang() {
   $path = dirname(plugin_basename(__FILE__)).'/lang/';
   load_plugin_textdomain('WP-ElementToLink', NULL, $path);
}
add_action('plugins_loaded', 'WP_ElementToLink_Lang');

// Fonction lancée lors de l'activation ou de la desactivation de l'extension
register_activation_hook( __FILE__, 'WP_ElementToLink_install' );
register_deactivation_hook( __FILE__, 'WP_ElementToLink_desinstall' );

function WP_ElementToLink_install() {
	global $wpdb, $table_WP_ElementToLink;

	// Création de la table de base
	$sql = "CREATE TABLE $table_WP_ElementToLink (
		id INT(3) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		selector VARCHAR( 30 ) NOT NULL, 
		attribute VARCHAR( 30 ) NOT NULL,
		evttag VARCHAR( 20 ) NOT NULL,
		newclass VARCHAR( 30 ) NOT NULL
		);";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	
	// Insertion de valeurs par défaut (premier enregistrement)
	$defaut = array(
		"selector" => "linktoggle",
		"attribute" => "title",
		"evttag" => "hover",
		"newclass" => "newlink"
	);
	$champ = wp_parse_args($instance, $defaut);
	
	$default = $wpdb->insert($table_WP_ElementToLink, array('selector' => $champ['selector'], 'attribute' => $champ['attribute'], 'evttag' => $champ['evttag'], 'newclass' => $champ['newclass']));
}
function WP_ElementToLink_desinstall() {
	global $wpdb, $table_WP_ElementToLink;
	// Suppression de la table de base
	$wpdb->query("DROP TABLE IF EXISTS $table_WP_ElementToLink");
}

// Ajout d'une page de sous-menu
function WP_ElementToLink_admin() {
	$parent_slug	= 'options-general.php';								// Page dans laquelle est ajoutée le sous-menu
	$page_title		= 'Réglages : Tag-to-Link - jQueryRank sculpting';		// Titre interne à la page de réglages
	$menu_title		= 'Tag To Link';										// Titre du sous-menu
	$capability		= 'manage_options';										// Rôle d'administration qui a accès au sous-menu
	$menu_slug		= 'tag-to-link';										// Alias (slug) de la page
	$function		= 'WP_ElementToLink_Callback';							// Fonction appelé pour afficher la page de réglages
	add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
}
add_action('admin_menu', 'WP_ElementToLink_admin');

// Fichier des options et réflages
include 'element-to-link-options.php';

function WP_ElementToLink_front() {
	global $wpdb, $table_WP_ElementToLink; // insérer les variables globales
	$select = $wpdb->get_row("SELECT * FROM $table_WP_ElementToLink WHERE id=1");
?>
<script type="application/javascript">
source = '<?php echo $select->selector; ?>';
attribut = '<?php echo $select->attribute; ?>';
newclass = '<?php echo $select->newclass; ?>';
evttag = '<?php echo $select->evttag; ?>';
</script>
<?php
}
add_action('wp_head','WP_ElementToLink_front');

// Ajout conditionné d'une feuille de style personnalisée
function WP_ElementToLink_JS() {
	$url = plugins_url('ElementToA-3.0.min.js',__FILE__);
	wp_enqueue_script('TagToLink', $url, array('jquery'), 1.0, true);
}
add_action('wp_enqueue_scripts', 'WP_ElementToLink_JS');
?>
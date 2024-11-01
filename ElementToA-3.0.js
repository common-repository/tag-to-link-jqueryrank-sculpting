// Source de la fonction : http://phpjs.org/functions/strtr/
function strtr(str, from, to) {
var fr = '',
i = 0,
j = 0,
lenStr = 0,
lenFrom = 0,
tmpStrictForIn = false,
fromTypeStr = '',
toTypeStr = '',
istr = '';
var tmpFrom = [];
var tmpTo = [];
var ret = '';
var match = false;

	if (typeof from === 'object') {
		tmpStrictForIn = this.ini_set('phpjs.strictForIn', false);
		from = this.krsort(from);
		this.ini_set('phpjs.strictForIn', tmpStrictForIn);
	
		for (fr in from) {
			if(from.hasOwnProperty(fr)) {
				tmpFrom.push(fr);
				tmpTo.push(from[fr]);
			}
		}
		from = tmpFrom;
		to = tmpTo;
	}

	lenStr = str.length;
	lenFrom = from.length;
	fromTypeStr = typeof from === 'string';
	toTypeStr = typeof to === 'string';

	for (i = 0; i < lenStr; i++) {
    	match = false;
    	if (fromTypeStr) {
			istr = str.charAt(i);
			for (j = 0; j < lenFrom; j++) {
				if (istr == from.charAt(j)) {
				match = true;
				break;
				}
			}
    	} else {
			for (j = 0; j < lenFrom; j++) {
				if (str.substr(i, from[j].length) == from[j]) {
					match = true;
					// Fast forward
					i = (i + from[j].length) - 1;
					break;
				}
			}
		}
	    if (match) {
    		ret += toTypeStr ? to.charAt(j) : to[j];
	    } else {
			ret += str.charAt(i);
		}
	}
	return ret;
}

(function($eta) {
$eta(document).ready(function() {
	// Cryptage et décryptage des URL
	$eta.fn.wwwtostr = function(contenu) {
		string		= "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_!$/*+&#?:.0123456789";
		stringNew	= "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!+-_$/*&#?:.";
		var returnwww = strtr(contenu, string, stringNew);
		
		var tab = new Array();
		for (var i=0; i < returnwww.length; i++) {
			tab[i] = returnwww.substring(i,i+1);
		}
		tab.reverse();
		var result = tab.join("");
		return result;
	};
	$eta.fn.strtowww = function(contenu) {
		string		= "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_!$/*+&#?:.0123456789";
		stringNew	= "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!+-_$/*&#?:.";
		var returnwww = strtr(contenu, stringNew, string);
		var tab = new Array();
		for (var i=0; i < returnwww.length; i++) {
			tab[i] = returnwww.substring(i,i+1);
		}
		tab.reverse();
		var result = tab.join("");
		return result;
	};
	// Cryptage au démarrage
	$eta("."+source).attr(attribut, function() {
		var localUrl = $eta(this).attr(attribut);
		if(localUrl !== undefined) {
			var wwwModif = $eta(this).wwwtostr(localUrl);
			$eta(this).attr({title: wwwModif});
		}
	});

	// Choix de l'événement Javascript automatique
	if(evttag == 'hover') {evenement='hover';evtjavascript='onmouseover';evtjavascript2='onmouseout';} else
	if(evttag == 'click') {evenement='click';evtjavascript='onclick';evtjavascript2='onmouseout';} else
	if(evttag == 'dblclick') {evenement='dblclick';evtjavascript='ondblclick';evtjavascript2='onmouseout';} else
	{evenement='hover';evtjavascript='onmouseover';evtjavascript2='onmouseout';}
	
	// Premier survol : on remplace le <span> survolé par un lien <a>
	// Cette initialisation évite d'appliquer les remplacements quand des <a> classiques sont en place (on se limite aux <span> ici)
	$eta(document).on("mouseenter", '.'+source, function() {
		// Variables globales (obligatoires)
		// on adapte la modification en <a> en fonction des éléments d'origine (<span>, <h2>, <div>...) qui portent la class ".linktoggle"
		ElmtToggle = $eta(this).get(0).tagName.toLowerCase();
		AttrSource = $eta(this).attr(attribut);

		// on récupère les attributs existants dans la balise d'origine (rel, title, class, id...)
		var arrayAttrs = [];
		for (var i=0, attrs=$eta(this).get(0).attributes, nb=attrs.length; i < nb; i++){
			arrayAttrs.push(attrs.item(i).nodeName+'="'+attrs.item(i).nodeValue+'"');
		}
		Attributs = arrayAttrs.join(" "); // on enregistre tous les attributs et leurs valeurs dans une chaine --> variable globale

		var TexteSpan = $eta(this).text(); // on mémorise le texte contenu dans le lien
		var TexteTitle = $eta(this).strtowww(AttrSource); // on enregistre le texte contenu dans l'attribut title --> href du lien
		
		// on utilise replaceWith() plutôt que html() car elle remplace totalement les balises, elles ne les ajoutent pas --> problèmes sinon !
		// on génère un appel vers une fonction qui va permettre de remettre le <span> quand il n'y a plus de survol
		$eta(this).replaceWith('<a href="'+TexteTitle+'" class="'+newclass+'">'+TexteSpan+'<\/a>'); // on l'intègre dans des <a>
		return false;
    });
	$eta(document).on("mouseleave", '.'+newclass, function() {	
		var TexteA = $eta(this).text(); // on enregistre le texte contenu dans le lien
		var TexteHREF = $eta(this).attr('href'); // on enregistre le texte contenu dans l'attribut href --> title du <span>
		
		// on utilise replaceWith() plutôt que html() car elle remplace totalement les balises, elles ne les ajoutent pas --> problèmes sinon !
		// on génère un appel vers une fonction qui va permettre de remettre le <span> quand il n'y a plus de survol
		$eta(this).replaceWith('<'+ElmtToggle+' '+Attributs+'>'+TexteA+'<\/'+ElmtToggle+'>'); // on l'intègre dans des <a>
		return false;
    });
});
})(jQuery);
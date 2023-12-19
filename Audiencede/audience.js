// Calcul du tableau des noms des convoqués, sans doublons, et trié par ordre alphabétique
// par lecture du tableau HTML "CONVOQUES"
var convoques = [];

function extraireConvoques() {
	convoques = [];
	var SELECTs = document.querySelectorAll(".convoques SELECT");
	SELECTs.forEach(function(SELECT) {
		var OPTION = SELECT.options[SELECT.selectedIndex];
		// exclut "---"
		if (OPTION.value !== "nemo") {
			// exclut les doublons
			if (convoques.indexOf(OPTION.textContent) === -1) {
				convoques.push(OPTION.textContent);
			}
		}
	});
	// tri
	convoques.sort();
}

extraireConvoques();

// Calcul du tableau des noms des exempts trié par ordre alphabétique
// par lecture du tableau HTML "EXEMPTS"
var exempts = [];

function extraireExempts() {
	exempts = [];
	var TDs = document.querySelectorAll("TD.exempts");
	TDs.forEach(function(TD) {
		var TEXT = TD.textContent;
		if (TEXT !== "") {
			exempts.push(TEXT);
		}
	});
	exempts.sort();
}

extraireExempts();

// Calcul du tableau des noms des personnes convoquables, trié par ordre
// alphabétique, par "union" des tableaux convoques et exempts
var convoquables = [];

function calculerConvoquables() {
	convoquables = [];
	convoquables = convoques.concat(exempts);
	convoquables.sort();
}

calculerConvoquables();

//Masquer les images des exempts, afficher les images des convoques
function gererImages() {
	convoques.forEach(function(c) {
		var title = c.toLowerCase();
		document.querySelector("img[title=" + title + "]").style.visibility = "visible";
	});
	exempts.forEach(function(e) {
		var title = e.toLowerCase();
		document.querySelector("img[title=" + title + "]").style.visibility = "hidden";
	});
}

gererImages();

// Mise à jour du tableau exempts à partir des tableaux convoquables et
// convoques
function mettreAJourExempts() {
	exempts = [];
	convoquables.forEach(function(c) {
		if (convoques.indexOf(c) === -1) {
			exempts.push(c);
		}
	});
	exempts.sort();
}

// Mise à jour du tableau HTML EXEMPTS à partir du tableau exempts
function regenererTableExempts() {
	TDs = document.querySelectorAll("TD.exempts");
	for (let t = 0; t < TDs.length; t++) {
		if (t < exempts.length) {
			TDs[t].textContent = exempts[t];
		} else {
			TDs[t].textContent = "";
		}
	}
}

/* NON DEMANDE
// Alerte en cas de doublon
function doublons(nom) {
	if (convoques.indexOf(nom) !== -1) {
		alert("Attention : " + nom + " est déjà convoqué !");
		return false;
	} else {
		return true;
	}
}
*/

// Gestion de modification de convocation
var SELECTs = document.querySelectorAll(".convoques SELECT");
SELECTs.forEach(function(SELECT) {
	SELECT.addEventListener("change", function(e) {
		/* NON DEMANDE
		var SELECT = e.target;
		var OPTION = SELECT.options[SELECT.selectedIndex];
		if (OPTION.value !== "nemo") {
			doublons(OPTION.textContent);
		}
		/**/
		extraireConvoques();
		mettreAJourExempts();
		regenererTableExempts();
		gererImages();
	});
});

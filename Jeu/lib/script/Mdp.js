(function() { // On utilise une IEF pour ne pas polluer l'espace global
    
   
    // La fonction ci-dessous permet de récupérer la « erreur » qui correspond à notre input
    
    function getErreur(element) {
    
        while (element = element.nextSibling) {
            if (element.className === 'erreur') {
                return element;
            }
        }
        
        return false;
    
    }


    // Fonction de désactivation de l'affichage des « Erreur »
    function deactivateErreur() {
    
        var spans = document.getElementsByTagName('span'),
        spansLength = spans.length;
        
        for (var i = 0 ; i < spansLength ; i++) {
            if (spans[i].className == 'erreur') {
                spans[i].style.display = 'none';
            }
        }
    
    }
    
    
    // Fonctions de vérification du formulaire, elles renvoient « true » si tout est OK
    
    var check = {}; // On met toutes nos fonctions dans un objet littéral
    
    check['mdp'] = function(id) {
    
        var name = document.getElementById(id),
            erreurStyle = getErreur(name).style;
    
        if ((name.value.length >= 5)&&(name.value.length < 12)) {
            name.className = 'correct';
            erreurStyle.display = 'none';
            return true;
        } else {
            name.className = 'incorrect';
            erreurStyle.display = 'inline-block';
            return false;
        }
    
    };

    check['mdp2'] = function() {
    
        var mdp = document.getElementById('mdp'),
            mdp2 = document.getElementById('mdp2'),
            tooltipStyle = getTooltip(mdp2).style;
        
        if (mdp.value == mdp2.value && mdp2.value != '') {
            mdp2.className = 'correct';
            tooltipStyle.display = 'none';
            return true;
        } else {
            mdp2.className = 'incorrect';
            tooltipStyle.display = 'inline-block';
            return false;
        }
    
    };
  
   check['mdp2'] = check['mdp'];


    // Mise en place des événements
    
    (function() { // Utilisation d'une fonction anonyme pour éviter les variables globales.
    
        var form_mdp = document.getElementById('form_mdp'),
            inputs = document.getElementsByTagName('input'),
            inputsLength = inputs.length;
    
        for (var i = 0 ; i < inputsLength ; i++) {
            if (inputs[i].type == 'password') {
    
                inputs[i].onkeyup = function() {
                    check[this.id](this.id); // « this » représente l'input actuellement modifié
                };
    
            }
        }
    
        form_mdp.onsubmit = function() {
    
            var result = true;
    
            for (var i in check) {
                result = check[i](i) && result;
            }
    
            if (result) {
                return true;
            }
    
            return false;
    
        };
    
    
    })();
    
    
    // Maintenant que tout est initialisé, on peut désactiver les « Erreur »
    
    deactivateErreur();

})();
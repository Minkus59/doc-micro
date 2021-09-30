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
    
   
    check['email'] = function(id) {
    
        var name = document.getElementById(id),
            erreurStyle = getErreur(name).style;
    
        if (name.value.length >= 2) {
            name.className = 'correct';
            erreurStyle.display = 'none';
            return true;
        } else {
            name.className = 'incorrect';
            erreurStyle.display = 'inline-block';
            return false;
        }
    
    };


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

      
    
    // Mise en place des événements
    
    (function() { // Utilisation d'une fonction anonyme pour éviter les variables globales.
    
        var form_cnx = document.getElementById('form_cnx'),
            inputs = document.getElementsByTagName('input'),
            inputsLength = inputs.length;
    
        for (var i = 0 ; i < inputsLength ; i++) {
            if (inputs[i].type == 'text' || inputs[i].type == 'password') {
    
                inputs[i].onkeyup = function() {
                    check[this.id](this.id); // « this » représente l'input actuellement modifié
                };
    
            }
        }
    
        form_cnx.onsubmit = function() {
    
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
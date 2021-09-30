(function() { // On utilise une IEF pour ne pas polluer l'espace global
    
   
    // La fonction ci-dessous permet de r�cup�rer la � erreur � qui correspond � notre input
    
    function getErreur(element) {
    
        while (element = element.nextSibling) {
            if (element.className === 'erreur') {
                return element;
            }
        }
        
        return false;
    
    }

    // Fonction de d�sactivation de l'affichage des � Erreur �
    function deactivateErreur() {
    
        var spans = document.getElementsByTagName('span'),
        spansLength = spans.length;
        
        for (var i = 0 ; i < spansLength ; i++) {
            if (spans[i].className == 'erreur') {
                spans[i].style.display = 'none';
            }
        }
    
    }
    
    
    // Fonctions de v�rification du formulaire, elles renvoient � true � si tout est OK
    
    var check = {}; // On met toutes nos fonctions dans un objet litt�ral
    
    
    check['nom2'] = function(id) {
    
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

    check['valid'] = function(id) {
    
        var name = document.getElementById(id),
	    erreurStyle = getErreur(name).style;
    
        if (name.checked == true) {
	    erreurStyle.display = 'none';
            return true;
        } else {
            erreurStyle.display = 'inline-block';
            return false;
        }
    
    }; 

    check['tel2'] = function(id) {
    
        var name = document.getElementById(id),
            erreurStyle = getErreur(name).style;
    
        if ((name.value.length >= 10)&&(name.value.length < 11)) {
            name.className = 'correct';
            erreurStyle.display = 'none';
            return true;
        } else {
            name.className = 'incorrect';
            erreurStyle.display = 'inline-block';
            return false;
        }
    
    };

  
    check['prenom2'] = check['nom2'];
    check['email2'] = check['nom2'];
    check['commentaire2'] = check['nom2']; // La fonction est la m�me

   /* check['email2'] = function(id) {
	
       var name = document.getElementById(id),
       var pattern=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
       if(pattern.name) {         
            name.className = 'correct';
            erreurStyle.display = 'none';
            return true;   
       } else {   
            name.className = 'incorrect';
            erreurStyle.display = 'inline-block';
            return false;
       }
    }

   */    
    
    // Mise en place des �v�nements
    
    (function() { // Utilisation d'une fonction anonyme pour �viter les variables globales.
    
        var form_devis = document.getElementById('form_devis'),
            inputs = document.getElementsByTagName('input'),
            inputsLength = inputs.length;
    
        for (var i = 0 ; i < inputsLength ; i++) {
            if (inputs[i].type == 'text') {
    
                inputs[i].onkeyup = function() {
                    check[this.id](this.id); // � this � repr�sente l'input actuellement modifi�
                };
    
            }
        }
    
        form_devis.onsubmit = function() {
    
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
    
    
    // Maintenant que tout est initialis�, on peut d�sactiver les � Erreur �
    
    deactivateErreur();

})();
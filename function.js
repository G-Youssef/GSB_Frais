function changeVisit()
{
                
    // on va soumettre le formulaire que si on a sélectionné un vsisteur (l'indice 0 de la liste n'est pas sélectionné)
    if(document.getElementById('lstVisiteur').options[document.getElementById('lstVisiteur').selectedIndex].value !=0)
    {
        document.getElementById('idFormVisit').submit();
        
    }   
    
}

function changeMois()
{
                
    // on va soumettre le formulaire que si on a sélectionné un mois (l'indice 0 de la liste n'est pas sélectionné)
    if(document.getElementById('lstMois').options[document.getElementById('lstMois').selectedIndex].value !=0)
    {
        document.getElementById('idFormMois').submit();
        
    }
}


function modifLigneHF(idLigneHF) {
    //alert(document.getElementById('idLigneHF').value);
  document.location.replace('http://' +location.hostname + location.pathname + '?etape=validerModifLigneHF&idLigneHF=' + idLigneHF +'&dateLigneHF='+ document.getElementById('dateLigneHF' + idLigneHF).value +'&libelleLigneHF='+document.getElementById('libelleLigneHF' + idLigneHF).value +'&montantLigneHF='+ document.getElementById('montantLigneHF' + idLigneHF).value + '&lstMois='+document.getElementById('lstMois').value+'&lstVisiteur=' + document.getElementById('lstVisiteur').value);
	

}

function actualiserFraisForfait() {
    
 document.location.replace('http://' +location.hostname + location.pathname + '?etape=ValiderFraisForfait&etape2=' + document.getElementById('etape2').value + '&nuitee=' + document.getElementById('nuitee').value + '&km=' + document.getElementById('km').value + '&repas=' + document.getElementById('repas').value + '&lstMois=' + document.getElementById('lstMois').value + '&lstVisiteur=' + document.getElementById('lstVisiteur').value)   
    
}

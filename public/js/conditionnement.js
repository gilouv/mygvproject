
// permet d'alimenter la balise prix ttc apré avoir choisi le conditionnement
function choice(m)
{
  
    let prDisplay  =  document.getElementById("sel_" + m.name);  
    let proDisplay  =  document.getElementById("selp_" + m.name);
    let btPan = document.getElementById("pan_" + m.name);
    let imDisplay = document.getElementById("img_" + m.name);
    
    prDisplay.innerHTML ="";
    proDisplay.innerHTML ="";
   
   try{
      btPan.setAttribute("href", "/panier/ajouter/" + m.id );
   }
   catch(e)
    { 
      alert(e);

    }
 
   
  let prChoice  =  document.getElementById("PP_" + m.id);
  let proChoice  =  document.getElementById("PR_" + m.id);
  let imChoice  =  document.getElementById("IM_" + m.id);
  
  prDisplay.innerHTML = prChoice.innerHTML;
  proDisplay.innerHTML = proChoice.innerHTML; 
  imDisplay.setAttribute("src", imChoice.innerHTML ); 
  
  
 }

 document.addEventListener("DOMContentLoaded", function(){
        
  var cond = document.querySelectorAll('.condit');

  cond.forEach(function(Item) {
    Item.click();
  });
    


});

         
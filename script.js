function kereses(){

    let nap = document.getElementById("nap").value.trim();
    let nev = document.getElementById("nev").value.trim();
    let eredmeny = document.getElementById("eredmeny");

    let url = "";

    if(nap !== ""){
        url = "http://localhost/api/nevnapok/?nap=" + encodeURIComponent(nap);
    }
    else if(nev !== ""){
        url = "http://localhost/api/nevnapok/?nev=" + encodeURIComponent(nev);
    }
    else{
        eredmeny.innerHTML = "Adj meg egy dátumot vagy egy keresztnevet!";
        return;
    }

    fetch(url)
    .then(response => response.json())
    .then(adat => {

        if(adat.hiba){
            eredmeny.innerHTML = adat.hiba;
        }
        else if(nap !== ""){
            eredmeny.innerHTML =
                "<strong>" + adat.datum + "</strong><br>" +
                "Ezen a napon ünnepel: " +
                adat.nevnap1 + 
                (adat.nevnap2 ? ", " + adat.nevnap2 : "");
        }
        else{
            eredmeny.innerHTML =
                "<strong>" + nev + "</strong> névnapja:<br>" +
                adat.datum;
        }

    })
    .catch(error => {
        eredmeny.innerHTML = "Hiba történt az API hívás során.";
        console.log(error);
    });
}

function torles(){
    document.getElementById("nap").value = "";
    document.getElementById("nev").value = "";
    document.getElementById("eredmeny").innerHTML =
        "Itt jelenik meg a keresés eredménye.";
}
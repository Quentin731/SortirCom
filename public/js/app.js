function placeLoad() {
    console.log("test")
    let cityId = document.getElementById("create_sortie_place").value;
    let select = document.getElementById("place");
    console.log(cityId);
    fetch("http://sortir/placeList/" + cityId)
        .then(response => response.json())
        .then((places) => {
            console.log('ça marche');
            if (select === true){
                select.innerHTML = "";
            }
            console.log(places);
            for (const eachPlace of places) {
                let site =  document.createElement("option");
                site.innerText = eachPlace.placeName;
                site.value = eachPlace.id;
                console.log(site);
                select.add(site);
            }
        })
}
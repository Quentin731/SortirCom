function placeLoad() {
    ClearOptionsFast();
    let cityId = document.getElementById("create_sortie_place").value;
    let select = document.getElementById("place");
    fetch("http://127.0.0.1:8000/placeList/" + cityId)
        .then(response => response.json())
        .then((places) => {
            if (select === true){
                select.innerHTML = "";
            }
            for (const eachPlace of places) {
                let site =  document.createElement("option");
                site.innerText = eachPlace.placeName;
                site.value = eachPlace.id;
                select.add(site);
            }
        })
}
function ClearOptionsFast()
{
    let selectObj = document.getElementById("place");
    var i,L = selectObj.options.length - 1
    for(i = L; i >= 0; i--) {
        selectObj.remove(i);
    }
}
function placeLoad() {
    let cityId = document.getElementById("city").value;
    let select = document.getElementById("place");
    fetch("http://127.0.0.1:8000/placeList/" + cityId)
        .then(response => response.json())
        .then((places) => {
            select.innerHTML = "";
            for (const eachPlace of places) {
                let site =  document.createAttribute("option");
                site.innerText = eachPlace.placeName;
                select.appendChild(site);
            }
        })
}
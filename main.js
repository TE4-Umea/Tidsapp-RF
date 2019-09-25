checkIn = (projectName) => {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "https://external.umea-ntig.se/~pilwil17/rfTimeApp/checkIn.php", true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify({
    //    token: 
        text: projectName
    }));
}

logOut = () => {

} 
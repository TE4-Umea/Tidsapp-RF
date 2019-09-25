


var oAuthToken

var slackClientId = "715933475586.763249056999";
var slackClientSecret = "6765838853c54a3dab6cd33d4d4eac82";
var slackCode = new String;
var accessToken = new String;
var userId = "FRONT";

window.onload = () => {
    slackCode = getSlackCode();
    accessToken = getAccessToken();

    console.log(accessToken);
}


getSlackCode = () => {
    let params = new URLSearchParams(location.search);
    return params.get('code');
}

getAccessToken = () => {
    var token;
    var request = `https://slack.com/api/oauth.access?client_id=${slackClientId}&client_secret=${slackClientSecret}&code=${slackCode}`
    $.get(request, (appJson) => token = appJson);
    return token;
}

getProjectNameInput = () => document.getElementById('projectNameInput').value;

getCheckedIn = () => {
    var request = {
        token: "P2zoHA16O3ZuQQpQYpE7EC7M",
        user_id: userId
    }
    $.post('checkActive.php', request);
    //return isCheckedIn;
}

checkIn = () => {
    var request = {
        token: "P2zoHA16O3ZuQQpQYpE7EC7M",
        text: getProjectNameInput(),
        user_id: userId
    };
    $.post('checkIn.php', request);
}

getProjects = (userId) => {

}
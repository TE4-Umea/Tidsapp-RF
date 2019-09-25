
var userId = "FRONT";


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
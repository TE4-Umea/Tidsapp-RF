checkIn = (projectName) => {
    $.post('checkIn.php', {
        token: "P2zoHA16O3ZuQQpQYpE7EC7M",
        text: projectName,
        user_id: "FRONT"
    })
}

logOut = () => {

} 

getProjects = (userId) => {

}
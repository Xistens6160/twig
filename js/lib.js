function callAction(action) {
    $.post('../php/newAction.php', {"action": action}, function (response) {
        console.log(response);
        var data = JSON.parse(response);
        document.getElementById('output').innerHTML = data.art + data.output;
        document.getElementById('step').innerHTML = data.art2 + data.steps;
        document.getElementById('time').innerHTML = data.art3 + data.time;
        document.getElementById('button').innerHTML = data.body;
    })
}

function callScore() {
    $.post('../php/highscore.php', function (response) {
        console.log(response);
        var data = JSON.parse(response);
        console.log(data.body);
        document.getElementById('tableoutput').innerHTML = data.body;
    })
}

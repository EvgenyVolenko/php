// let maxId = jQuery('.table-responsive tbody tr:last-child td:first-child').html();

// setInterval(() => {
//     jQuery
//         .ajax({
//             method: 'POST',
//             url: 'user/indexRefresh/',
//             data: { maxId: maxId }
//         })
//         .done((response) => {
//             //data - JSON response
//             //0 => [userid, username, userlastname, userbirthday]

//             let users = jQuery.parseJSON(response);

//             if (users.length != 0) {
//                 for (const k in users) {
//                     let row = '<tr>';
//                     row += '<td>' + users[k].userid + '</td>';
//                     maxId = users[k].userid;
//                     row += '<td>' + users[k].username + '</td>';
//                     row += '<td>' + users[k].userlastname + '</td>';
//                     row += '<td>' + users[k].userbirthday + '</td>';
//                     row += '</tr>';

//                     jQuery('.content-template tbody').append(row);
//                 }
//             }
//         })
// }, 5000);

setInterval(() => {
    (
        async () => {
            const response = await fetch('/time/index');
            const answer = await response.json();
            document.querySelector('.serverTime').textContent = answer.time;
        }
    )();
}, 1000);

// setInterval(() => {
//   jQuery.ajax({
//     method:'GET',
//     url: '/page/time'
//   }).done((response)=>{
//     const time = jQuery.parseJSON(response);
//     jQuery('.serverTime').text(time);
//   });
// }, 1000);


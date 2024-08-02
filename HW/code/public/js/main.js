let maxId = jQuery('.table-responsive tbody tr:last-child td:first-child').html();

setInterval(() => {
    jQuery
        .ajax({
            method: 'POST',
            url: 'user/indexRefresh/',
            data: { maxId: maxId }
        })
        .done((data) => {
            //data - JSON response
            //0 => [userid, username, userlastname, userbirthday]

            let users = jQuery.parseJSON(data);
            if (users.length > 0) {
                for (const k in users) {
                    let row = '<tr>';
                    row += '<td>' + users[k].userid + '</td>';
                    maxId = users[k].userid;
                    row += '<td>' + users[k].username + '</td>';
                    row += '<td>' + users[k].userlastname + '</td>';
                    row += '<td>' + users[k].userbirthday + '</td>';
                    row += '</tr>';

                    jQuery('.content-template tbody').append(row);
                }
            }
        });
}, 5000);


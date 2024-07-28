<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>

</head>

<body>
<button id="start">POST запрос ajax</button>

<script>

    window.onload = function () {
        document.getElementById('start').onclick = function () {
            (async () => {
                const response = await fetch('post.php', {
                    method: 'POST',
                    headers: new Headers({
                        'Content-Type': 'application/json'
                    }),
                    body: JSON.stringify({
                        id: '1',
                        name: 'Олег Иванов',
                        age: '22'
                    }), // body data type must match "Content-Type" header
                });
                const answer = await response.json();

                console.log(answer);
            })();
        }
    }

</script>
</body>
</html>
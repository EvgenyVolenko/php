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
                    method: 'post',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: "id=1&name=Иван Иванов&age=23"
                });
                const answer = await response.json();

                console.log(answer);
            })();
        }
    }

</script>
</body>
</html>
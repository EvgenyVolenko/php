<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <style>
        body {
            background: #191f26;
        }
        #loading {
            display: none;
            position: absolute;
            top:0px;
            left:0px;
        }

    </style>
</head>

<body>
<button id="show" class="show">Покажи собачку</button><br>
<img width="300" src="" id="dogImage">
<span id="loading"><img width="300" src="download.gif"></span>
<script>

    $(document).ready(function(){
        $(".show").on('click', function(){
            $("#loading").css('display', 'block');

            $.ajax({
                url: "https://dog.ceo/api/breeds/image/random",
                type: "GET",
                dataType : "json",
                data:{
                },
                error: function() {alert("Что-то пошло не так...");},
                success: function(answer){
                    $("#dogImage").attr('src', answer.message);
                    $("#loading").css('display', 'none');
                    console.log(answer.message);
                }

            })
        });

    });


    /*
      // fetch на промисах современный
      window.onload = function () {
          document.getElementById('show').onclick = function () {
              $("#loading").css('display', 'block');
              (async () => {
                  const response = await fetch('https://dog.ceo/api/breeds/image/random');
                  const answer = await response.json();

                  $("#dogImage").attr('src', answer.message);
                  $("#loading").css('display', 'none');
                  console.log(answer.status);
              })();
          }
      }


      //на XMLHttpRequest
          function reqListener() {
              var answer = JSON.parse(this.responseText);
              $("#dogImage").attr('src', answer.message);
              $("#loading").css('display', 'none');
              console.log(answer.status); //
          }

          function reqError(err) {
              console.log('Fetch Error :-S', err);
          }

          window.onload = function () {
              document.getElementById('show').onclick = function () {
              $("#loading").css('display', 'block');
                  var oReq = new XMLHttpRequest();
                  oReq.onload = reqListener;
                  oReq.onerror = reqError;
                  oReq.open('get', 'https://dog.ceo/api/breeds/image/random', true);
                  oReq.send();
              }
          }


  */

    /*

        // fetch на промисах старый способ


              window.onload = function () {
                document.getElementById('show').onclick = function(e) {
                    $("#loading").css('display', 'block');
                    fetch('https://dog.ceo/api/breeds/image/random')
                        .then(function(response) {
                            console.log(response.headers.get('Content-Type')); // application/json; charset=utf-8
                            console.log(response.status); // 200

                            return response.json();
                        })
                        .then(function(answer) {
                            $("#dogImage").attr('src', answer.message);
                            $("#loading").css('display', 'none');
                            console.log(answer.status); //
                        })
                        .catch( alert );
                }
            }

    */



</script>
</body>
</html>
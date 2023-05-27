<?php

    include "koneksi.php";
    $koneksi = new koneksi();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kalkulator Sederhana</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet"/>

</head>
<body>
    
<div id="kalkulator">
        <h4 class="text-white">Program Kalkulator</h4>
        <button type="button" 
            id="btn-history" 
            class="btn btn-primary mb-2" data-toggle="modal" data-target="#exampleModalLong">
         History
        </button>
        <!-- Modal untuk menampilkan riwayat hitung -->
        <div class="modal fade" id="modal-history" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Riwayat Hitungan</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="list-group">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
        </div>

        <div id="input-wrap">
            <!-- untuk txt kecil di atas layar -->
            <div id="tmp"></div> 

            <!-- untuk txt utama di layar -->
            <div id="input"></div>
        </div>
        <div id="button-wrap">
            <button id="all-clear">AC</button>
            <button id="clear">C</button>
            <button class="amt pangkat">^</button>
            <button class="amt divide">/</button>

            <button class="btn btn-primary nomor">7</button>
            <button class="btn btn-primary nomor">8</button>
            <button class="btn btn-primary nomor">9</button>
            <button class="amt multiplication">*</button>

            <button class="btn btn-primary nomor">4</button>
            <button class="btn btn-primary nomor">5</button>
            <button class="btn btn-primary nomor">6</button>
            <button class="amt minus">-</button>

            <button class="btn btn-primary nomor">1</button>
            <button class="btn btn-primary nomor">2</button>
            <button class="btn btn-primary nomor">3</button>
            <button class="amt plus">+</button>

            <button class="nomor no-0">0</button>
            <button class="amt modulo">%</button>
            <button id="res">=</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

    <script type="text/javascript">
        const input = document.getElementById("input");
        const tmp = document.getElementById("tmp");

        const empty = (element) => { // menghapus text pada layar
            element.innerText = "";
        };

        $('#all-clear').click(function(){ // menghapus semua inputan
            empty(tmp);
            empty(input);
        });

        $('#clear').click(function(){ // menghapus temporary inputan
            empty(input);
        });

        document.querySelectorAll(".nomor").forEach((element) => {
            element.addEventListener("click", () => {
                if (input.innerText.length > 20) // jika angka memuat terlalu banyak
                    return alert("Angka yang dimuat terlalu banyak!");

                input.innerText += element.innerText;
            });
        });

        document.querySelectorAll(".amt").forEach((element) => {
            element.addEventListener("click", () => {
                if (input.innerText) {
                    if (tmp.innerText) {
                        tmp.innerText = `${tmp.innerText} ${input.innerText} ${element.innerText}`;
                    } else {
                        tmp.innerText = `${input.innerText} ${element.innerText}`;
                    }
                } else if (tmp.innerText.slice(-1).match(/-|\+|\*|\%|\^|\//)) {
                    let string = tmp.innerText.slice(0, -1);
                    string += element.innerText;
                    tmp.innerText = string;
                }
                empty(input);
            });
        });

        function clickAngka(hitung){ // memasukkan data history ke layar input kalkulator
            $("#modal-history").modal('hide');
            const regex = /-|\+|\*|\%|\^|\//;
            var pos = hitung.search(regex); // return index position
            var tempAngka = hitung.substring(0,pos);
            var inpAngka = hitung.substring(pos, hitung.length);
            tmp.innerText = tempAngka;
            input.innerText = inpAngka;
        }

        $(document).ready(function(){
            $('#btn-history').click(function(){ // mengambil data history pada database
                $('#modal-history').find('.list-group').empty();
                $.ajax({
                    url:"prosesShow.php",
                    type:"GET",
                    success: function(response){
                        var data = JSON.parse(response);
                        for(var i =0; i<data.length; i++){
                            $('#modal-history').find('.list-group').append(
                            `<a href="javaScript:void(0);" 
                                onclick="clickAngka('${data[i].perhitungan}')"
                                class="list-history list-group-item list-group-item-action">
                                ${data[i].perhitungan} = ${data[i].hasil}
                                </a>`  
                            );
                        }
                    }
                });
                $('#modal-history').modal('show');
            });

            //input hasil perhitungan ke database
            $('#res').click(function(){
                if (input.innerText) {
                    if(tmp.innerText.substring(tmp.innerText.length-1) == '^'){
                        let a1 = parseInt(tmp.innerText.slice(0,-1));
                        let a2 = parseInt(input.innerText);
                        var res = Math.pow(a1, a2);
                        var hitung = `${a1} ^ ${a2}`;
                        input.innerText = res;
                    }else{
                        var res = eval(tmp.innerText + input.innerText);
                        var hitung = `${tmp.innerText} ${input.innerText}`;
                        input.innerText = res;
                    }
                    if(hitung.match(/-|\+|\*|\%|\^|\//)){
                        sendData(hitung, res); // untuk mengirimkan data ke database
                    }
                    empty(tmp);
                }
            });

            function sendData(hitung,hasil){ // menginput ke database
                $.ajax({
                    url:"prosesInsert.php",
                    method:'post',
                    data:{
                        perhitungan:hitung,
                        hasil:hasil
                    },
                    success: function(response){ 
                        console.log(response);
                    }
                });
            }

        });
    </script>
</body>
</html>
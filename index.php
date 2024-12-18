<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sorteio Habitacional</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Roboto:700" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css"
        integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">

    <link href="css.css" rel="stylesheet">

</head>

<body>

    <header class="cabecalho">
        <img id="logos" src="img/logo-sao-luis.png">
    </header>



    <div class="watermark">
        <img src="img/habitacional.png" alt="Marca d'água">
    </div>

    <main role="main" class="container">

        <div class="card blue box">
            <div class="padding">
                <h1>Sorteio de Endereços do Residencial Mato Grosso I, II e III</h1>
            </div>
        </div>

        <div class="card blue box">
            <div class="padding">

                <form onsubmit="return false" id="form1">
                    <div class="row ">
                        <div class="form-group col-12">
                            <label for="sorteio">SELECIONE O SORTEIO</label>
                            <select name="sorteio" class="form-control" id="sorteio">
                                <option></option>
                                <option value="mt1-pcd">Mato Grosso I - PCDs</option>
                                <option value="mt1-geral">Mato Grosso I - Geral</option>
                                <option value="mt2-pcd">Mato Grosso II - PCDs</option>
                                <option value="mt2-geral">Mato Grosso II - Geral</option>
                                <option value="mt3-pcd">Mato Grosso III - PCDs</option>
                                <option value="mt3-geral">Mato Grosso III - Geral</option>
                            </select>
                        </div>
                    </div>


                    <button id="bt_sorteio" style="width: 100%;" type="submit"
                        class="btn botao-sortear">SORTEAR</button>


                    <a href='#' onclick='location.reload(true); return false;'>
                        <button style="width: 100%;margin-top: 10px;" class="btn botao-novo-sorteio">
                            NOVO SORTEIO
                        </button>
                    </a>
                </form>

            </div>
        </div>

        <div class="hide card blue box result-outter" id="card_sorteio" style="display: none;">
            <div id="result" class="padding result-outter result-container">
                <h5></h5>
            </div>
        </div>

    </main>

    <footer>
        <img id="logos" src="img/gov-brasil.png" alt="Governo do Brasil">
        <img id="logos" src="img/minha-casa.png" alt="Minha Casa">
        <img id="logos" src="img/caixa.png" alt="Caixa">
        <img id="logos" src="img/semurh.png" alt="SEMURH">
    </footer>

    <script>
        $(function () {
            let executando_sorteio = false;
            let resultados = []; // Para armazenar os dados recebidos
            let indiceAtual = 0; // Índice atual do resultado exibido
            const divResultado = document.getElementById('result');

            // Parâmetros ajustáveis
            const tempoAnimacao = 3000;
            const linhasPorVez = 8; // Número de linhas exibidas de cada vez
            const tempoPorExibicao = 4000; // Tempo (em ms) para exibição de cada conjunto de linhas

            // Função que exibe "Processando..." e, após 3 segundos, mostra o resultado
            function exibirAnimacao() {

                $('#card_sorteio').show();

                // Exibe "Processando..."
                divResultado.innerHTML = "<h5>Processando...</h5>";

                // Aguarda x segundos e exibe o resultado do sorteio
                setTimeout(exibirConcluido, tempoAnimacao);
            }

            function exibirConcluido() {

                // Exibe "Concluído"
                divResultado.innerHTML = "<h5>Concluído! Exibindo resultados:</h5>";

                console.log("Iniciando exibição");
                // Inicia a exibição
                indiceAtual = 0; // Resetamos o índice
                // atualizarExibicao();

                // Aguarda x segundos e exibe o resultado do sorteio
                setTimeout(atualizarExibicao, tempoAnimacao);
            }

            // Função de atualização de exibição
            function atualizarExibicao() {
                if (indiceAtual < resultados.length) {

                    // $('#card_sorteio').show();

                    // Seleciona o próximo grupo de linhas baseado em linhasPorVez
                    const grupo = resultados.slice(indiceAtual, indiceAtual + linhasPorVez);

                    // Monta o HTML para o grupo de linhas
                    let html = "";
                    grupo.forEach((item, idx) => {
                        html += `<strong>${indiceAtual + idx + 1}:</strong> ${item.nome} - ${item.cpf_anonim} <br> 
                        Casa: ${item.codigo}<br><br>`;
                    });

                    // Atualiza o conteúdo da div de resultados
                    $('#result').html(html);

                    var cardSorteio = document.getElementById("card_sorteio");
                    cardSorteio.scrollIntoView({behavior: "smooth"});

                    // Incrementa o índice atual para o próximo grupo
                    indiceAtual += linhasPorVez;

                    // Exibe o próximo grupo após o intervalo configurado
                    setTimeout(atualizarExibicao, tempoPorExibicao);

                } else {
                    console.log("Exibição concluída");
                }
            }


            $('#bt_sorteio').click(function () {
                const sorteio = $('#sorteio').val();
                $('#card_sorteio').hide();
                $('#result').html('Processando...');

                $.ajax({
                    url: "script_sorteio.php",
                    method: "POST",
                    data: { sorteio },
                    success: function (data) {

                        console.log("Dados recebidos do PHP (brutos):", data);
                        resultados = JSON.parse(data);

                        console.log("Dados processados:", resultados);

                        console.log("Exibindo animação");

                        // Chamando a função para exibir o processo
                        exibirAnimacao();

                    },
                    error: function (xhr, status, error) {
                        $('#result').html('Erro ao processar o sorteio. Tente novamente.');
                        console.error(error);
                    }
                });
            });
        });
    </script>
</body>

</html>
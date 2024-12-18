<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sorteio = $_POST['sorteio'];

    // Arquivos associados ao sorteio selecionado
    $arquivos = [
        'mt1-geral' => [
            'pessoas' => 'arquivos_sorteio/mt1-geral-pessoas.csv',
            'casas' => 'arquivos_sorteio/mt1-geral-casas.csv',
            'resultado' => 'resultado/resultado_mt1_geral.csv',
            'casas_nao_sorteadas' => 'resultado/mt1-geral-casas-nao-sorteadas.csv',
        ],
        'mt2-geral' => [
            'pessoas' => 'arquivos_sorteio/mt2-geral-pessoas.csv',
            'casas' => 'arquivos_sorteio/mt2-geral-casas.csv',
            'resultado' => 'resultado/resultado_mt2_geral.csv',
            'casas_nao_sorteadas' => 'resultado/mt2-geral-casas-nao-sorteadas.csv',
        ],
        'mt3-geral' => [
            'pessoas' => 'arquivos_sorteio/mt3-geral-pessoas.csv',
            'casas' => 'arquivos_sorteio/mt3-geral-casas.csv',
            'resultado' => 'resultado/resultado_mt3_geral.csv',
            'casas_nao_sorteadas' => 'resultado/mt3-geral-casas-nao-sorteadas.csv',
        ],
        'mt1-pcd' => [
            'pessoas' => 'arquivos_sorteio/mt1-pcd-pessoas.csv',
            'casas' => 'arquivos_sorteio/mt1-pcd-casas.csv',
            'resultado' => 'resultado/resultado_mt1_pcd.csv',
            'casas_nao_sorteadas' => 'resultado/mt1-pcd-casas-nao-sorteadas.csv',
        ],
        'mt2-pcd' => [
            'pessoas' => 'arquivos_sorteio/mt2-pcd-pessoas.csv',
            'casas' => 'arquivos_sorteio/mt2-pcd-casas.csv',
            'resultado' => 'resultado/resultado_mt2_pcd.csv',
            'casas_nao_sorteadas' => 'resultado/mt2-pcd-casas-nao-sorteadas.csv',
        ],
        'mt3-pcd' => [
            'pessoas' => 'arquivos_sorteio/mt3-pcd-pessoas.csv',
            'casas' => 'arquivos_sorteio/mt3-pcd-casas.csv',
            'resultado' => 'resultado/resultado_mt3_pcd.csv',
            'casas_nao_sorteadas' => 'resultado/mt3-pcd-casas-nao-sorteadas.csv',
        ],
    ];

    if (!array_key_exists($sorteio, $arquivos)) {
        echo json_encode(['error' => 'Sorteio inválido']);
        exit;
    }

    $arquivoPessoas = $arquivos[$sorteio]['pessoas'];
    $arquivoCasas = $arquivos[$sorteio]['casas'];
    $arquivoResultado = $arquivos[$sorteio]['resultado'];
    $arquivoCasasNaoSorteadas = $arquivos[$sorteio]['casas_nao_sorteadas'];

    // Lendo arquivos
    $pessoas = array_map('str_getcsv', file($arquivoPessoas));
    $casas = array_map('str_getcsv', file($arquivoCasas));

    // Embaralhando as listas
    // shuffle($pessoas);
    shuffle($casas);

    // Validando que o número de pessoas é menor ou igual ao número de casas
    if (count($pessoas) > count($casas)) {
        echo json_encode(['error' => 'Número de pessoas excede o número de casas disponíveis']);
        exit;
    }

    // Gerando resultados
    $resultados = [];
    $casasSorteadas = [];
    foreach ($pessoas as $index => $pessoa) {
        $casa = $casas[$index];
        $resultados[] = [
            'nome' => $pessoa[0],
            'cpf' => $pessoa[1],
            'cpf_anonim' => $pessoa[2],
            'rua' => $casa[0],
            'quadra' => $casa[1],
            'casa' => $casa[2],
            'codigo' => $casa[3],
        ];
        $casasSorteadas[] = $index; // Armazena o índice das casas sorteadas
    }

    // Salvando resultados no arquivo CSV
    $fp = fopen($arquivoResultado, 'w');
    foreach ($resultados as $resultado) {
        fputcsv($fp, $resultado);
    }
    fclose($fp);

    // Identificando casas não sorteadas
    $casasNaoSorteadas = [];
    foreach ($casas as $index => $casa) {
        if (!in_array($index, $casasSorteadas)) {
            $casasNaoSorteadas[] = $casa;
        }
    }

    // Salvando casas não sorteadas no arquivo CSV
    $fp = fopen($arquivoCasasNaoSorteadas, 'w');
    foreach ($casasNaoSorteadas as $casa) {
        fputcsv($fp, $casa);
    }
    fclose($fp);

    // Retornando resultados como JSON
    echo json_encode($resultados);
}

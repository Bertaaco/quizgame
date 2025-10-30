<?php
// Verifica se a requisição é do tipo POST. Isso acontece quando o formulário é enviado.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Obtém o número total de questões, convertendo o valor para inteiro.
    // Se não for fornecido, o valor padrão será 10.
    $total_questions = intval($_POST['text_total_questions'] ?? 10);

    // Chama a função 'prepare_game' para preparar os dados do jogo com o número de questões definido.
    prepare_game($total_questions);

    // Redireciona para a página do jogo (route=game), para que o jogo possa começar a jogar.
    header('Location: index.php?route=game');
    exit; // É essencial interromper aqui para evitar que o código abaixo seja executado.
}

// Função para preparar os dados do jogo com base no número total de questões fornecido.
function prepare_game($total_questions)
{
    // A variável global $capitals, que contém os dados das capitais.
    global $capitals;

    // Inicializa um array para armazenar os IDs das capitais selecionadas aleatoriamente.
    $ids = [];

    // Gera uma lista de números aleatórios dentro do intervalo de índices do array $capitals.
    while (count($ids) < $total_questions) {
        $id = rand(0, count($capitals) - 1);
        // Verifica se o ID já foi selecionado. Se não, adiciona ao array $ids.
        if (!in_array($id, $ids)) {
            $ids[] = $id;
        }
    }

    // Inicializa o array $questions para armazenar as questões do jogo.
    $questions = [];

    // Para cada ID selecionado, cria uma questão.
    foreach ($ids as $id) {
        // Inicializa um array para armazenar as respostas possíveis.
        $answers = [];

        // A primeira resposta é sempre o ID correto (capital correspondente ao país).
        $answers[] = $id;

        // Preenche as outras respostas com IDs aleatórios que não sejam iguais ao correto.
        while (count($answers) < 4) {
            $tmp = rand(0, count($capitals) - 1);
            // Verifica se essa resposta já foi selecionada. Se não, adiciona à lista de respostas.
            if (!in_array($tmp, $answers)) {
                $answers[] = $tmp;
            }
        }

        // Embaralha as respostas para que a correta não fique sempre na mesma posição.
        shuffle($answers);

        // Adiciona a questão ao array $questions.
        $questions[] = [
            'question' => $capitals[$id][0],   // O nome do país.
            'correct' => $capitals[$id][1],    // O ID da resposta correta (capital).
            'answers' => $answers               // As 4 respostas possíveis (uma correta, duas erradas).
        ];
    }

    // Armazena as questões na sessão para que possam ser acessadas em outras páginas.
    $_SESSION['questions'] = $questions;

    // Inicializa variáveis do jogo na sessão.
    $_SESSION['game'] = [
        'total_questions' => $total_questions, // Número total de questões
        'current_question' => 0,               // Começa com a primeira questão.
        'correct_answers' => 0,                // Inicializa o número de respostas corretas.
        'incorrect_answers' => 0               // Inicializa o número de respostas erradas.
    ];
}
?>


<!-- Início do código HTML para exibir o formulário na página de início -->
<div class="container">
<div class="row">
    <!-- Título principal do jogo -->
    <h1>Quiz das Capitais</h1>
    <hr>

    <!-- Formulário para o usuário definir o número de questões e iniciar o jogo -->
    <form action="index.php?route=start" method="post">
        <!-- Pergunta sobre o número de questões -->
        <h3>
            <label for="text_total_questions" class="form-label">Número de questões:</label>
            <!-- Valor inicial 10, mínimo 1 e máximo 20 -->
            <input type="number" class="form-control" id="text_total_questions"
                   name="text_total_questions" value="10" min="1" max="20">
        </h3>

        <!-- Botão para enviar o formulário -->
        <div>
            <button type="submit" class="btn">Iniciar</button>
        </div>
    </form>
</div>

</div>

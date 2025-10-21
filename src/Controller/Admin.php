<?php
namespace GrupoA\Supermercado\Controller;


use GrupoA\Supermercado\Model\Produto;
use GrupoA\Supermercado\Model\Database;

class Admin
{
    private \Twig\Environment $ambiente;
    private \Twig\Loader\FilesystemLoader $carregador;

    public function __construct()
    {
        // Inicia a sessão
        // Verifica se o usuário está logado
        // Se não estiver, redireciona para a página de login
        session_start();
        if (!isset($_SESSION["usuario"])) {
            header("Location: /login");
            exit;
        }

        // Construtor da classe
        $this->carregador =
            new \Twig\Loader\FilesystemLoader("./src/View");

        // Combina os dados com o template
        $this->ambiente =
            new \Twig\Environment($this->carregador);
    }

    /**
     * Exibe o formulário para criação de um novo produto
     * @param array $dados
     * @return void
     */
    public function formularioNovoProduto(array $dados)
    {
        echo $this->ambiente->render("formularioNovoProduto.html", $dados);
    }

    /**
     * Salva um novo produto
     * @param array $dados
     * @return void
     */
    public function salvaProduto(array $dados)
    {
        $nome = trim($dados["nome"]);
        $valor = $dados["valor"];

        $avisos = "";

        if ($nome != "" || $valor > 0) {
            $produto = new Produto();
            $produto->nome = $nome;
            $produto->valor = $valor;

            $bd = new Database();
            if ($bd->saveProduto($produto)) {
                // Avisa que deu certo
                $avisos .= "Produto cadastrado com sucesso.";
            } else {
                // Avisa que deu errado
                $avisos .= "Erro ao cadastrar produto.";
            }
        }

        $dados["avisos"] = $avisos;
        echo $this->ambiente->render("formularioNovoProduto.html", $dados);
    }

    public function listaProdutos(array $dados)
    {
        $bd = new Database();
        $produtos = $bd->loadProdutos();

        echo $this->ambiente->render("produtos.html", ["produtos" => $produtos]);
    }

    // Próxima aula "rotas dinâmicas"
    public function mostraProduto(array $dados)
    {
        $id = $dados["id"];

        $bd = new Database();
        $produto = $bd->loadProdutoById($id);

        if ($produto) {
            echo $this->ambiente->render("mostraProduto.html", ["produto" => $produto]);
        } else {
            echo $this->ambiente->render("mostraProduto.html", ["erro" => "Produto não encontrado."]);
        }
    }

    public function formularioNovoEndereco(array $dados)
    {
        echo $this->ambiente->render("formularioNovoEndereco.html", $dados);
    }

    /**
     * Salva um novo produto
     * @param array $dados
     * @return void
     */
}
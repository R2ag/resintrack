<?php
class Controller
{
    protected function view($view, $data = [])
    {
        extract($data);
        require "../views/layout/header.php";
        require "../views/$view.php";
        require "../views/layout/footer.php";
    }

    public function proteger()
    {
        if (!isset($_SESSION['usuario'])) {
            header("Location: " . BASE_URL . "index.php?page=login");
            exit;
        }
    }
}

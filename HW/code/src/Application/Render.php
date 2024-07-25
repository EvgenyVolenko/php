<?php

namespace Geekbrains\Application1\Application;

use Exception;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class Render
{
    private string $viewFolder = '/src/Domain/Views/';
    private FilesystemLoader $loader;
    private Environment $environment;


    public function __construct()
    {
        $this->loader = new FilesystemLoader($_SERVER['DOCUMENT_ROOT'] . $this->viewFolder);
        $this->environment = new Environment($this->loader, [
            // 'cache' => $_SERVER['DOCUMENT_ROOT'].'/cache/',
        ]);
    }


    public function renderPage(string $contentTemplateName = 'page-index.twig', array $templateVariables = [])
    {
        $template = $this->environment->load('main.twig');

        $templateVariables['header'] = 'header.twig';
        $templateVariables['nav'] = 'navigation.twig';
        $templateVariables['footer'] = 'footer.twig';
        $templateVariables['content_template_name'] = $contentTemplateName;

        return $template->render($templateVariables);
    }

    public static function renderExceptionPage(Exception $exception): string
    {
        $contentTemplateName = "error.twig";
        $viewFolder = '/src/Domain/Views/';

        $loader = new FilesystemLoader($_SERVER['DOCUMENT_ROOT'] . $viewFolder);
        $environment = new Environment($loader, [
            // 'cache' => $_SERVER['DOCUMENT_ROOT'] . '/cache/',
        ]);

        $template = $environment->load('main.twig');
        $templateVariables['header'] = 'header.twig';
        $templateVariables['nav'] = 'navigation.twig';
        $templateVariables['footer'] = 'footer.twig';

        $templateVariables['content_template_name'] = $contentTemplateName;
        $templateVariables['error_message'] = $exception->getMessage();

        return $template->render($templateVariables);
    }

    public function renderPageWithForm(string $contentTemplateName = 'page-index.tpl', array $templateVariables = [])
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        $templateVariables['csrf_token'] = $_SESSION['csrf_token'];

        return $this->renderPage($contentTemplateName, $templateVariables);
    }
}

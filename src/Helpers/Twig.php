<?php


namespace App\Helpers;


use App\Exceptions\TwigRenderException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class Twig
{
    public static function render(string $fileName, string $directoryPath, ?array $data = []): string
    {
        $loader = new FilesystemLoader($directoryPath, getcwd());
        $twig = new Environment($loader, [
            'debug' => true,
        ]);
        try {
            return $twig->render($fileName, $data);
        } catch (LoaderError $e) {
            throw new TwigRenderException($e->getMessage(), $e->getCode());
        } catch (RuntimeError $e) {
            throw new TwigRenderException($e->getMessage(), $e->getCode());
        } catch (SyntaxError $e) {
            throw new TwigRenderException($e->getMessage(), $e->getCode());
        }
    }



}

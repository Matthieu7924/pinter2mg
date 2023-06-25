<?php

namespace App\Controller;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;

class LanguageController extends AbstractController
{
    #[Route('/change-locale/{locale}', name: 'change_locale')]
    public function changeLocale(Request $request, string $locale, SessionInterface $session)
    {
        // Mettre à jour la valeur de default_locale dans translation.yaml
        $translationFilePath = $this->getParameter('kernel.project_dir') . '/config/packages/translation.yaml';

        // Lire le contenu du fichier de configuration
        $filesystem = new Filesystem();
        $configContent = file_get_contents($translationFilePath);

        // Modifier la valeur de default_locale en fonction de la langue sélectionnée
        $config = Yaml::parse($configContent);
        $config['framework']['default_locale'] = $locale;
        $newConfigContent = Yaml::dump($config);

        // Écrire le nouveau contenu dans le fichier de configuration
        $filesystem->dumpFile($translationFilePath, $newConfigContent);

        // Retourner une réponse indiquant un changement de langue réussi
        return $this->redirectToRoute('app_home');
    }
}